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
		9,
		[
			'fb.bus.entity.deleted.trigger'        => [
				'primaryKey'                           => 'id',
				'402aabb9-b5a8-4f28-aad4-c7ec245831b2' => [
					'id'       => '402aabb9-b5a8-4f28-aad4-c7ec245831b2',
					'name'     => '7c055b2b-60c3-4017-93db-e9478d8aa662',
					'comment'  => 'ade714f4-ca9b-40bc-8022-dcb3a4f1b705',
					'enabled'  => true,
					'owner'    => null,
					'type'     => 'channel-property',
					'device'   => 'cB8F0Q',
					'channel'  => '1B8F0Q',
					'property' => 'w7pT0Q',
					'operand'  => '1',
					'operator' => 'eq',
				],
				'1c580923-28dd-4b28-8517-bf37f0173b93' => [
					'id'       => '1c580923-28dd-4b28-8517-bf37f0173b93',
					'name'     => '28bc0d38-2f7c-4a71-aa74-27b102f8df4c',
					'comment'  => '996213a4-d959-4f6c-b77c-f248ce8f8d84',
					'enabled'  => true,
					'owner'    => null,
					'type'     => 'channel-property',
					'device'   => 'cB8F0Q',
					'channel'  => 'zB8F0Q',
					'property' => 'k7pT0Q',
					'operand'  => '3',
					'operator' => 'eq',
				],
			],
			'fb.bus.entity.deleted.trigger.action' => [
				'primaryKey'                           => 'id',
				'5a38d726-630f-4e36-862a-8056f6b99aff' => [
					'id'       => '5a38d726-630f-4e36-862a-8056f6b99aff',
					'enabled'  => true,
					'trigger'  => '917402ff-3f82-451c-892c-38f08175856a',
					'owner'    => null,
					'type'     => 'channel-property',
					'device'   => 'cB8F0Q',
					'channel'  => 'DB8F0Q',
					'property' => 'V0WQ0Q',
					'value'    => 'toggle',
				],
				'5c47a7c0-99d5-4dfa-b289-edb8afe4d198' => [
					'id'       => '5c47a7c0-99d5-4dfa-b289-edb8afe4d198',
					'enabled'  => true,
					'trigger'  => '1c580923-28dd-4b28-8517-bf37f0173b93',
					'owner'    => null,
					'type'     => 'channel-property',
					'device'   => 'GB8F0Q',
					'channel'  => '2B8F0Q',
					'property' => 'h1WQ0Q',
					'value'    => 'toggle',
				],
				'0dac7180-dfe1-4079-ba91-fec6eeccccdf' => [
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
