<?php declare(strict_types = 1);

use Fig\Http\Message\StatusCodeInterface;

const VALID_TOKEN = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJjb20uZmFzdHliaXJkLmF1dGgtbW9kdWxlIiwianRpIjoiMjQ3MTBlOTYtYTZmYi00ZmM3LWFhMzAtNDcyNzkwNWQzMDRjIiwiaWF0IjoxNTg1NzQyNDAwLCJleHAiOjE1ODU3NDk2MDAsInVzZXIiOiI1ZTc5ZWZiZi1iZDBkLTViN2MtNDZlZi1iZmJkZWZiZmJkMzQiLCJyb2xlcyI6WyJhZG1pbmlzdHJhdG9yIl19.QH_Oo_uzTXAb3pNnHvXYnnX447nfVq2_ggQ9ZxStu4s';
const EXPIRED_TOKEN = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJjb20uZmFzdHliaXJkLmF1dGgtbW9kdWxlIiwianRpIjoiMjM5Nzk0NzAtYmVmNi00ZjE2LTlkNzUtNmFhMWZiYWVjNWRiIiwiaWF0IjoxNTc3ODgwMDAwLCJleHAiOjE1Nzc4ODcyMDAsInVzZXIiOiI1ZTc5ZWZiZi1iZDBkLTViN2MtNDZlZi1iZmJkZWZiZmJkMzQiLCJyb2xlcyI6WyJhZG1pbmlzdHJhdG9yIl19.2k8-_-dsPVQeYnb6OunzDp9fJmiQ2JLQo8GwtjgpBXg';
const INVALID_TOKEN = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJjb20uZmFzdHliaXJkLmF1dGgtbW9kdWxlIiwianRpIjoiODkyNTcxOTQtNWUyMi00NWZjLThhMzEtM2JhNzI5OWM5OTExIiwiaWF0IjoxNTg1NzQyNDAwLCJleHAiOjE1ODU3NDk2MDAsInVzZXIiOiI1ZTc5ZWZiZi1iZDBkLTViN2MtNDZlZi1iZmJkZWZiZmJkMzQiLCJyb2xlcyI6WyJhZG1pbmlzdHJhdG9yIl19.z8hS0hUVtGkiHBeUTdKC_CMqhMIa4uXotPuJJ6Js6S4';
const VALID_TOKEN_USER = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJjb20uZmFzdHliaXJkLmF1dGgtbW9kdWxlIiwianRpIjoiOGIxN2I5ZjMtNWNkMi00OTU0LWJhM2ItNThlZTRiZTUzMjdkIiwiaWF0IjoxNTg1NzQyNDAwLCJleHAiOjE1ODU3NDk2MDAsInVzZXIiOiI1ZTc5ZWZiZi1iZDBkLTViN2MtNDZlZi1iZmJkZWZiZmJkMzQiLCJyb2xlcyI6WyJ1c2VyIl19.jELVcZGRa5_-Jcpoo3Jfho08vQT2IobtoEQPhxN2tzw';

return [
	// Valid responses
	//////////////////
	'createChannelProperty' => [
		'/v1/triggers/1b17bcaa-a19e-45f0-98b4-56211cc648ae/conditions',
		'Bearer ' . VALID_TOKEN,
		file_get_contents(__DIR__ . '/requests/conditions.createChannelProperty.json'),
		StatusCodeInterface::STATUS_CREATED,
		__DIR__ . '/responses/conditions.createChannelProperty.json',
	],
	'createDeviceProperty'  => [
		'/v1/triggers/1b17bcaa-a19e-45f0-98b4-56211cc648ae/conditions',
		'Bearer ' . VALID_TOKEN,
		file_get_contents(__DIR__ . '/requests/conditions.createDeviceProperty.json'),
		StatusCodeInterface::STATUS_CREATED,
		__DIR__ . '/responses/conditions.createDeviceProperty.json',
	],
	'createTime'            => [
		'/v1/triggers/1b17bcaa-a19e-45f0-98b4-56211cc648ae/conditions',
		'Bearer ' . VALID_TOKEN,
		file_get_contents(__DIR__ . '/requests/conditions.createTime.json'),
		StatusCodeInterface::STATUS_CREATED,
		__DIR__ . '/responses/conditions.createTime.json',
	],

	// Invalid responses
	////////////////////
	'notAllowed'            => [
		'/v1/triggers/1b17bcaa-a19e-45f0-98b4-56211cc648ae/conditions',
		'Bearer ' . VALID_TOKEN_USER,
		file_get_contents(__DIR__ . '/requests/conditions.createChannelProperty.json'),
		StatusCodeInterface::STATUS_FORBIDDEN,
		__DIR__ . '/responses/generic/forbidden.json',
	],
	'missingRequired'       => [
		'/v1/triggers/1b17bcaa-a19e-45f0-98b4-56211cc648ae/conditions',
		'Bearer ' . VALID_TOKEN,
		file_get_contents(__DIR__ . '/requests/conditions.create.missing.required.json'),
		StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
		__DIR__ . '/responses/conditions.create.missing.required.json',
	],
	'unknownTrigger'        => [
		'/v1/triggers/74e40f3e-84cb-4e0c-b3b3-fbf8246e0888/conditions',
		'Bearer ' . VALID_TOKEN,
		file_get_contents(__DIR__ . '/requests/conditions.createChannelProperty.json'),
		StatusCodeInterface::STATUS_NOT_FOUND,
		__DIR__ . '/responses/generic/notFound.json',
	],
	'invalidType'           => [
		'/v1/triggers/1b17bcaa-a19e-45f0-98b4-56211cc648ae/conditions',
		'Bearer ' . VALID_TOKEN,
		file_get_contents(__DIR__ . '/requests/conditions.create.invalidType.json'),
		StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
		__DIR__ . '/responses/generic/invalid.type.json',
	],
	'missingToken'          => [
		'/v1/triggers/1b17bcaa-a19e-45f0-98b4-56211cc648ae/conditions',
		null,
		file_get_contents(__DIR__ . '/requests/conditions.createChannelProperty.json'),
		StatusCodeInterface::STATUS_FORBIDDEN,
		__DIR__ . '/responses/generic/forbidden.json',
	],
	'emptyToken'            => [
		'/v1/triggers/1b17bcaa-a19e-45f0-98b4-56211cc648ae/conditions',
		'',
		file_get_contents(__DIR__ . '/requests/conditions.createChannelProperty.json'),
		StatusCodeInterface::STATUS_FORBIDDEN,
		__DIR__ . '/responses/generic/forbidden.json',
	],
	'invalidToken'          => [
		'/v1/triggers/1b17bcaa-a19e-45f0-98b4-56211cc648ae/conditions',
		'Bearer ' . INVALID_TOKEN,
		file_get_contents(__DIR__ . '/requests/conditions.createChannelProperty.json'),
		StatusCodeInterface::STATUS_UNAUTHORIZED,
		__DIR__ . '/responses/generic/unauthorized.json',
	],
	'expiredToken'          => [
		'/v1/triggers/1b17bcaa-a19e-45f0-98b4-56211cc648ae/conditions',
		'Bearer ' . EXPIRED_TOKEN,
		file_get_contents(__DIR__ . '/requests/conditions.createChannelProperty.json'),
		StatusCodeInterface::STATUS_UNAUTHORIZED,
		__DIR__ . '/responses/generic/unauthorized.json',
	],
];
