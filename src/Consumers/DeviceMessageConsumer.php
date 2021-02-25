<?php declare(strict_types = 1);

/**
 * DeviceMessageConsumer.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     Consumers
 * @since          0.1.0
 *
 * @date           05.04.20
 */

namespace FastyBird\TriggersModule\Consumers;

use FastyBird\ApplicationExchange\Consumer as ApplicationExchangeConsumer;
use FastyBird\ModulesMetadata;
use FastyBird\TriggersModule\Entities;
use FastyBird\TriggersModule\Models;
use FastyBird\TriggersModule\Queries;
use Nette;
use Nette\Utils;
use Psr\Log;

/**
 * Device command messages consumer
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Consumers
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
final class DeviceMessageConsumer implements ApplicationExchangeConsumer\IConsumer
{

	use Nette\SmartObject;

	private const ROUTING_KEYS = [
		ModulesMetadata\Constants::MESSAGE_BUS_DEVICES_DELETED_ENTITY_ROUTING_KEY,
	];

	/** @var Models\Triggers\ITriggerRepository */
	private Models\Triggers\ITriggerRepository $triggerRepository;

	/** @var Models\Triggers\ITriggersManager */
	private Models\Triggers\ITriggersManager $triggersManager;

	/** @var Models\Actions\IActionRepository */
	private Models\Actions\IActionRepository $actionRepository;

	/** @var Models\Actions\IActionsManager */
	private Models\Actions\IActionsManager $actionsManager;

	/** @var Models\Conditions\IConditionRepository */
	private Models\Conditions\IConditionRepository $conditionRepository;

	/** @var Models\Conditions\IConditionsManager */
	private Models\Conditions\IConditionsManager $conditionsManager;

	/** @var Log\LoggerInterface */
	private Log\LoggerInterface $logger;

	public function __construct(
		Models\Triggers\ITriggerRepository $triggerRepository,
		Models\Triggers\ITriggersManager $triggersManager,
		Models\Actions\IActionRepository $actionRepository,
		Models\Actions\IActionsManager $actionsManager,
		Models\Conditions\IConditionRepository $conditionRepository,
		Models\Conditions\IConditionsManager $conditionsManager,
		?Log\LoggerInterface $logger
	) {
		$this->triggerRepository = $triggerRepository;
		$this->triggersManager = $triggersManager;
		$this->actionRepository = $actionRepository;
		$this->actionsManager = $actionsManager;
		$this->conditionRepository = $conditionRepository;
		$this->conditionsManager = $conditionsManager;

		$this->logger = $logger ?? new Log\NullLogger();
	}

	/**
	 * {@inheritDoc}
	 */
	public function consume(
		string $origin,
		string $routingKey,
		Utils\ArrayHash $message
	): void {
		if (!in_array($routingKey, self::ROUTING_KEYS, true)) {
			return;
		}

		if ($routingKey === ModulesMetadata\Constants::MESSAGE_BUS_DEVICES_DELETED_ENTITY_ROUTING_KEY) {
			$this->clearDevices(
				$message->offsetGet('key')
			);
		}
	}

	/**
	 * @param string $device
	 *
	 * @return void
	 */
	private function clearDevices(string $device): void
	{
		$findQuery = new Queries\FindActionsQuery();
		$findQuery->forDevice($device);

		$actions = $this->actionRepository->findAllBy($findQuery, Entities\Actions\ChannelPropertyAction::class);

		foreach ($actions as $action) {
			$this->actionsManager->delete($action);
		}

		$findQuery = new Queries\FindConditionsQuery();
		$findQuery->forDevice($device);

		$conditions = $this->conditionRepository->findAllBy($findQuery, Entities\Conditions\DevicePropertyCondition::class);

		foreach ($conditions as $condition) {
			$this->conditionsManager->delete($condition);
		}

		$findQuery = new Queries\FindConditionsQuery();
		$findQuery->forDevice($device);

		$conditions = $this->conditionRepository->findAllBy($findQuery, Entities\Conditions\ChannelPropertyCondition::class);

		foreach ($conditions as $condition) {
			$this->conditionsManager->delete($condition);
		}

		$this->logger->info('[FB:TRIGGERS_MODULE:CONSUMER] Successfully consumed device entity message');
	}

}
