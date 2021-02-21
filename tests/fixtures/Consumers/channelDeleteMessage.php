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
		1,
		[
			'fb.bus.entity.deleted.trigger.action' => [
				'primaryKey'                           => 'id',
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
