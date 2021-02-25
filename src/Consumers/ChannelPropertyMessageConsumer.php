<?php declare(strict_types = 1);

/**
 * ChannelPropertyMessageConsumer.php
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
use FastyBird\ApplicationExchange\Publisher as ApplicationExchangePublisher;
use FastyBird\ModulesMetadata;
use FastyBird\TriggersModule\Entities;
use FastyBird\TriggersModule\Models;
use FastyBird\TriggersModule\Queries;
use FastyBird\TriggersModule\Types;
use Nette;
use Nette\Utils;
use Psr\Log;

/**
 * Channel property command messages consumer
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Consumers
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
final class ChannelPropertyMessageConsumer implements ApplicationExchangeConsumer\IConsumer
{

	use Nette\SmartObject;
	use TPropertyDataMessageConsumer;

	private const ROUTING_KEYS = [
		ModulesMetadata\Constants::MESSAGE_BUS_CHANNELS_PROPERTY_UPDATED_ENTITY_ROUTING_KEY,
		ModulesMetadata\Constants::MESSAGE_BUS_CHANNELS_PROPERTY_DELETED_ENTITY_ROUTING_KEY,
	];

	/** @var ApplicationExchangePublisher\IPublisher */
	protected ApplicationExchangePublisher\IPublisher $publisher;

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
	protected Log\LoggerInterface $logger;

	public function __construct(
		Models\Triggers\ITriggerRepository $triggerRepository,
		Models\Triggers\ITriggersManager $triggersManager,
		Models\Actions\IActionRepository $actionRepository,
		Models\Actions\IActionsManager $actionsManager,
		Models\Conditions\IConditionRepository $conditionRepository,
		Models\Conditions\IConditionsManager $conditionsManager,
		ApplicationExchangePublisher\IPublisher $publisher,
		?Log\LoggerInterface $logger
	) {
		$this->triggerRepository = $triggerRepository;
		$this->triggersManager = $triggersManager;
		$this->actionRepository = $actionRepository;
		$this->actionsManager = $actionsManager;
		$this->conditionRepository = $conditionRepository;
		$this->conditionsManager = $conditionsManager;

		$this->publisher = $publisher;
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

		if ($routingKey === ModulesMetadata\Constants::MESSAGE_BUS_CHANNELS_PROPERTY_DELETED_ENTITY_ROUTING_KEY) {
			$this->clearProperties(
				$message->offsetGet('key')
			);

		} elseif ($routingKey === ModulesMetadata\Constants::MESSAGE_BUS_CHANNELS_PROPERTY_UPDATED_ENTITY_ROUTING_KEY) {
			// Only not pending messages will be processed
			if (
				$message->offsetExists('pending')
				&& $message->offsetGet('pending') === false
				&& $message->offsetExists('value')
			) {
				$this->processChannelConditions(
					$message->offsetGet('key'),
					$message->offsetGet('value'),
					$message->offsetExists('previous_value') ? $message->offsetGet('previous_value') : null,
					$message->offsetGet('data_type')
				);
			}
		}
	}

	/**
	 * @param string $property
	 *
	 * @return void
	 */
	private function clearProperties(string $property): void
	{
		$findQuery = new Queries\FindActionsQuery();
		$findQuery->forChannelProperty($property);

		$actions = $this->actionRepository->findAllBy($findQuery, Entities\Actions\ChannelPropertyAction::class);

		foreach ($actions as $action) {
			$this->actionsManager->delete($action);
		}

		$findQuery = new Queries\FindConditionsQuery();
		$findQuery->forProperty($property);

		$conditions = $this->conditionRepository->findAllBy($findQuery, Entities\Conditions\ChannelPropertyCondition::class);

		foreach ($conditions as $condition) {
			$this->conditionsManager->delete($condition);
		}

		$this->logger->info('[FB:TRIGGERS_MODULE:CONSUMER] Successfully consumed channel property data message');
	}

	/**
	 * @param string $property
	 * @param mixed $value
	 * @param mixed|null $previousValue
	 * @param string|null $dataType
	 *
	 * @return void
	 */
	private function processChannelConditions(
		string $property,
		$value,
		$previousValue = null,
		?string $dataType = null
	): void {
		$value = $this->formatValue($value, $dataType);
		$previousValue = $this->formatValue($previousValue, $dataType);

		// Previous value is same as current, skipping
		if ($previousValue !== null && (string) $value === (string) $previousValue) {
			return;
		}

		$findQuery = new Queries\FindConditionsQuery();
		$findQuery->forProperty($property);

		$conditions = $this->conditionRepository->findAllBy($findQuery, Entities\Conditions\ChannelPropertyCondition::class);

		/** @var Entities\Conditions\ChannelPropertyCondition $condition */
		foreach ($conditions as $condition) {
			if (
				$condition->getOperator()->equalsValue(Types\ConditionOperatorType::OPERATOR_VALUE_EQUAL)
				&& $condition->getOperand() === (string) $value
			) {
				$this->processCondition($condition);
			}
		}
	}

}
