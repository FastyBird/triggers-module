<?php declare(strict_types = 1);

use FastyBird\ModulesMetadata;
use Nette\Utils;

return [
	'messageWithDeletedChannel' => [
		ModulesMetadata\Constants::MESSAGE_BUS_DEVICES_DELETED_ENTITY_ROUTING_KEY,
		Utils\ArrayHash::from([
			'id'         => 'fe2badf6-2e85-4ef6-9009-fe247d473069',
			'key'        => 'cB8F0Q',
			'identifier' => 'device-identifier',
			'state'      => 'disconnected',
			'name'       => 'Device one',
			'owner'      => '89ce7161-12dd-427e-9a35-92bc4390d98d',
		]),
		4,
		[
			'fb.bus.entity.deleted.trigger.action' => [
				'primaryKey'                           => 'id',
				'21d13f14-8be0-4625-8764-4d5b1f3b4d1e' => [
					'id'       => '21d13f14-8be0-4625-8764-4d5b1f3b4d1e',
					'enabled'  => true,
					'trigger'  => '0b48dfbc-fac2-4292-88dc-7981a121602d',
					'owner'    => null,
					'type'     => 'channel-property',
					'device'   => 'cB8F0Q',
					'channel'  => '1B8F0Q',
					'property' => 'MwWQ0Q',
					'value'    => 'on',
				],
				'46c39a95-39eb-4216-9fa3-4d575a6295bd' => [
					'id'       => '46c39a95-39eb-4216-9fa3-4d575a6295bd',
					'enabled'  => true,
					'trigger'  => '421ca8e9-26c6-4630-89ba-c53aea9bcb1e',
					'owner'    => null,
					'type'     => 'channel-property',
					'device'   => 'cB8F0Q',
					'channel'  => '4B8F0Q',
					'property' => 'YwWQ0Q',
					'value'    => 'on',
				],
				'4aa84028-d8b7-4128-95b2-295763634aa4' => [
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
				'52aa8a35-1832-4317-be2c-8b8fffaae07f' => [
					'id'       => '52aa8a35-1832-4317-be2c-8b8fffaae07f',
					'enabled'  => true,
					'trigger'  => 'b8bb82f3-31e2-406a-96ed-f99ebaf9947a',
					'owner'    => null,
					'type'     => 'channel-property',
					'device'   => 'cB8F0Q',
					'channel'  => 'zB8F0Q',
					'property' => 'J0WQ0Q',
					'value'    => 'on',
				],
			],
		],
	],
];
