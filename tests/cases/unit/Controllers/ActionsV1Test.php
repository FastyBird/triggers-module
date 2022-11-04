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

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
final class ActionsV1Test extends DbTestCase
{

	/**
	 * @throws BootstrapExceptions\InvalidArgument
	 * @throws InvalidArgumentException
	 * @throws Nette\DI\MissingServiceException
	 * @throws RuntimeException
	 * @throws Utils\JsonException
	 *
	 * @dataProvider actionsRead
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
	 * @return Array<string, Array<string|int|null>>
	 */
	public function actionsRead(): array
	{
		return [
			// Valid responses
			//////////////////
			'readAll' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions',
				'Bearer ' . self::VALID_TOKEN,
				StatusCodeInterface::STATUS_OK,
				__DIR__ . '/../../../fixtures/Controllers/responses/actions.index.json',
			],
			'readAllPaging' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions?page[offset]=1&page[limit]=1',
				'Bearer ' . self::VALID_TOKEN,
				StatusCodeInterface::STATUS_OK,
				__DIR__ . '/../../../fixtures/Controllers/responses/actions.index.paging.json',
			],
			'readOne' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions/4aa84028-d8b7-4128-95b2-295763634aa4',
				'Bearer ' . self::VALID_TOKEN,
				StatusCodeInterface::STATUS_OK,
				__DIR__ . '/../../../fixtures/Controllers/responses/actions.read.json',
			],
			'readOneInclude' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions/4aa84028-d8b7-4128-95b2-295763634aa4?include=trigger',
				'Bearer ' . self::VALID_TOKEN,
				StatusCodeInterface::STATUS_OK,
				__DIR__ . '/../../../fixtures/Controllers/responses/actions.read.include.json',
			],
			'readRelationshipsTrigger' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions/4aa84028-d8b7-4128-95b2-295763634aa4/relationships/trigger',
				'Bearer ' . self::VALID_TOKEN,
				StatusCodeInterface::STATUS_OK,
				__DIR__ . '/../../../fixtures/Controllers/responses/actions.readRelationships.trigger.json',
			],
			'readAllUser' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions',
				'Bearer ' . self::VALID_TOKEN_USER,
				StatusCodeInterface::STATUS_OK,
				__DIR__ . '/../../../fixtures/Controllers/responses/actions.index.json',
			],
			'readOneUser' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions/4aa84028-d8b7-4128-95b2-295763634aa4',
				'Bearer ' . self::VALID_TOKEN_USER,
				StatusCodeInterface::STATUS_OK,
				__DIR__ . '/../../../fixtures/Controllers/responses/actions.read.json',
			],

			// Invalid responses
			////////////////////
			'readOneUnknown' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions/74e40f3e-84cb-4e0c-b3b3-fbf8246e0888',
				'Bearer ' . self::VALID_TOKEN,
				StatusCodeInterface::STATUS_NOT_FOUND,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/notFound.json',
			],
			'readOneUnknownTrigger' => [
				'/v1/triggers/74e40f3e-84cb-4e0c-b3b3-fbf8246e0888/actions/4aa84028-d8b7-4128-95b2-295763634aa4',
				'Bearer ' . self::VALID_TOKEN,
				StatusCodeInterface::STATUS_NOT_FOUND,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/notFound.json',
			],
			'readRelationshipsUnknown' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions/4aa84028-d8b7-4128-95b2-295763634aa4/relationships/unknown',
				'Bearer ' . self::VALID_TOKEN,
				StatusCodeInterface::STATUS_NOT_FOUND,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/relation.unknown.json',
			],
			'readRelationshipsUnknownAction' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions/74e40f3e-84cb-4e0c-b3b3-fbf8246e0888/relationships/trigger',
				'Bearer ' . self::VALID_TOKEN,
				StatusCodeInterface::STATUS_NOT_FOUND,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/notFound.json',
			],
			'readRelationshipsUnknownTrigger' => [
				'/v1/triggers/74e40f3e-84cb-4e0c-b3b3-fbf8246e0888/actions/4aa84028-d8b7-4128-95b2-295763634aa4/relationships/trigger',
				'Bearer ' . self::VALID_TOKEN,
				StatusCodeInterface::STATUS_NOT_FOUND,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/notFound.json',
			],
			'readAllMissingToken' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions',
				null,
				StatusCodeInterface::STATUS_FORBIDDEN,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/forbidden.json',
			],
			'readOneMissingToken' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions/4aa84028-d8b7-4128-95b2-295763634aa4',
				null,
				StatusCodeInterface::STATUS_FORBIDDEN,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/forbidden.json',
			],
			'readAllEmptyToken' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions',
				'',
				StatusCodeInterface::STATUS_FORBIDDEN,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/forbidden.json',
			],
			'readOneEmptyToken' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions/4aa84028-d8b7-4128-95b2-295763634aa4',
				'',
				StatusCodeInterface::STATUS_FORBIDDEN,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/forbidden.json',
			],
			'readAllInvalidToken' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions',
				'Bearer ' . self::INVALID_TOKEN,
				StatusCodeInterface::STATUS_UNAUTHORIZED,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/unauthorized.json',
			],
			'readOneInvalidToken' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions/4aa84028-d8b7-4128-95b2-295763634aa4',
				'Bearer ' . self::INVALID_TOKEN,
				StatusCodeInterface::STATUS_UNAUTHORIZED,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/unauthorized.json',
			],
			'readAllExpiredToken' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions',
				'Bearer ' . self::EXPIRED_TOKEN,
				StatusCodeInterface::STATUS_UNAUTHORIZED,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/unauthorized.json',
			],
			'readOneExpiredToken' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions/4aa84028-d8b7-4128-95b2-295763634aa4',
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
	 * @dataProvider actionsCreate
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
		Tools\JsonAssert::assertFixtureMatch(
			$fixture,
			(string) $response->getBody(),
		);
	}

	/**
	 * @return Array<string, Array<bool|string|int|null>>
	 */
	public function actionsCreate(): array
	{
		return [
			// Valid responses
			//////////////////
			'createChannelProperty' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions',
				'Bearer ' . self::VALID_TOKEN,
				file_get_contents(
					__DIR__ . '/../../../fixtures/Controllers/requests/actions.createChannelProperty.json',
				),
				StatusCodeInterface::STATUS_CREATED,
				__DIR__ . '/../../../fixtures/Controllers/responses/actions.createChannelProperty.json',
			],

			// Invalid responses
			////////////////////
			'notAllowed' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions',
				'Bearer ' . self::VALID_TOKEN_USER,
				file_get_contents(
					__DIR__ . '/../../../fixtures/Controllers/requests/actions.createChannelProperty.json',
				),
				StatusCodeInterface::STATUS_FORBIDDEN,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/forbidden.json',
			],
			'createChannelPropertyNotUnique' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions',
				'Bearer ' . self::VALID_TOKEN,
				file_get_contents(
					__DIR__ . '/../../../fixtures/Controllers/requests/actions.createChannelProperty.unique.json',
				),
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				__DIR__ . '/../../../fixtures/Controllers/responses/actions.createChannelProperty.unique.json',
			],
			'missingRequired' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions',
				'Bearer ' . self::VALID_TOKEN,
				file_get_contents(
					__DIR__ . '/../../../fixtures/Controllers/requests/actions.create.missing.required.json',
				),
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				__DIR__ . '/../../../fixtures/Controllers/responses/actions.create.missing.required.json',
			],
			'unknownTrigger' => [
				'/v1/triggers/74e40f3e-84cb-4e0c-b3b3-fbf8246e0888/actions',
				'Bearer ' . self::VALID_TOKEN,
				file_get_contents(
					__DIR__ . '/../../../fixtures/Controllers/requests/actions.createChannelProperty.json',
				),
				StatusCodeInterface::STATUS_NOT_FOUND,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/notFound.json',
			],
			'invalidType' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions',
				'Bearer ' . self::VALID_TOKEN,
				file_get_contents(__DIR__ . '/../../../fixtures/Controllers/requests/actions.create.invalidType.json'),
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/invalid.type.json',
			],
			'missingToken' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions',
				null,
				file_get_contents(
					__DIR__ . '/../../../fixtures/Controllers/requests/actions.createChannelProperty.unique.json',
				),
				StatusCodeInterface::STATUS_FORBIDDEN,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/forbidden.json',
			],
			'emptyToken' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions',
				'',
				file_get_contents(
					__DIR__ . '/../../../fixtures/Controllers/requests/actions.createChannelProperty.unique.json',
				),
				StatusCodeInterface::STATUS_FORBIDDEN,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/forbidden.json',
			],
			'invalidToken' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions',
				'Bearer ' . self::INVALID_TOKEN,
				file_get_contents(
					__DIR__ . '/../../../fixtures/Controllers/requests/actions.createChannelProperty.unique.json',
				),
				StatusCodeInterface::STATUS_UNAUTHORIZED,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/unauthorized.json',
			],
			'expiredToken' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions',
				'Bearer ' . self::EXPIRED_TOKEN,
				file_get_contents(
					__DIR__ . '/../../../fixtures/Controllers/requests/actions.createChannelProperty.unique.json',
				),
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
	 * @dataProvider actionsUpdate
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
	 * @return Array<string, Array<bool|string|int|null>>
	 */
	public function actionsUpdate(): array
	{
		return [
			// Valid responses
			//////////////////
			'update' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions/4aa84028-d8b7-4128-95b2-295763634aa4',
				'Bearer ' . self::VALID_TOKEN,
				file_get_contents(__DIR__ . '/../../../fixtures/Controllers/requests/actions.update.json'),
				StatusCodeInterface::STATUS_OK,
				__DIR__ . '/../../../fixtures/Controllers/responses/actions.update.json',
			],

			// Invalid responses
			////////////////////
			'notAllowed' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions/4aa84028-d8b7-4128-95b2-295763634aa4',
				'Bearer ' . self::VALID_TOKEN_USER,
				file_get_contents(__DIR__ . '/../../../fixtures/Controllers/requests/actions.update.json'),
				StatusCodeInterface::STATUS_FORBIDDEN,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/forbidden.json',
			],
			'unknownTrigger' => [
				'/v1/triggers/74e40f3e-84cb-4e0c-b3b3-fbf8246e0888/actions/4aa84028-d8b7-4128-95b2-295763634aa4',
				'Bearer ' . self::VALID_TOKEN,
				file_get_contents(__DIR__ . '/../../../fixtures/Controllers/requests/actions.update.json'),
				StatusCodeInterface::STATUS_NOT_FOUND,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/notFound.json',
			],
			'unknownAction' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions/74e40f3e-84cb-4e0c-b3b3-fbf8246e0888',
				'Bearer ' . self::VALID_TOKEN,
				file_get_contents(__DIR__ . '/../../../fixtures/Controllers/requests/actions.update.json'),
				StatusCodeInterface::STATUS_NOT_FOUND,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/notFound.json',
			],
			'invalidType' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions/4aa84028-d8b7-4128-95b2-295763634aa4',
				'Bearer ' . self::VALID_TOKEN,
				file_get_contents(__DIR__ . '/../../../fixtures/Controllers/requests/actions.update.invalidType.json'),
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/invalid.type.json',
			],
			'idMismatch' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions/4aa84028-d8b7-4128-95b2-295763634aa4',
				'Bearer ' . self::VALID_TOKEN,
				file_get_contents(__DIR__ . '/../../../fixtures/Controllers/requests/actions.update.idMismatch.json'),
				StatusCodeInterface::STATUS_BAD_REQUEST,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/invalid.identifier.json',
			],
			'missingToken' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions/4aa84028-d8b7-4128-95b2-295763634aa4',
				null,
				file_get_contents(__DIR__ . '/../../../fixtures/Controllers/requests/actions.update.json'),
				StatusCodeInterface::STATUS_FORBIDDEN,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/forbidden.json',
			],
			'emptyToken' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions/4aa84028-d8b7-4128-95b2-295763634aa4',
				'',
				file_get_contents(__DIR__ . '/../../../fixtures/Controllers/requests/actions.update.json'),
				StatusCodeInterface::STATUS_FORBIDDEN,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/forbidden.json',
			],
			'invalidToken' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions/4aa84028-d8b7-4128-95b2-295763634aa4',
				'Bearer ' . self::INVALID_TOKEN,
				file_get_contents(__DIR__ . '/../../../fixtures/Controllers/requests/actions.update.json'),
				StatusCodeInterface::STATUS_UNAUTHORIZED,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/unauthorized.json',
			],
			'expiredToken' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions/4aa84028-d8b7-4128-95b2-295763634aa4',
				'Bearer ' . self::EXPIRED_TOKEN,
				file_get_contents(__DIR__ . '/../../../fixtures/Controllers/requests/actions.update.json'),
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
	 * @dataProvider actionsDelete
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
	 * @return Array<string, Array<string|int|null>>
	 */
	public function actionsDelete(): array
	{
		return [
			// Valid responses
			//////////////////
			'delete' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions/4aa84028-d8b7-4128-95b2-295763634aa4',
				'Bearer ' . self::VALID_TOKEN,
				StatusCodeInterface::STATUS_NO_CONTENT,
				__DIR__ . '/../../../fixtures/Controllers/responses/actions.delete.json',
			],

			// Invalid responses
			////////////////////
			'notAllowed' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions/4aa84028-d8b7-4128-95b2-295763634aa4',
				'Bearer ' . self::VALID_TOKEN_USER,
				StatusCodeInterface::STATUS_FORBIDDEN,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/forbidden.json',
			],
			'deleteUnknown' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions/74e40f3e-84cb-4e0c-b3b3-fbf8246e0888',
				'Bearer ' . self::VALID_TOKEN,
				StatusCodeInterface::STATUS_NOT_FOUND,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/notFound.json',
			],
			'unknownTrigger' => [
				'/v1/triggers/74e40f3e-84cb-4e0c-b3b3-fbf8246e0888/actions/4aa84028-d8b7-4128-95b2-295763634aa4',
				'Bearer ' . self::VALID_TOKEN,
				StatusCodeInterface::STATUS_NOT_FOUND,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/notFound.json',
			],
			'missingToken' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions/4aa84028-d8b7-4128-95b2-295763634aa4',
				null,
				StatusCodeInterface::STATUS_FORBIDDEN,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/forbidden.json',
			],
			'emptyToken' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions/4aa84028-d8b7-4128-95b2-295763634aa4',
				'',
				StatusCodeInterface::STATUS_FORBIDDEN,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/forbidden.json',
			],
			'invalidToken' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions/4aa84028-d8b7-4128-95b2-295763634aa4',
				'Bearer ' . self::INVALID_TOKEN,
				StatusCodeInterface::STATUS_UNAUTHORIZED,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/unauthorized.json',
			],
			'expiredToken' => [
				'/v1/triggers/c64ba1c4-0eda-4cab-87a0-4d634f7b67f4/actions/4aa84028-d8b7-4128-95b2-295763634aa4',
				'Bearer ' . self::EXPIRED_TOKEN,
				StatusCodeInterface::STATUS_UNAUTHORIZED,
				__DIR__ . '/../../../fixtures/Controllers/responses/generic/unauthorized.json',
			],
		];
	}

}
