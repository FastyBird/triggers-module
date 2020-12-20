<?php declare(strict_types = 1);

use FastyBird\ModulesMetadata;
use Nette\Utils;

return [
	'messageWithDeletedChannelProperty'     => [
		ModulesMetadata\Constants::MESSAGE_BUS_CHANNELS_PROPERTY_DELETED_ENTITY_ROUTING_KEY,
		Utils\ArrayHash::from([
			'id'         => 'fe2badf6-2e85-4ef6-9009-fe247d473069',
			'identifier' => 'device-one',
			'device'     => 'device-one',
			'channel'    => 'channel-one',
			'property'   => 'button',
			'name'       => 'button',
			'owner'      => '89ce7161-12dd-427e-9a35-92bc4390d98d',
		]),
		2,
		[
			'fb.bus.entity.deleted.trigger'        => [
				'id'       => '1c580923-28dd-4b28-8517-bf37f0173b93',
				'name'     => '28bc0d38-2f7c-4a71-aa74-27b102f8df4c',
				'comment'  => '996213a4-d959-4f6c-b77c-f248ce8f8d84',
				'enabled'  => true,
				'owner'    => null,
				'type'     => 'channel-property',
				'device'   => 'device-one',
				'channel'  => 'channel-one',
				'property' => 'button',
				'operand'  => '3',
				'operator' => 'eq',
			],
			'fb.bus.entity.deleted.trigger.action' => [
				'id'       => '5c47a7c0-99d5-4dfa-b289-edb8afe4d198',
				'enabled'  => true,
				'trigger'  => '1c580923-28dd-4b28-8517-bf37f0173b93',
				'owner'    => null,
				'type'     => 'channel-property',
				'device'   => 'device-two',
				'channel'  => 'channel-one',
				'property' => 'switch',
				'value'    => 'toggle',
			],
		],
		1,
		0,
		1,
		1,
	],
	'messageWithDeletedChannelProperty_two' => [
		ModulesMetadata\Constants::MESSAGE_BUS_CHANNELS_PROPERTY_DELETED_ENTITY_ROUTING_KEY,
		Utils\ArrayHash::from([
			'id'         => 'fe2badf6-2e85-4ef6-9009-fe247d473069',
			'identifier' => 'device-one',
			'device'     => 'device-one',
			'channel'    => 'channel-four',
			'property'   => 'switch',
			'name'       => 'switch',
			'owner'      => '89ce7161-12dd-427e-9a35-92bc4390d98d',
		]),
		1,
		[
			'fb.bus.entity.deleted.trigger.action' => [
				'id'       => '4aa84028-d8b7-4128-95b2-295763634aa4',
				'enabled'  => true,
				'trigger'  => 'c64ba1c4-0eda-4cab-87a0-4d634f7b67f4',
				'owner'    => null,
				'type'     => 'channel-property',
				'device'   => 'device-one',
				'channel'  => 'channel-four',
				'property' => 'switch',
				'value'    => 'on',
			],
		],
		1,
		1,
		1,
		0,
	],
];
