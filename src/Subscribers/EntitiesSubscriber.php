<?php declare(strict_types = 1);

/**
 * EntitiesSubscriber.php
 *
 * @license        More in LICENSE.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     Subscribers
 * @since          0.1.0
 *
 * @date           28.08.20
 */

namespace FastyBird\TriggersModule\Subscribers;

use Doctrine\Common;
use Doctrine\ORM;
use Doctrine\Persistence;
use FastyBird\ExchangePlugin\Publisher as ExchangePluginPublisher;
use FastyBird\ModulesMetadata\Types as ModulesMetadataTypes;
use FastyBird\TriggersModule;
use FastyBird\TriggersModule\Entities;
use FastyBird\TriggersModule\Models;
use Nette;
use ReflectionClass;
use ReflectionException;

/**
 * Doctrine entities events
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Subscribers
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
final class EntitiesSubscriber implements Common\EventSubscriber
{

	private const ACTION_CREATED = 'created';
	private const ACTION_UPDATED = 'updated';
	private const ACTION_DELETED = 'deleted';

	use Nette\SmartObject;

	/** @var Models\States\ITriggerItemRepository|null */
	private ?Models\States\ITriggerItemRepository $triggerItemRepository;

	/** @var ExchangePluginPublisher\IPublisher */
	private ExchangePluginPublisher\IPublisher $publisher;

	/** @var ORM\EntityManagerInterface */
	private ORM\EntityManagerInterface $entityManager;

	public function __construct(
		ExchangePluginPublisher\IPublisher $publisher,
		ORM\EntityManagerInterface $entityManager,
		?Models\States\ITriggerItemRepository $triggerItemRepository = null
	) {
		$this->triggerItemRepository = $triggerItemRepository;
		$this->publisher = $publisher;
		$this->entityManager = $entityManager;
	}

	/**
	 * Register events
	 *
	 * @return string[]
	 */
	public function getSubscribedEvents(): array
	{
		return [
			ORM\Events::onFlush,
			ORM\Events::prePersist,
			ORM\Events::postPersist,
			ORM\Events::postUpdate,
		];
	}

	/**
	 * @return void
	 */
	public function onFlush(): void
	{
		$uow = $this->entityManager->getUnitOfWork();

		$processedEntities = [];

		$processEntities = [];

		foreach ($uow->getScheduledEntityDeletions() as $entity) {
			// Check for valid entity
			if (!$entity instanceof Entities\IEntity || !$this->validateNamespace($entity)) {
				continue;
			}

			// Doctrine is fine deleting elements multiple times. We are not.
			$hash = $this->getHash($entity, $uow->getEntityIdentifier($entity));

			if (in_array($hash, $processedEntities, true)) {
				continue;
			}

			$processedEntities[] = $hash;

			$processEntities[] = $entity;
		}

		foreach ($processEntities as $entity) {
			$this->processEntityAction($entity, self::ACTION_DELETED);
		}
	}

	/**
	 * @param ORM\Event\LifecycleEventArgs $eventArgs
	 *
	 * @return void
	 */
	public function prePersist(ORM\Event\LifecycleEventArgs $eventArgs): void
	{
		$entity = $eventArgs->getObject();

		// Check for valid entity
		if (!$entity instanceof Entities\IEntity || !$this->validateNamespace($entity)) {
			return;
		}

		if ($entity instanceof Entities\Triggers\IManualTrigger) {
			new Entities\Triggers\Controls\Control(
				ModulesMetadataTypes\ControlNameType::NAME_TRIGGER,
				$entity,
			);
		}
	}

	/**
	 * @param ORM\Event\LifecycleEventArgs $eventArgs
	 *
	 * @return void
	 */
	public function postPersist(ORM\Event\LifecycleEventArgs $eventArgs): void
	{
		// onFlush was executed before, everything already initialized
		$entity = $eventArgs->getObject();

		// Check for valid entity
		if (!$entity instanceof Entities\IEntity || !$this->validateNamespace($entity)) {
			return;
		}

		$this->processEntityAction($entity, self::ACTION_CREATED);
	}

	/**
	 * @param ORM\Event\LifecycleEventArgs $eventArgs
	 *
	 * @return void
	 */
	public function postUpdate(ORM\Event\LifecycleEventArgs $eventArgs): void
	{
		$uow = $this->entityManager->getUnitOfWork();

		// onFlush was executed before, everything already initialized
		$entity = $eventArgs->getObject();

		// Get changes => should be already computed here (is a listener)
		$changeset = $uow->getEntityChangeSet($entity);

		// If we have no changes left => don't create revision log
		if (count($changeset) === 0) {
			return;
		}

		// Check for valid entity
		if (
			!$entity instanceof Entities\IEntity
			|| !$this->validateNamespace($entity)
			|| $uow->isScheduledForDelete($entity)
		) {
			return;
		}

		$this->processEntityAction($entity, self::ACTION_UPDATED);
	}

	/**
	 * @param Entities\IEntity $entity
	 * @param string $action
	 *
	 * @return void
	 */
	private function processEntityAction(Entities\IEntity $entity, string $action): void
	{
		if (!method_exists($entity, 'toArray')) {
			return;
		}

		$publishRoutingKey = null;

		switch ($action) {
			case self::ACTION_CREATED:
				foreach (TriggersModule\Constants::MESSAGE_BUS_CREATED_ENTITIES_ROUTING_KEYS_MAPPING as $class => $routingKey) {
					if ($this->validateEntity($entity, $class)) {
						$publishRoutingKey = ModulesMetadataTypes\RoutingKeyType::get($routingKey);
					}
				}

				break;

			case self::ACTION_UPDATED:
				foreach (TriggersModule\Constants::MESSAGE_BUS_UPDATED_ENTITIES_ROUTING_KEYS_MAPPING as $class => $routingKey) {
					if ($this->validateEntity($entity, $class)) {
						$publishRoutingKey = ModulesMetadataTypes\RoutingKeyType::get($routingKey);
					}
				}

				break;

			case self::ACTION_DELETED:
				foreach (TriggersModule\Constants::MESSAGE_BUS_DELETED_ENTITIES_ROUTING_KEYS_MAPPING as $class => $routingKey) {
					if ($this->validateEntity($entity, $class)) {
						$publishRoutingKey = ModulesMetadataTypes\RoutingKeyType::get($routingKey);
					}
				}

				break;
		}

		if ($publishRoutingKey !== null) {
			if ($entity instanceof Entities\Actions\IAction && $this->triggerItemRepository !== null) {
				$state = $this->triggerItemRepository->findOne($entity->getId());

				$this->publisher->publish(
					ModulesMetadataTypes\ModuleOriginType::get(ModulesMetadataTypes\ModuleOriginType::ORIGIN_MODULE_TRIGGERS),
					$publishRoutingKey,
					array_merge($state !== null ? [
						'is_triggered' => $state->getValidationResult(),
					] : [], $entity->toArray())
				);

			} elseif ($entity instanceof Entities\Conditions\ICondition && $this->triggerItemRepository !== null) {
				$state = $this->triggerItemRepository->findOne($entity->getId());

				$this->publisher->publish(
					ModulesMetadataTypes\ModuleOriginType::get(ModulesMetadataTypes\ModuleOriginType::ORIGIN_MODULE_TRIGGERS),
					$publishRoutingKey,
					array_merge($state !== null ? [
						'is_fulfilled' => $state->getValidationResult(),
					] : [], $entity->toArray())
				);

			} elseif ($entity instanceof Entities\Triggers\ITrigger && $this->triggerItemRepository !== null) {
				$isTriggered = true;

				foreach ($entity->getActions() as $action) {
					$state = $this->triggerItemRepository->findOne($action->getId());

					if ($state === null || $state->getValidationResult() === false) {
						$isTriggered = false;
					}
				}

				if ($entity instanceof Entities\Triggers\IAutomaticTrigger) {
					$isFulfilled = true;

					foreach ($entity->getConditions() as $condition) {
						$state = $this->triggerItemRepository->findOne($condition->getId());

						if ($state === null || $state->getValidationResult() === false) {
							$isFulfilled = false;
						}
					}

					$this->publisher->publish(
						ModulesMetadataTypes\ModuleOriginType::get(ModulesMetadataTypes\ModuleOriginType::ORIGIN_MODULE_TRIGGERS),
						$publishRoutingKey,
						array_merge([
							'is_triggered' => $isTriggered,
							'is_fulfilled' => $isFulfilled,
						], $entity->toArray())
					);

				} else {
					$this->publisher->publish(
						ModulesMetadataTypes\ModuleOriginType::get(ModulesMetadataTypes\ModuleOriginType::ORIGIN_MODULE_TRIGGERS),
						$publishRoutingKey,
						array_merge([
							'is_triggered' => $isTriggered,
						], $entity->toArray())
					);
				}
			} else {
				$this->publisher->publish(
					ModulesMetadataTypes\ModuleOriginType::get(ModulesMetadataTypes\ModuleOriginType::ORIGIN_MODULE_TRIGGERS),
					$publishRoutingKey,
					$entity->toArray()
				);
			}
		}
	}

	/**
	 * @param Entities\IEntity $entity
	 * @param string $class
	 *
	 * @return bool
	 */
	private function validateEntity(Entities\IEntity $entity, string $class): bool
	{
		$result = false;

		if (get_class($entity) === $class) {
			$result = true;
		}

		if (is_subclass_of($entity, $class)) {
			$result = true;
		}

		return $result;
	}

	/**
	 * @param Entities\IEntity $entity
	 * @param mixed[] $identifier
	 *
	 * @return string
	 */
	private function getHash(Entities\IEntity $entity, array $identifier): string
	{
		return implode(
			' ',
			array_merge(
				[$this->getRealClass(get_class($entity))],
				$identifier
			)
		);
	}

	/**
	 * @param string $class
	 *
	 * @return string
	 */
	private function getRealClass(string $class): string
	{
		$pos = strrpos($class, '\\' . Persistence\Proxy::MARKER . '\\');

		if ($pos === false) {
			return $class;
		}

		return substr($class, $pos + Persistence\Proxy::MARKER_LENGTH + 2);
	}

	/**
	 * @param object $entity
	 *
	 * @return bool
	 */
	private function validateNamespace(object $entity): bool
	{
		try {
			$rc = new ReflectionClass($entity);

		} catch (ReflectionException $ex) {
			return false;
		}

		return str_starts_with($rc->getNamespaceName(), 'FastyBird\TriggersModule');
	}

}
