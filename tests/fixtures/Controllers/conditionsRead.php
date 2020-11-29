<?php declare(strict_types = 1);

use Fig\Http\Message\StatusCodeInterface;

const VALID_TOKEN = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJjb20uZmFzdHliaXJkLmF1dGgtbW9kdWxlIiwianRpIjoiMjQ3MTBlOTYtYTZmYi00ZmM3LWFhMzAtNDcyNzkwNWQzMDRjIiwiaWF0IjoxNTg1NzQyNDAwLCJleHAiOjE1ODU3NDk2MDAsInVzZXIiOiI1ZTc5ZWZiZi1iZDBkLTViN2MtNDZlZi1iZmJkZWZiZmJkMzQiLCJyb2xlcyI6WyJhZG1pbmlzdHJhdG9yIl19.QH_Oo_uzTXAb3pNnHvXYnnX447nfVq2_ggQ9ZxStu4s';
const EXPIRED_TOKEN = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJjb20uZmFzdHliaXJkLmF1dGgtbW9kdWxlIiwianRpIjoiMjM5Nzk0NzAtYmVmNi00ZjE2LTlkNzUtNmFhMWZiYWVjNWRiIiwiaWF0IjoxNTc3ODgwMDAwLCJleHAiOjE1Nzc4ODcyMDAsInVzZXIiOiI1ZTc5ZWZiZi1iZDBkLTViN2MtNDZlZi1iZmJkZWZiZmJkMzQiLCJyb2xlcyI6WyJhZG1pbmlzdHJhdG9yIl19.2k8-_-dsPVQeYnb6OunzDp9fJmiQ2JLQo8GwtjgpBXg';
const INVALID_TOKEN = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJjb20uZmFzdHliaXJkLmF1dGgtbW9kdWxlIiwianRpIjoiODkyNTcxOTQtNWUyMi00NWZjLThhMzEtM2JhNzI5OWM5OTExIiwiaWF0IjoxNTg1NzQyNDAwLCJleHAiOjE1ODU3NDk2MDAsInVzZXIiOiI1ZTc5ZWZiZi1iZDBkLTViN2MtNDZlZi1iZmJkZWZiZmJkMzQiLCJyb2xlcyI6WyJhZG1pbmlzdHJhdG9yIl19.z8hS0hUVtGkiHBeUTdKC_CMqhMIa4uXotPuJJ6Js6S4';
const VALID_TOKEN_USER = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJjb20uZmFzdHliaXJkLmF1dGgtbW9kdWxlIiwianRpIjoiOGIxN2I5ZjMtNWNkMi00OTU0LWJhM2ItNThlZTRiZTUzMjdkIiwiaWF0IjoxNTg1NzQyNDAwLCJleHAiOjE1ODU3NDk2MDAsInVzZXIiOiI1ZTc5ZWZiZi1iZDBkLTViN2MtNDZlZi1iZmJkZWZiZmJkMzQiLCJyb2xlcyI6WyJ1c2VyIl19.jELVcZGRa5_-Jcpoo3Jfho08vQT2IobtoEQPhxN2tzw';

