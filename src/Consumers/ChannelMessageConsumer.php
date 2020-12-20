<?php declare(strict_types = 1);

/**
 * ChannelMessageConsumer.php
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
 * Channel command messages consumer
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Consumers
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
final class ChannelMessageConsumer implements ApplicationExchangeConsumer\IConsumer
{

	use Nette\SmartObject;

	private const ROUTING_KEYS = [
		ModulesMetadata\Constants::MESSAGE_BUS_CHANNELS_DELETED_ENTITY_ROUTING_KEY,
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
		string $routingKey,
		string $origin,
		Utils\ArrayHash $message
	): void {
		if (!in_array($routingKey, self::ROUTING_KEYS, true)) {
			return;
		}

		if ($routingKey === ModulesMetadata\Constants::MESSAGE_BUS_CHANNELS_DELETED_ENTITY_ROUTING_KEY) {
			$this->clearChannels(
				$message->offsetGet('device'),
				$message->offsetGet('channel')
			);
		}
	}

	/**
	 * @param string $device
	 * @param string $channel
	 *
	 * @return void
	 */
	private function clearChannels(string $device, string $channel): void
	{
		$findQuery = new Queries\FindChannelPropertyTriggersQuery();
		$findQuery->forChannel($device, $channel);

		$triggers = $this->triggerRepository->findAllBy($findQuery, Entities\Triggers\ChannelPropertyTrigger::class);

		foreach ($triggers as $trigger) {
			$this->triggersManager->delete($trigger);
		}

		$findQuery = new Queries\FindActionsQuery();
		$findQuery->forChannel($device, $channel);

		$actions = $this->actionRepository->findAllBy($findQuery, Entities\Actions\ChannelPropertyAction::class);

		foreach ($actions as $action) {
			$this->actionsManager->delete($action);
		}

		$findQuery = new Queries\FindConditionsQuery();
		$findQuery->forChannel($device, $channel);

		$conditions = $this->conditionRepository->findAllBy($findQuery, Entities\Conditions\ChannelPropertyCondition::class);

		foreach ($conditions as $condition) {
			$this->conditionsManager->delete($condition);
		}

		$this->logger->info('[CONSUMER] Successfully consumed channel entity message');
	}

}
