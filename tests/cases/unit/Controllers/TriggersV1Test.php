<?php declare(strict_types = 1);

namespace FastyBird\Module\Triggers\Tests\Cases\Unit\Controllers;

use FastyBird\Library\Bootstrap\Exceptions as BootstrapExceptions;
use FastyBird\Module\Triggers\Tests\Cases\Unit\DbTestCase;
use FastyBird\Module\Triggers\Tests\Tools;
use Fig\Http\Message\RequestMethodInterface;
use Fig\Http\Message\StatusCodeInterface;
use InvalidArgumentException;
use IPub\SlimRouter;
use IPub\SlimRouter\Http as SlimRouterHttp;
use Nette;
use Nette\Utils;
use React\Http\Message\ServerRequest;
use RuntimeException;
use function file_get_contents;
use function is_array;
use function str_replace;
use function strval;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
final class TriggersV1Test extends DbTestCase
{

	/**
	 * @throws BootstrapExceptions\InvalidArgument
	 * @throws InvalidArgumentException
	 * @throws Nette\DI\MissingServiceException
	 * @throws RuntimeException
	 * @throws Utils\JsonException
	 *
	 * @dataProvider triggersRead
	 */
	public function testRead(string $url, string|null $token, int $statusCode, string $fixture): void
	{
		$router = $this->getContainer()->getByType(SlimRouter\Routing\IRouter::class);

		$headers = [];

		if ($token !== null) {
			$headers['authorization'] = $token;
		}

		$request = new ServerRequest(
			RequestMethodInterface::METHOD_GET,
			$url,
			$headers,
		);

		$response = $router->handle($request);

		self::assertTrue($response instanceof SlimRouterHttp\Response);
		self::assertSame($statusCode, $response->getStatusCode());
		Tools\JsonAssert::assertFixtureMatch(
			$fixture,
			(string) $response->getBody(),
		);
	}