return [
	// Valid responses
	//////////////////
	'readAll'                           => [
		'/v1/triggers/1b17bcaa-a19e-45f0-98b4-56211cc648ae/conditions',
		'Bearer ' . VALID_TOKEN,
		StatusCodeInterface::STATUS_OK,
		__DIR__ . '/responses/conditions.index.json',
	],
	'readAllPaging'                     => [
		'/v1/triggers/1b17bcaa-a19e-45f0-98b4-56211cc648ae/conditions?page[offset]=1&page[limit]=1',
		'Bearer ' . VALID_TOKEN,
		StatusCodeInterface::STATUS_OK,
		__DIR__ . '/responses/conditions.index.paging.json',
	],
	'readOne'                           => [
		'/v1/triggers/1b17bcaa-a19e-45f0-98b4-56211cc648ae/conditions/09c453b3-c55f-4050-8f1c-b50f8d5728c2',
		'Bearer ' . VALID_TOKEN,
		StatusCodeInterface::STATUS_OK,
		__DIR__ . '/responses/conditions.read.json',
	],
	'readOneInclude'                    => [
		'/v1/triggers/1b17bcaa-a19e-45f0-98b4-56211cc648ae/conditions/09c453b3-c55f-4050-8f1c-b50f8d5728c2?include=trigger',
		'Bearer ' . VALID_TOKEN,
		StatusCodeInterface::STATUS_OK,
		__DIR__ . '/responses/conditions.read.include.json',
	],
	'readRelationshipsTrigger'          => [
		'/v1/triggers/1b17bcaa-a19e-45f0-98b4-56211cc648ae/conditions/09c453b3-c55f-4050-8f1c-b50f8d5728c2/relationships/trigger',
		'Bearer ' . VALID_TOKEN,
		StatusCodeInterface::STATUS_OK,
		__DIR__ . '/responses/conditions.readRelationships.trigger.json',
	],
	'readAllUser'                       => [
		'/v1/triggers/1b17bcaa-a19e-45f0-98b4-56211cc648ae/conditions',
		'Bearer ' . VALID_TOKEN_USER,
		StatusCodeInterface::STATUS_OK,
		__DIR__ . '/responses/conditions.index.json',
	],
	'readOneUser'                       => [
		'/v1/triggers/1b17bcaa-a19e-45f0-98b4-56211cc648ae/conditions/09c453b3-c55f-4050-8f1c-b50f8d5728c2',
		'Bearer ' . VALID_TOKEN_USER,
		StatusCodeInterface::STATUS_OK,
		__DIR__ . '/responses/conditions.read.json',
	],

	// Invalid responses
	////////////////////
	'readOneUnknown'                    => [
		'/v1/triggers/1b17bcaa-a19e-45f0-98b4-56211cc648ae/conditions/74e40f3e-84cb-4e0c-b3b3-fbf8246e0888',
		'Bearer ' . VALID_TOKEN,
		StatusCodeInterface::STATUS_NOT_FOUND,
		__DIR__ . '/responses/generic/notFound.json',
	],
	'readAllInvalid'                    => [
		'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/conditions',
		'Bearer ' . VALID_TOKEN,
		StatusCodeInterface::STATUS_NOT_FOUND,
		__DIR__ . '/responses/generic/notFound.json',
	],
	'readInvalid'                       => [
		'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/conditions/09c453b3-c55f-4050-8f1c-b50f8d5728c2',
		'Bearer ' . VALID_TOKEN,
		StatusCodeInterface::STATUS_NOT_FOUND,
		__DIR__ . '/responses/generic/notFound.json',
	],
	'readOneUnknownTrigger'             => [
		'/v1/triggers/74e40f3e-84cb-4e0c-b3b3-fbf8246e0888/conditions/09c453b3-c55f-4050-8f1c-b50f8d5728c2',
		'Bearer ' . VALID_TOKEN,
		StatusCodeInterface::STATUS_NOT_FOUND,
		__DIR__ . '/responses/generic/notFound.json',
	],
	'readRelationshipsUnknown'          => [
		'/v1/triggers/1b17bcaa-a19e-45f0-98b4-56211cc648ae/conditions/09c453b3-c55f-4050-8f1c-b50f8d5728c2/relationships/unknown',
		'Bearer ' . VALID_TOKEN,
		StatusCodeInterface::STATUS_NOT_FOUND,
		__DIR__ . '/responses/generic/relation.unknown.json',
	],
	'readRelationshipsUnknownCondition' => [
		'/v1/triggers/1b17bcaa-a19e-45f0-98b4-56211cc648ae/conditions/74e40f3e-84cb-4e0c-b3b3-fbf8246e0888/relationships/trigger',
		'Bearer ' . VALID_TOKEN,
		StatusCodeInterface::STATUS_NOT_FOUND,
		__DIR__ . '/responses/generic/notFound.json',
	],
	'readRelationshipsUnknownTrigger'   => [
		'/v1/triggers/74e40f3e-84cb-4e0c-b3b3-fbf8246e0888/conditions/09c453b3-c55f-4050-8f1c-b50f8d5728c2/relationships/trigger',
		'Bearer ' . VALID_TOKEN,
		StatusCodeInterface::STATUS_NOT_FOUND,
		__DIR__ . '/responses/generic/notFound.json',
	],
	'readAllMissingToken'               => [
		'/v1/triggers/1b17bcaa-a19e-45f0-98b4-56211cc648ae/conditions',
		null,
		StatusCodeInterface::STATUS_FORBIDDEN,
		__DIR__ . '/responses/generic/forbidden.json',
	],
	'readOneMissingToken'               => [
		'/v1/triggers/1b17bcaa-a19e-45f0-98b4-56211cc648ae/conditions/09c453b3-c55f-4050-8f1c-b50f8d5728c2',
		null,
		StatusCodeInterface::STATUS_FORBIDDEN,
		__DIR__ . '/responses/generic/forbidden.json',
	],
	'readAllEmptyToken'                 => [
		'/v1/triggers/1b17bcaa-a19e-45f0-98b4-56211cc648ae/conditions',
		'',
		StatusCodeInterface::STATUS_FORBIDDEN,
		__DIR__ . '/responses/generic/forbidden.json',
	],
	'readOneEmptyToken'                 => [
		'/v1/triggers/1b17bcaa-a19e-45f0-98b4-56211cc648ae/conditions/09c453b3-c55f-4050-8f1c-b50f8d5728c2',
		'',
		StatusCodeInterface::STATUS_FORBIDDEN,
		__DIR__ . '/responses/generic/forbidden.json',
	],
	'readAllInvalidToken'               => [
		'/v1/triggers/1b17bcaa-a19e-45f0-98b4-56211cc648ae/conditions',
		'Bearer ' . INVALID_TOKEN,
		StatusCodeInterface::STATUS_UNAUTHORIZED,
		__DIR__ . '/responses/generic/unauthorized.json',
	],
	'readOneInvalidToken'               => [
		'/v1/triggers/1b17bcaa-a19e-45f0-98b4-56211cc648ae/conditions/09c453b3-c55f-4050-8f1c-b50f8d5728c2',
		'Bearer ' . INVALID_TOKEN,
		StatusCodeInterface::STATUS_UNAUTHORIZED,
		__DIR__ . '/responses/generic/unauthorized.json',
	],
	'readAllExpiredToken'               => [
		'/v1/triggers/1b17bcaa-a19e-45f0-98b4-56211cc648ae/conditions',
		'Bearer ' . EXPIRED_TOKEN,
		StatusCodeInterface::STATUS_UNAUTHORIZED,
		__DIR__ . '/responses/generic/unauthorized.json',
	],
	'readOneExpiredToken'               => [
		'/v1/triggers/1b17bcaa-a19e-45f0-98b4-56211cc648ae/conditions/09c453b3-c55f-4050-8f1c-b50f8d5728c2',
		'Bearer ' . EXPIRED_TOKEN,
		StatusCodeInterface::STATUS_UNAUTHORIZED,
		__DIR__ . '/responses/generic/unauthorized.json',
	],
];
