<?php declare(strict_types = 1);

/**
 * TPropertyDataMessageConsumer.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     Consumers
 * @since          0.1.0
 *
 * @date           07.04.20
 */

namespace FastyBird\TriggersModule\Consumers;

use FastyBird\ApplicationExchange\Publisher as ApplicationExchangePublisher;
use FastyBird\ModulesMetadata;
use FastyBird\ModulesMetadata\Types as ModulesMetadataTypes;
use FastyBird\TriggersModule\Entities;
use Psr\Log;

/**
 * Device or channel property data command messages trait
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Consumers
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 *
 * @property-read ApplicationExchangePublisher\IPublisher $publisher
 * @property-read Log\LoggerInterface $logger
 */
trait TPropertyDataMessageConsumer
{

	/**
	 * @param Entities\Conditions\ICondition $condition
	 *
	 * @return void
	 */
	protected function processCondition(
		Entities\Conditions\ICondition $condition
	): void {
		$trigger = $condition->getTrigger();

		if (count($trigger->getActions()) === 0) {
			return;
		}

		foreach ($trigger->getConditions() as $triggerCondition) {
			if (!$triggerCondition->getId()->equals($condition->getId())) {
				// Check if all conditions are passed

				if ($triggerCondition instanceof Entities\Conditions\IChannelPropertyCondition) {
					$value = $this->fetchChannelPropertyValue(
						$triggerCondition->getDevice(),
						$triggerCondition->getChannel(),
						$triggerCondition->getProperty()
					);

					if ($value !== $triggerCondition->getOperand()) {
						$this->logger->info('[FB:TRIGGERS_MODULE:CONSUMER] Trigger do not met all conditions, skipping');

						return;
					}

				} elseif ($triggerCondition instanceof Entities\Conditions\IDevicePropertyCondition) {
					$value = $this->fetchDevicePropertyValue(
						$triggerCondition->getDevice(),
						$triggerCondition->getProperty()
					);

					if ($value !== $triggerCondition->getOperand()) {
						$this->logger->info('[FB:TRIGGERS_MODULE:CONSUMER] Trigger do not met all conditions, skipping');

						return;
					}
				}
			}
		}

		$this->processTrigger($trigger);
	}

	/**
	 * @param string $device
	 * @param string $channel
	 * @param string $property
	 *
	 * @return mixed|null
	 */
	private function fetchChannelPropertyValue(
		string $device,
		string $channel,
		string $property
	) {
		// TODO: fetch stored value from store
		return null;
	}

	/**
	 * @param string $device
	 * @param string $property
	 *
	 * @return mixed|null
	 */
	private function fetchDevicePropertyValue(
		string $device,
		string $property
	) {
		// TODO: fetch stored value from store
		return null;
	}

	/**
	 * @param Entities\Triggers\ITrigger $trigger
	 *
	 * @return void
	 */
	private function processTrigger(
		Entities\Triggers\ITrigger $trigger
	): void {
		foreach ($trigger->getActions() as $action) {
			if ($action instanceof Entities\Actions\ChannelPropertyAction) {
				$this->publisher->publish(
					ModulesMetadata\Constants::MODULE_TRIGGERS_ORIGIN,
					ModulesMetadata\Constants::MESSAGE_BUS_CHANNELS_PROPERTIES_DATA_ROUTING_KEY,
					[
						'device'   => $action->getDevice(),
						'channel'  => $action->getChannel(),
						'property' => $action->getProperty(),
						'expected' => $action->getValue(),
					]
				);

				$this->logger->info('[FB:TRIGGERS_MODULE:CONSUMER] Trigger fired command');
			}
		}
	}

	/**
	 * @param mixed $value
	 * @param ModulesMetadataTypes\DataTypeType|null $dataType
	 *
	 * @return int|float|string|bool|null
	 */
	protected function formatValue($value, ?ModulesMetadataTypes\DataTypeType $dataType)
	{
		if ($dataType === null) {
			return $value;
		}

		if ($dataType->isInteger()) {
			return (int) $value;

		} elseif ($dataType->equalsValue(ModulesMetadataTypes\DataTypeType::DATA_TYPE_FLOAT)) {
			return (float) $value;

		} elseif ($dataType->equalsValue(ModulesMetadataTypes\DataTypeType::DATA_TYPE_BOOLEAN)) {
			return (bool) $value;
		}

		return $value;
	}

}
