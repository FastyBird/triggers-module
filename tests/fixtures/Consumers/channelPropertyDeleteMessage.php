<?php declare(strict_types = 1);

use FastyBird\ModulesMetadata;
use Nette\Utils;

return [
	'messageWithDeletedChannelProperty'     => [
		ModulesMetadata\Constants::MESSAGE_BUS_CHANNELS_PROPERTY_DELETED_ENTITY_ROUTING_KEY,
		Utils\ArrayHash::from([
			'id'         => 'fe2badf6-2e85-4ef6-9009-fe247d473069',
			'key'        => '9wWQ0Q',
			'identifier' => 'property-identifier',
			'name'       => 'button',
			'owner'      => '89ce7161-12dd-427e-9a35-92bc4390d98d',
		]),
		1,
		[
			'fb.bus.entity.deleted.trigger.action' => [
				'id'       => '0dac7180-dfe1-4079-ba91-fec6eeccccdf',
				'enabled'  => true,
				'trigger'  => '402aabb9-b5a8-4f28-aad4-c7ec245831b2',
				'owner'    => null,
				'type'     => 'channel-property',
				'device'   => 'cB8F0Q',
				'channel'  => 'zB8F0Q',
				'property' => '9wWQ0Q',
				'value'    => 'toggle',
			],
		],
	],
	'messageWithDeletedChannelProperty_two' => [
		ModulesMetadata\Constants::MESSAGE_BUS_CHANNELS_PROPERTY_DELETED_ENTITY_ROUTING_KEY,
		Utils\ArrayHash::from([
			'id'         => 'fe2badf6-2e85-4ef6-9009-fe247d473069',
			'key'        => 'lxWQ0Q',
			'identifier' => 'property-identifier',
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
				'device'   => 'cB8F0Q',
				'channel'  => '9B8F0Q',
				'property' => 'lxWQ0Q',
				'value'    => 'on',
			],
		],
	],
];