	/**
	 * @return array<string, array<string|int|null>>
	 */
	public function triggersRead(): array
	{
		return [
			// Valid responses
			//////////////////
			'readAll' => [
				'/v1/triggers',
				'Bearer ' . self::VALID_TOKEN,
				StatusCodeInterface::STATUS_OK,
				__DIR__ . '/../../../fixtures/Controllers/responses/triggers.index.json',
			],
			'readAllPaging' => [
				'/v1/triggers?page[offset]=1&page[limit]=1',
				'Bearer ' . self::VALID_TOKEN,
				StatusCodeInterface::STATUS_OK,
				__DIR__ . '/../../../fixtures/Controllers/responses/triggers.index.paging.json',
			],
			'readOne' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4',
				'Bearer ' . self::VALID_TOKEN,
				StatusCodeInterface::STATUS_OK,
				__DIR__ . '/../../../fixtures/Controllers/responses/triggers.read.json',
			],
			'readOneInclude' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4?include=actions,notifications',
				'Bearer ' . self::VALID_TOKEN,
				StatusCodeInterface::STATUS_OK,
				__DIR__ . '/../../../fixtures/Controllers/responses/triggers.read.include.json',
			],
			'readRelationshipsActions' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/relationships/actions',
				'Bearer ' . self::VALID_TOKEN,
				StatusCodeInterface::STATUS_OK,
				__DIR__ . '/../../../fixtures/Controllers/responses/triggers.readRelationships.actions.json',
			],
			'readRelationshipsNotifications' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/relationships/notifications',
				'Bearer ' . self::VALID_TOKEN,
				StatusCodeInterface::STATUS_OK,
				__DIR__ . '/../../../fixtures/Controllers/responses/triggers.readRelationships.notifications.json',
			],
			'readRelationshipsConditions' => [
				'/v1/triggers/0b48dfbc-fac2-4292-88dc-7981a121602d/relationships/conditions',
				'Bearer ' . self::VALID_TOKEN,
				StatusCodeInterface::STATUS_OK,
				__DIR__ . '/../../../fixtures/Controllers/responses/triggers.readRelationships.conditions.json',
			],
			'readAllUser' => [
				'/v1/triggers',
				'Bearer ' . self::VALID_TOKEN_USER,
				StatusCodeInterface::STATUS_OK,
				__DIR__ . '/../../../fixtures/Controllers/responses/triggers.index.json',
			],
			'readOneUser' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4',
				'Bearer ' . self::VALID_TOKEN_USER,
				StatusCodeInterface::STATUS_OK,
				__DIR__ . '/../../../fixtures/Controllers/responses/triggers.read.json',
			],

			// Invalid responses
			////////////////////
			'readOneUnknown' => [
				'/v1/triggers/74e40f3e-84cb-4e0c-b3b3-fbf8246e0888',
				'Bearer ' . self::VALID_TOKEN,
				StatusCodeInterface::STATUS_NOT_FOUND,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/notFound.json',
			],
			'readRelationshipsConditionsInvalid' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/relationships/conditions',
				'Bearer ' . self::VALID_TOKEN,
				StatusCodeInterface::STATUS_NOT_FOUND,
				__DIR__ . '/../../../fixtures/Controllers/responses/triggers.readRelationships.conditionsInvalid.json',
			],
			'readRelationshipsUnknown' => [
				'/v1/triggers/0b48dfbc-fac2-4292-88dc-7981a121602d/relationships/unknown',
				'Bearer ' . self::VALID_TOKEN,
				StatusCodeInterface::STATUS_NOT_FOUND,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/relation.unknown.json',
			],
			'readRelationshipsUnknownTrigger' => [
				'/v1/triggers/74e40f3e-84cb-4e0c-b3b3-fbf8246e0888/relationships/actions',
				'Bearer ' . self::VALID_TOKEN,
				StatusCodeInterface::STATUS_NOT_FOUND,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/notFound.json',
			],
			'readAllMissingToken' => [
				'/v1/triggers',
				null,
				StatusCodeInterface::STATUS_FORBIDDEN,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/forbidden.json',
			],
			'readOneMissingToken' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4',
				null,
				StatusCodeInterface::STATUS_FORBIDDEN,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/forbidden.json',
			],
			'readAllEmptyToken' => [
				'/v1/triggers',
				'',
				StatusCodeInterface::STATUS_FORBIDDEN,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/forbidden.json',
			],
			'readOneEmptyToken' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4',
				'',
				StatusCodeInterface::STATUS_FORBIDDEN,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/forbidden.json',
			],
			'readAllInvalidToken' => [
				'/v1/triggers',
				'Bearer ' . self::INVALID_TOKEN,
				StatusCodeInterface::STATUS_UNAUTHORIZED,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/unauthorized.json',
			],
			'readOneInvalidToken' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4',
				'Bearer ' . self::INVALID_TOKEN,
				StatusCodeInterface::STATUS_UNAUTHORIZED,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/unauthorized.json',
			],
			'readAllExpiredToken' => [
				'/v1/triggers',
				'Bearer ' . self::EXPIRED_TOKEN,
				StatusCodeInterface::STATUS_UNAUTHORIZED,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/unauthorized.json',
			],
			'readOneExpiredToken' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4',
				'Bearer ' . self::EXPIRED_TOKEN,
				StatusCodeInterface::STATUS_UNAUTHORIZED,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/unauthorized.json',
			],
		];
	}

	/**
	 * @throws BootstrapExceptions\InvalidArgument
	 * @throws InvalidArgumentException
	 * @throws Nette\DI\MissingServiceException
	 * @throws RuntimeException
	 * @throws Utils\JsonException
	 *
	 * @dataProvider triggersCreate
	 */
	public function testCreate(string $url, string|null $token, string $body, int $statusCode, string $fixture): void
	{
		$router = $this->getContainer()->getByType(SlimRouter\Routing\IRouter::class);

		$headers = [];

		if ($token !== null) {
			$headers['authorization'] = $token;
		}

		$request = new ServerRequest(
			RequestMethodInterface::METHOD_POST,
			$url,
			$headers,
			$body,
		);

		$response = $router->handle($request);

		self::assertTrue($response instanceof SlimRouterHttp\Response);
		self::assertSame($statusCode, $response->getStatusCode());

		$responseBody = (string) $response->getBody();

		$actual = Utils\Json::decode($responseBody, Utils\Json::FORCE_ARRAY);
		self::assertTrue(is_array($actual));

		Tools\JsonAssert::assertFixtureMatch(
			$fixture,
			$responseBody,
			static function (string $expectation) use ($actual): string {
				if (
					isset($actual['data'])
					&& is_array($actual['data'])
					&& isset($actual['data']['relationships'])
					&& is_array($actual['data']['relationships'])
					&& isset($actual['data']['relationships']['controls'])
					&& is_array($actual['data']['relationships']['controls'])
					&& isset($actual['data']['relationships']['controls']['data'])
					&& is_array($actual['data']['relationships']['controls']['data'])
					&& isset($actual['data']['relationships']['controls']['data'][0])
					&& is_array($actual['data']['relationships']['controls']['data'][0])
					&& isset($actual['data']['relationships']['controls']['data'][0]['id'])
				) {
					$expectation = str_replace(
						'__CONTROL_IDENTIFIER_PLACEHOLDER__',
						strval($actual['data']['relationships']['controls']['data'][0]['id']),
						$expectation,
					);
				}

				return $expectation;
			},
		);
	}

	/**
	 * @return array<string, array<bool|string|int|null>>
	 */
	public function triggersCreate(): array
	{
		return [
			// Valid responses
			//////////////////
			'createManual' => [
				'/v1/triggers',
				'Bearer ' . self::VALID_TOKEN,
				file_get_contents(__DIR__ . '/../../../fixtures/Controllers/requests/triggers.createManual.json'),
				StatusCodeInterface::STATUS_CREATED,
				__DIR__ . '/../../../fixtures/Controllers/responses/triggers.createManual.json',
			],

			// Invalid responses
			////////////////////
			'notAllowed' => [
				'/v1/triggers',
				'Bearer ' . self::VALID_TOKEN_USER,
				file_get_contents(__DIR__ . '/../../../fixtures/Controllers/requests/triggers.createManual.json'),
				StatusCodeInterface::STATUS_FORBIDDEN,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/forbidden.json',
			],
			'missingRequired' => [
				'/v1/triggers',
				'Bearer ' . self::VALID_TOKEN,
				file_get_contents(
					__DIR__ . '/../../../fixtures/Controllers/requests/triggers.create.missing.required.json',
				),
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				__DIR__ . '/../../../fixtures/Controllers/responses/triggers.create.missing.required.json',
			],
			'invalidType' => [
				'/v1/triggers',
				'Bearer ' . self::VALID_TOKEN,
				file_get_contents(__DIR__ . '/../../../fixtures/Controllers/requests/triggers.create.invalidType.json'),
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/invalid.type.json',
			],
			'missingToken' => [
				'/v1/triggers',
				null,
				file_get_contents(__DIR__ . '/../../../fixtures/Controllers/requests/triggers.createManual.json'),
				StatusCodeInterface::STATUS_FORBIDDEN,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/forbidden.json',
			],
			'emptyToken' => [
				'/v1/triggers',
				'',
				file_get_contents(__DIR__ . '/../../../fixtures/Controllers/requests/triggers.createManual.json'),
				StatusCodeInterface::STATUS_FORBIDDEN,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/forbidden.json',
			],
			'invalidToken' => [
				'/v1/triggers',
				'Bearer ' . self::INVALID_TOKEN,
				file_get_contents(__DIR__ . '/../../../fixtures/Controllers/requests/triggers.createManual.json'),
				StatusCodeInterface::STATUS_UNAUTHORIZED,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/unauthorized.json',
			],
			'expiredToken' => [
				'/v1/triggers',
				'Bearer ' . self::EXPIRED_TOKEN,
				file_get_contents(__DIR__ . '/../../../fixtures/Controllers/requests/triggers.createManual.json'),
				StatusCodeInterface::STATUS_UNAUTHORIZED,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/unauthorized.json',
			],
		];
	}

	/**
	 * @throws BootstrapExceptions\InvalidArgument
	 * @throws InvalidArgumentException
	 * @throws Nette\DI\MissingServiceException
	 * @throws RuntimeException
	 * @throws Utils\JsonException
	 *
	 * @dataProvider triggersUpdate
	 */
	public function testUpdate(string $url, string|null $token, string $body, int $statusCode, string $fixture): void
	{
		$router = $this->getContainer()->getByType(SlimRouter\Routing\IRouter::class);

		$headers = [];

		if ($token !== null) {
			$headers['authorization'] = $token;
		}

		$request = new ServerRequest(
			RequestMethodInterface::METHOD_PATCH,
			$url,
			$headers,
			$body,
		);

		$response = $router->handle($request);

		self::assertTrue($response instanceof SlimRouterHttp\Response);
		self::assertSame($statusCode, $response->getStatusCode());
		Tools\JsonAssert::assertFixtureMatch(
			$fixture,
			(string) $response->getBody(),
		);
	}

	/**
	 * @return array<string, array<bool|string|int|null>>
	 */
	public function triggersUpdate(): array
	{
		return [
			// Valid responses
			//////////////////
			'update' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4',
				'Bearer ' . self::VALID_TOKEN,
				file_get_contents(__DIR__ . '/../../../fixtures/Controllers/requests/triggers.update.json'),
				StatusCodeInterface::STATUS_OK,
				__DIR__ . '/../../../fixtures/Controllers/responses/triggers.update.json',
			],

			// Invalid responses
			////////////////////
			'notAllowed' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4',
				'Bearer ' . self::VALID_TOKEN_USER,
				file_get_contents(__DIR__ . '/../../../fixtures/Controllers/requests/triggers.update.json'),
				StatusCodeInterface::STATUS_FORBIDDEN,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/forbidden.json',
			],
			'unknownTrigger' => [
				'/v1/triggers/74e40f3e-84cb-4e0c-b3b3-fbf8246e0888',
				'Bearer ' . self::VALID_TOKEN,
				file_get_contents(__DIR__ . '/../../../fixtures/Controllers/requests/triggers.update.json'),
				StatusCodeInterface::STATUS_NOT_FOUND,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/notFound.json',
			],
			'invalidType' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4',
				'Bearer ' . self::VALID_TOKEN,
				file_get_contents(__DIR__ . '/../../../fixtures/Controllers/requests/triggers.update.invalidType.json'),
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/invalid.type.json',
			],
			'idMismatch' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4',
				'Bearer ' . self::VALID_TOKEN,
				file_get_contents(__DIR__ . '/../../../fixtures/Controllers/requests/triggers.update.idMismatch.json'),
				StatusCodeInterface::STATUS_BAD_REQUEST,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/invalid.identifier.json',
			],
			'missingToken' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4',
				null,
				file_get_contents(__DIR__ . '/../../../fixtures/Controllers/requests/triggers.update.json'),
				StatusCodeInterface::STATUS_FORBIDDEN,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/forbidden.json',
			],
			'emptyToken' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4',
				'',
				file_get_contents(__DIR__ . '/../../../fixtures/Controllers/requests/triggers.update.json'),
				StatusCodeInterface::STATUS_FORBIDDEN,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/forbidden.json',
			],
			'invalidToken' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4',
				'Bearer ' . self::INVALID_TOKEN,
				file_get_contents(__DIR__ . '/../../../fixtures/Controllers/requests/triggers.update.json'),
				StatusCodeInterface::STATUS_UNAUTHORIZED,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/unauthorized.json',
			],
			'expiredToken' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4',
				'Bearer ' . self::EXPIRED_TOKEN,
				file_get_contents(__DIR__ . '/../../../fixtures/Controllers/requests/triggers.update.json'),
				StatusCodeInterface::STATUS_UNAUTHORIZED,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/unauthorized.json',
			],
		];
	}

	/**
	 * @throws BootstrapExceptions\InvalidArgument
	 * @throws InvalidArgumentException
	 * @throws Nette\DI\MissingServiceException
	 * @throws RuntimeException
	 * @throws Utils\JsonException
	 *
	 * @dataProvider triggersDelete
	 */
	public function testDelete(string $url, string|null $token, int $statusCode, string $fixture): void
	{
		$router = $this->getContainer()->getByType(SlimRouter\Routing\IRouter::class);

		$headers = [];

		if ($token !== null) {
			$headers['authorization'] = $token;
		}

		$request = new ServerRequest(
			RequestMethodInterface::METHOD_DELETE,
			$url,
			$headers,
		);

		$response = $router->handle($request);

		self::assertTrue($response instanceof SlimRouterHttp\Response);
		self::assertSame($statusCode, $response->getStatusCode());
		Tools\JsonAssert::assertFixtureMatch(
			$fixture,
			(string) $response->getBody(),
		);
	}

	/**
	 * @return array<string, array<string|int|null>>
	 */
	public function triggersDelete(): array
	{
		return [
			// Valid responses
			//////////////////
			'delete' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4',
				'Bearer ' . self::VALID_TOKEN,
				StatusCodeInterface::STATUS_NO_CONTENT,
				__DIR__ . '/../../../fixtures/Controllers/responses/triggers.delete.json',
			],

			// Invalid responses
			////////////////////
			'notAllowed' => [
				'/v1/triggers/69786d15-fd0c-4d9f-9378-33287c2009af',
				'Bearer ' . self::VALID_TOKEN_USER,
				StatusCodeInterface::STATUS_FORBIDDEN,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/forbidden.json',
			],
			'deleteUnknown' => [
				'/v1/triggers/74e40f3e-84cb-4e0c-b3b3-fbf8246e0888',
				'Bearer ' . self::VALID_TOKEN,
				StatusCodeInterface::STATUS_NOT_FOUND,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/notFound.json',
			],
			'missingToken' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4',
				null,
				StatusCodeInterface::STATUS_FORBIDDEN,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/forbidden.json',
			],
			'emptyToken' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4',
				'',
				StatusCodeInterface::STATUS_FORBIDDEN,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/forbidden.json',
			],
			'invalidToken' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4',
				'Bearer ' . self::INVALID_TOKEN,
				StatusCodeInterface::STATUS_UNAUTHORIZED,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/unauthorized.json',
			],
			'expiredToken' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4',
				'Bearer ' . self::EXPIRED_TOKEN,
				StatusCodeInterface::STATUS_UNAUTHORIZED,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/unauthorized.json',
			],
		];
	}

}
