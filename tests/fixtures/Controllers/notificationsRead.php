<?php declare(strict_types = 1);

use Fig\Http\Message\StatusCodeInterface;

const VALID_TOKEN = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJjb20uZmFzdHliaXJkLmF1dGgtbW9kdWxlIiwianRpIjoiMjQ3MTBlOTYtYTZmYi00ZmM3LWFhMzAtNDcyNzkwNWQzMDRjIiwiaWF0IjoxNTg1NzQyNDAwLCJleHAiOjE1ODU3NDk2MDAsInVzZXIiOiI1ZTc5ZWZiZi1iZDBkLTViN2MtNDZlZi1iZmJkZWZiZmJkMzQiLCJyb2xlcyI6WyJhZG1pbmlzdHJhdG9yIl19.QH_Oo_uzTXAb3pNnHvXYnnX447nfVq2_ggQ9ZxStu4s';
const EXPIRED_TOKEN = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJjb20uZmFzdHliaXJkLmF1dGgtbW9kdWxlIiwianRpIjoiMjM5Nzk0NzAtYmVmNi00ZjE2LTlkNzUtNmFhMWZiYWVjNWRiIiwiaWF0IjoxNTc3ODgwMDAwLCJleHAiOjE1Nzc4ODcyMDAsInVzZXIiOiI1ZTc5ZWZiZi1iZDBkLTViN2MtNDZlZi1iZmJkZWZiZmJkMzQiLCJyb2xlcyI6WyJhZG1pbmlzdHJhdG9yIl19.2k8-_-dsPVQeYnb6OunzDp9fJmiQ2JLQo8GwtjgpBXg';
const INVALID_TOKEN = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJjb20uZmFzdHliaXJkLmF1dGgtbW9kdWxlIiwianRpIjoiODkyNTcxOTQtNWUyMi00NWZjLThhMzEtM2JhNzI5OWM5OTExIiwiaWF0IjoxNTg1NzQyNDAwLCJleHAiOjE1ODU3NDk2MDAsInVzZXIiOiI1ZTc5ZWZiZi1iZDBkLTViN2MtNDZlZi1iZmJkZWZiZmJkMzQiLCJyb2xlcyI6WyJhZG1pbmlzdHJhdG9yIl19.z8hS0hUVtGkiHBeUTdKC_CMqhMIa4uXotPuJJ6Js6S4';
const VALID_TOKEN_USER = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJjb20uZmFzdHliaXJkLmF1dGgtbW9kdWxlIiwianRpIjoiOGIxN2I5ZjMtNWNkMi00OTU0LWJhM2ItNThlZTRiZTUzMjdkIiwiaWF0IjoxNTg1NzQyNDAwLCJleHAiOjE1ODU3NDk2MDAsInVzZXIiOiI1ZTc5ZWZiZi1iZDBkLTViN2MtNDZlZi1iZmJkZWZiZmJkMzQiLCJyb2xlcyI6WyJ1c2VyIl19.jELVcZGRa5_-Jcpoo3Jfho08vQT2IobtoEQPhxN2tzw';

