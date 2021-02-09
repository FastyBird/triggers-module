<?php declare(strict_types = 1);

use FastyBird\ModulesMetadata;
use Nette\Utils;

return [
	'messageWithDeletedChannel' => [
		ModulesMetadata\Constants::MESSAGE_BUS_CHANNELS_DELETED_ENTITY_ROUTING_KEY,
		Utils\ArrayHash::from([
			'id'         => 'fe2badf6-2e85-4ef6-9009-fe247d473069',
			'key'        => 'zB8F0Q',
			'identifier' => 'channel-identifier',
			'name'       => 'Channel one',
			'owner'      => '89ce7161-12dd-427e-9a35-92bc4390d98d',
		]),
		4,
		[
			'fb.bus.entity.deleted.trigger'        => [
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
			'fb.bus.entity.deleted.trigger.action' => [
				'primaryKey'                           => 'id',
				'5c47a7c0-99d5-4dfa-b289-edb8afe4d198' => [
					'id' => '5c47a7c0-99d5-4dfa-b289-edb8afe4d198',
					'enabled' => true,
					'trigger' => '1c580923-28dd-4b28-8517-bf37f0173b93',
					'owner' => null,
					'type' => 'channel-property',
					'device' => 'GB8F0Q',
					'channel' => '2B8F0Q',
					'property' => 'h1WQ0Q',
					'value' => 'toggle',
				],
				'0dac7180-dfe1-4079-ba91-fec6eeccccdf' => [
					'id' => '0dac7180-dfe1-4079-ba91-fec6eeccccdf',
					'enabled' => true,
					'trigger' => '402aabb9-b5a8-4f28-aad4-c7ec245831b2',
					'owner' => null,
					'type' => 'channel-property',
					'device' => 'cB8F0Q',
					'channel' => 'zB8F0Q',
					'property' => '9wWQ0Q',
					'value' => 'toggle',
				],
				'52aa8a35-1832-4317-be2c-8b8fffaae07f' => [
					'id' => '52aa8a35-1832-4317-be2c-8b8fffaae07f',
					'enabled' => true,
					'trigger' => 'b8bb82f3-31e2-406a-96ed-f99ebaf9947a',
					'owner' => null,
					'type' => 'channel-property',
					'device' => 'cB8F0Q',
					'channel' => 'zB8F0Q',
					'property' => 'J0WQ0Q',
					'value' => 'on',
				],
			],
		],
	],
];
