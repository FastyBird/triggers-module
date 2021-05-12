<?php declare(strict_types = 1);

/**
 * DevicePropertyMessageConsumer.php
 *
 * @license        More in LICENSE.md
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
use FastyBird\ModulesMetadata\Types as ModulesMetadataTypes;
use FastyBird\TriggersModule\Entities;
use FastyBird\TriggersModule\Models;
use FastyBird\TriggersModule\Queries;
use Nette;
use Nette\Utils;
use Psr\Log;

/**
 * Device property command messages consumer
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Consumers
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
final class DevicePropertyMessageConsumer implements ApplicationExchangeConsumer\IConsumer
{

	use Nette\SmartObject;
	use TPropertyDataMessageConsumer;

	private const ROUTING_KEYS = [
		ModulesMetadata\Constants::MESSAGE_BUS_DEVICES_PROPERTY_UPDATED_ENTITY_ROUTING_KEY,
		ModulesMetadata\Constants::MESSAGE_BUS_DEVICES_PROPERTY_DELETED_ENTITY_ROUTING_KEY,
	];

	/** @var ApplicationExchangePublisher\IPublisher */
	protected ApplicationExchangePublisher\IPublisher $publisher;

	/** @var Models\Conditions\IConditionRepository */
	private Models\Conditions\IConditionRepository $conditionRepository;

	/** @var Models\Conditions\IConditionsManager */
	private Models\Conditions\IConditionsManager $conditionsManager;

	/** @var Log\LoggerInterface */
	protected Log\LoggerInterface $logger;

	public function __construct(
		Models\Conditions\IConditionRepository $conditionRepository,
		Models\Conditions\IConditionsManager $conditionsManager,
		ApplicationExchangePublisher\IPublisher $publisher,
		?Log\LoggerInterface $logger
	) {
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

		if ($routingKey === ModulesMetadata\Constants::MESSAGE_BUS_DEVICES_PROPERTY_DELETED_ENTITY_ROUTING_KEY) {
			$this->clearProperties(
				$message->offsetGet('key')
			);

		} elseif ($routingKey === ModulesMetadata\Constants::MESSAGE_BUS_DEVICES_PROPERTY_UPDATED_ENTITY_ROUTING_KEY) {
			// Only not pending messages will be processed
			if (
				$message->offsetExists('pending')
				&& $message->offsetGet('pending') === false
				&& $message->offsetExists('value')
			) {
				$this->processDeviceConditions(
					$message->offsetGet('key'),
					$message->offsetGet('value'),
					$message->offsetExists('previous_value') ? $message->offsetGet('previous_value') : null,
					ModulesMetadataTypes\DataTypeType::isValidValue($message->offsetGet('data_type')) ? ModulesMetadataTypes\DataTypeType::get($message->offsetGet('data_type')) : null
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
		$findQuery = new Queries\FindConditionsQuery();
		$findQuery->forProperty($property);

		$conditions = $this->conditionRepository->findAllBy($findQuery, Entities\Conditions\DevicePropertyCondition::class);

		foreach ($conditions as $condition) {
			$this->conditionsManager->delete($condition);
		}

		$this->logger->info('[FB:TRIGGERS_MODULE:CONSUMER] Successfully consumed device property data message');
	}

	/**
	 * @param string $property
	 * @param mixed $value
	 * @param mixed|null $previousValue
	 * @param ModulesMetadataTypes\DataTypeType|null $dataType
	 *
	 * @return void
	 */
	private function processDeviceConditions(
		string $property,
		$value,
		$previousValue = null,
		?ModulesMetadataTypes\DataTypeType $dataType = null
	): void {
		$value = $this->formatValue($value, $dataType);
		$previousValue = $this->formatValue($previousValue, $dataType);

		// Previous value is same as current, skipping
		if ($previousValue !== null && $value === $previousValue) {
			return;
		}

		$findQuery = new Queries\FindConditionsQuery();
		$findQuery->forProperty($property);

		$conditions = $this->conditionRepository->findAllBy($findQuery, Entities\Conditions\DevicePropertyCondition::class);

		/** @var Entities\Conditions\DevicePropertyCondition $condition */
		foreach ($conditions as $condition) {
			if (
				$condition->getOperator()->equalsValue(ModulesMetadataTypes\TriggerConditionOperatorType::OPERATOR_VALUE_EQUAL)
				&& $condition->getOperand() === $value
			) {
				$this->processCondition($condition);
			}
		}
	}

}