return [
	// Valid responses
	//////////////////
	'readAll'                              => [
		'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/notifications',
		'Bearer ' . VALID_TOKEN,
		StatusCodeInterface::STATUS_OK,
		__DIR__ . '/responses/notifications.index.json',
	],
	'readAllPaging'                        => [
		'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/notifications?page[offset]=1&page[limit]=1',
		'Bearer ' . VALID_TOKEN,
		StatusCodeInterface::STATUS_OK,
		__DIR__ . '/responses/notifications.index.paging.json',
	],
	'readOne'                              => [
		'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/notifications/05f28df9-5f19-4923-b3f8-b9090116dadc',
		'Bearer ' . VALID_TOKEN,
		StatusCodeInterface::STATUS_OK,
		__DIR__ . '/responses/notifications.read.json',
	],
	'readOneInclude'                       => [
		'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/notifications/05f28df9-5f19-4923-b3f8-b9090116dadc?include=trigger',
		'Bearer ' . VALID_TOKEN,
		StatusCodeInterface::STATUS_OK,
		__DIR__ . '/responses/notifications.read.include.json',
	],
	'readRelationshipsTrigger'             => [
		'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/notifications/05f28df9-5f19-4923-b3f8-b9090116dadc/relationships/trigger',
		'Bearer ' . VALID_TOKEN,
		StatusCodeInterface::STATUS_OK,
		__DIR__ . '/responses/notifications.readRelationships.trigger.json',
	],
	'readAllUser'                          => [
		'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/notifications',
		'Bearer ' . VALID_TOKEN_USER,
		StatusCodeInterface::STATUS_OK,
		__DIR__ . '/responses/notifications.index.json',
	],
	'readOneUser'                          => [
		'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/notifications/05f28df9-5f19-4923-b3f8-b9090116dadc',
		'Bearer ' . VALID_TOKEN_USER,
		StatusCodeInterface::STATUS_OK,
		__DIR__ . '/responses/notifications.read.json',
	],

	// Invalid responses
	////////////////////
	'readOneUnknown'                       => [
		'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/notifications/74e40f3e-84cb-4e0c-b3b3-fbf8246e0888',
		'Bearer ' . VALID_TOKEN,
		StatusCodeInterface::STATUS_NOT_FOUND,
		__DIR__ . '/responses/generic/notFound.json',
	],
	'readOneUnknownTrigger'                => [
		'/v1/triggers/74e40f3e-84cb-4e0c-b3b3-fbf8246e0888/notifications/05f28df9-5f19-4923-b3f8-b9090116dadc',
		'Bearer ' . VALID_TOKEN,
		StatusCodeInterface::STATUS_NOT_FOUND,
		__DIR__ . '/responses/generic/notFound.json',
	],
	'readRelationshipsUnknown'             => [
		'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/notifications/05f28df9-5f19-4923-b3f8-b9090116dadc/relationships/unknown',
		'Bearer ' . VALID_TOKEN,
		StatusCodeInterface::STATUS_NOT_FOUND,
		__DIR__ . '/responses/generic/relation.unknown.json',
	],
	'readRelationshipsUnknownNotification' => [
		'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/notifications/74e40f3e-84cb-4e0c-b3b3-fbf8246e0888/relationships/trigger',
		'Bearer ' . VALID_TOKEN,
		StatusCodeInterface::STATUS_NOT_FOUND,
		__DIR__ . '/responses/generic/notFound.json',
	],
	'readRelationshipsUnknownTrigger'      => [
		'/v1/triggers/74e40f3e-84cb-4e0c-b3b3-fbf8246e0888/notifications/05f28df9-5f19-4923-b3f8-b9090116dadc/relationships/trigger',
		'Bearer ' . VALID_TOKEN,
		StatusCodeInterface::STATUS_NOT_FOUND,
		__DIR__ . '/responses/generic/notFound.json',
	],
	'readAllMissingToken'                  => [
		'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/notifications',
		null,
		StatusCodeInterface::STATUS_FORBIDDEN,
		__DIR__ . '/responses/generic/forbidden.json',
	],
	'readOneMissingToken'                  => [
		'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/notifications/05f28df9-5f19-4923-b3f8-b9090116dadc',
		null,
		StatusCodeInterface::STATUS_FORBIDDEN,
		__DIR__ . '/responses/generic/forbidden.json',
	],
	'readAllEmptyToken'                    => [
		'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/notifications',
		'',
		StatusCodeInterface::STATUS_FORBIDDEN,
		__DIR__ . '/responses/generic/forbidden.json',
	],
	'readOneEmptyToken'                    => [
		'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/notifications/05f28df9-5f19-4923-b3f8-b9090116dadc',
		'',
		StatusCodeInterface::STATUS_FORBIDDEN,
		__DIR__ . '/responses/generic/forbidden.json',
	],
	'readAllInvalidToken'                  => [
		'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/notifications',
		'Bearer ' . INVALID_TOKEN,
		StatusCodeInterface::STATUS_UNAUTHORIZED,
		__DIR__ . '/responses/generic/unauthorized.json',
	],
	'readOneInvalidToken'                  => [
		'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/notifications/05f28df9-5f19-4923-b3f8-b9090116dadc',
		'Bearer ' . INVALID_TOKEN,
		StatusCodeInterface::STATUS_UNAUTHORIZED,
		__DIR__ . '/responses/generic/unauthorized.json',
	],
	'readAllExpiredToken'                  => [
		'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/notifications',
		'Bearer ' . EXPIRED_TOKEN,
		StatusCodeInterface::STATUS_UNAUTHORIZED,
		__DIR__ . '/responses/generic/unauthorized.json',
	],
	'readOneExpiredToken'                  => [
		'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/notifications/05f28df9-5f19-4923-b3f8-b9090116dadc',
		'Bearer ' . EXPIRED_TOKEN,
		StatusCodeInterface::STATUS_UNAUTHORIZED,
		__DIR__ . '/responses/generic/unauthorized.json',
	],
];
