<?php declare(strict_types = 1);

/**
 * Constants.php
 *
 * @license        More in LICENSE.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     common
 * @since          0.1.0
 *
 * @date           05.04.20
 */

namespace FastyBird\TriggersModule;

use FastyBird\ModulesMetadata;
use FastyBird\TriggersModule\Entities as TriggersModuleEntities;

/**
 * Service constants
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     common
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
final class Constants
{

	/**
	 * Module routing
	 */

	public const ROUTE_NAME_TRIGGERS = 'triggers';
	public const ROUTE_NAME_TRIGGER = 'trigger';
	public const ROUTE_NAME_TRIGGER_RELATIONSHIP = 'trigger.relationship';
	public const ROUTE_NAME_TRIGGER_ACTIONS = 'trigger.actions';
	public const ROUTE_NAME_TRIGGER_ACTION = 'trigger.action';
	public const ROUTE_NAME_TRIGGER_ACTION_RELATIONSHIP = 'trigger.action.relationship';
	public const ROUTE_NAME_TRIGGER_CONDITIONS = 'trigger.conditions';
	public const ROUTE_NAME_TRIGGER_CONDITION = 'trigger.condition';
	public const ROUTE_NAME_TRIGGER_CONDITION_RELATIONSHIP = 'trigger.condition.relationship';
	public const ROUTE_NAME_TRIGGER_NOTIFICATIONS = 'trigger.notifications';
	public const ROUTE_NAME_TRIGGER_NOTIFICATION = 'trigger.notification';
	public const ROUTE_NAME_TRIGGER_NOTIFICATION_RELATIONSHIP = 'trigger.v.relationship';

	/**
	 * Message bus routing keys mapping
	 */
	public const MESSAGE_BUS_CREATED_ENTITIES_ROUTING_KEYS_MAPPING = [
		TriggersModuleEntities\Triggers\Trigger::class           => ModulesMetadata\Constants::MESSAGE_BUS_TRIGGERS_CREATED_ENTITY_ROUTING_KEY,
		TriggersModuleEntities\Actions\Action::class             => ModulesMetadata\Constants::MESSAGE_BUS_TRIGGERS_ACTIONS_CREATED_ENTITY_ROUTING_KEY,
		TriggersModuleEntities\Notifications\Notification::class => ModulesMetadata\Constants::MESSAGE_BUS_TRIGGERS_NOTIFICATIONS_CREATED_ENTITY_ROUTING_KEY,
		TriggersModuleEntities\Conditions\Condition::class       => ModulesMetadata\Constants::MESSAGE_BUS_TRIGGERS_CONDITIONS_CREATED_ENTITY_ROUTING_KEY,
	];

	public const MESSAGE_BUS_UPDATED_ENTITIES_ROUTING_KEYS_MAPPING = [
		TriggersModuleEntities\Triggers\Trigger::class           => ModulesMetadata\Constants::MESSAGE_BUS_TRIGGERS_UPDATED_ENTITY_ROUTING_KEY,
		TriggersModuleEntities\Actions\Action::class             => ModulesMetadata\Constants::MESSAGE_BUS_TRIGGERS_ACTIONS_UPDATED_ENTITY_ROUTING_KEY,
		TriggersModuleEntities\Notifications\Notification::class => ModulesMetadata\Constants::MESSAGE_BUS_TRIGGERS_NOTIFICATIONS_UPDATED_ENTITY_ROUTING_KEY,
		TriggersModuleEntities\Conditions\Condition::class       => ModulesMetadata\Constants::MESSAGE_BUS_TRIGGERS_CONDITIONS_UPDATED_ENTITY_ROUTING_KEY,
	];

	public const MESSAGE_BUS_DELETED_ENTITIES_ROUTING_KEYS_MAPPING = [
		TriggersModuleEntities\Triggers\Trigger::class           => ModulesMetadata\Constants::MESSAGE_BUS_TRIGGERS_DELETED_ENTITY_ROUTING_KEY,
		TriggersModuleEntities\Actions\Action::class             => ModulesMetadata\Constants::MESSAGE_BUS_TRIGGERS_ACTIONS_DELETED_ENTITY_ROUTING_KEY,
		TriggersModuleEntities\Notifications\Notification::class => ModulesMetadata\Constants::MESSAGE_BUS_TRIGGERS_NOTIFICATIONS_DELETED_ENTITY_ROUTING_KEY,
		TriggersModuleEntities\Conditions\Condition::class       => ModulesMetadata\Constants::MESSAGE_BUS_TRIGGERS_CONDITIONS_DELETED_ENTITY_ROUTING_KEY,
	];

}
