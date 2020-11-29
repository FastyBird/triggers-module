<?php declare(strict_types = 1);

use Fig\Http\Message\StatusCodeInterface;

const VALID_TOKEN = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJjb20uZmFzdHliaXJkLmF1dGgtbW9kdWxlIiwianRpIjoiMjQ3MTBlOTYtYTZmYi00ZmM3LWFhMzAtNDcyNzkwNWQzMDRjIiwiaWF0IjoxNTg1NzQyNDAwLCJleHAiOjE1ODU3NDk2MDAsInVzZXIiOiI1ZTc5ZWZiZi1iZDBkLTViN2MtNDZlZi1iZmJkZWZiZmJkMzQiLCJyb2xlcyI6WyJhZG1pbmlzdHJhdG9yIl19.QH_Oo_uzTXAb3pNnHvXYnnX447nfVq2_ggQ9ZxStu4s';
const EXPIRED_TOKEN = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJjb20uZmFzdHliaXJkLmF1dGgtbW9kdWxlIiwianRpIjoiMjM5Nzk0NzAtYmVmNi00ZjE2LTlkNzUtNmFhMWZiYWVjNWRiIiwiaWF0IjoxNTc3ODgwMDAwLCJleHAiOjE1Nzc4ODcyMDAsInVzZXIiOiI1ZTc5ZWZiZi1iZDBkLTViN2MtNDZlZi1iZmJkZWZiZmJkMzQiLCJyb2xlcyI6WyJhZG1pbmlzdHJhdG9yIl19.2k8-_-dsPVQeYnb6OunzDp9fJmiQ2JLQo8GwtjgpBXg';
const INVALID_TOKEN = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJjb20uZmFzdHliaXJkLmF1dGgtbW9kdWxlIiwianRpIjoiODkyNTcxOTQtNWUyMi00NWZjLThhMzEtM2JhNzI5OWM5OTExIiwiaWF0IjoxNTg1NzQyNDAwLCJleHAiOjE1ODU3NDk2MDAsInVzZXIiOiI1ZTc5ZWZiZi1iZDBkLTViN2MtNDZlZi1iZmJkZWZiZmJkMzQiLCJyb2xlcyI6WyJhZG1pbmlzdHJhdG9yIl19.z8hS0hUVtGkiHBeUTdKC_CMqhMIa4uXotPuJJ6Js6S4';
const VALID_TOKEN_USER = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJjb20uZmFzdHliaXJkLmF1dGgtbW9kdWxlIiwianRpIjoiOGIxN2I5ZjMtNWNkMi00OTU0LWJhM2ItNThlZTRiZTUzMjdkIiwiaWF0IjoxNTg1NzQyNDAwLCJleHAiOjE1ODU3NDk2MDAsInVzZXIiOiI1ZTc5ZWZiZi1iZDBkLTViN2MtNDZlZi1iZmJkZWZiZmJkMzQiLCJyb2xlcyI6WyJ1c2VyIl19.jELVcZGRa5_-Jcpoo3Jfho08vQT2IobtoEQPhxN2tzw';

return [
	// Valid responses
	//////////////////
	'update'              => [
		'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/notifications/05f28df9-5f19-4923-b3f8-b9090116dadc',
		'Bearer ' . VALID_TOKEN,
		file_get_contents(__DIR__ . '/requests/notifications.update.json'),
		StatusCodeInterface::STATUS_OK,
		__DIR__ . '/responses/notifications.update.json',
	],

	// Invalid responses
	////////////////////
	'notAllowed'          => [
		'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/notifications/05f28df9-5f19-4923-b3f8-b9090116dadc',
		'Bearer ' . VALID_TOKEN_USER,
		file_get_contents(__DIR__ . '/requests/notifications.update.json'),
		StatusCodeInterface::STATUS_FORBIDDEN,
		__DIR__ . '/responses/generic/forbidden.json',
	],
	'unknownTrigger'      => [
		'/v1/triggers/74e40f3e-84cb-4e0c-b3b3-fbf8246e0888/notifications/05f28df9-5f19-4923-b3f8-b9090116dadc',
		'Bearer ' . VALID_TOKEN,
		file_get_contents(__DIR__ . '/requests/notifications.update.json'),
		StatusCodeInterface::STATUS_NOT_FOUND,
		__DIR__ . '/responses/generic/notFound.json',
	],
	'unknownNotification' => [
		'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/notifications/74e40f3e-84cb-4e0c-b3b3-fbf8246e0888',
		'Bearer ' . VALID_TOKEN,
		file_get_contents(__DIR__ . '/requests/notifications.update.json'),
		StatusCodeInterface::STATUS_NOT_FOUND,
		__DIR__ . '/responses/generic/notFound.json',
	],
	'invalidType'         => [
		'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/notifications/05f28df9-5f19-4923-b3f8-b9090116dadc',
		'Bearer ' . VALID_TOKEN,
		file_get_contents(__DIR__ . '/requests/notifications.update.invalidType.json'),
		StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
		__DIR__ . '/responses/generic/invalid.type.json',
	],
	'idMismatch'          => [
		'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/notifications/05f28df9-5f19-4923-b3f8-b9090116dadc',
		'Bearer ' . VALID_TOKEN,
		file_get_contents(__DIR__ . '/requests/notifications.update.idMismatch.json'),
		StatusCodeInterface::STATUS_BAD_REQUEST,
		__DIR__ . '/responses/generic/invalid.identifier.json',
	],
	'missingToken'        => [
		'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/notifications/05f28df9-5f19-4923-b3f8-b9090116dadc',
		null,
		file_get_contents(__DIR__ . '/requests/notifications.update.json'),
		StatusCodeInterface::STATUS_FORBIDDEN,
		__DIR__ . '/responses/generic/forbidden.json',
	],
	'emptyToken'          => [
		'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/notifications/05f28df9-5f19-4923-b3f8-b9090116dadc',
		'',
		file_get_contents(__DIR__ . '/requests/notifications.update.json'),
		StatusCodeInterface::STATUS_FORBIDDEN,
		__DIR__ . '/responses/generic/forbidden.json',
	],
	'invalidToken'        => [
		'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/notifications/05f28df9-5f19-4923-b3f8-b9090116dadc',
		'Bearer ' . INVALID_TOKEN,
		file_get_contents(__DIR__ . '/requests/notifications.update.json'),
		StatusCodeInterface::STATUS_UNAUTHORIZED,
		__DIR__ . '/responses/generic/unauthorized.json',
	],
	'expiredToken'        => [
		'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/notifications/05f28df9-5f19-4923-b3f8-b9090116dadc',
		'Bearer ' . EXPIRED_TOKEN,
		file_get_contents(__DIR__ . '/requests/notifications.update.json'),
		StatusCodeInterface::STATUS_UNAUTHORIZED,
		__DIR__ . '/responses/generic/unauthorized.json',
	],
];
