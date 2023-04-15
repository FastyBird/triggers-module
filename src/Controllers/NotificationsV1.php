<?php declare(strict_types = 1);

/**
 * NotificationsV1.php
 *
 * @license        More in LICENSE.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     Controllers
 * @since          1.0.0
 *
 * @date           04.04.20
 */

namespace FastyBird\Module\Triggers\Controllers;

use Doctrine;
use Exception;
use FastyBird\JsonApi\Exceptions as JsonApiExceptions;
use FastyBird\Library\Bootstrap\Helpers as BootstrapHelpers;
use FastyBird\Module\Triggers\Controllers;
use FastyBird\Module\Triggers\Entities;
use FastyBird\Module\Triggers\Exceptions;
use FastyBird\Module\Triggers\Models;
use FastyBird\Module\Triggers\Queries;
use FastyBird\Module\Triggers\Router;
use FastyBird\Module\Triggers\Schemas;
use FastyBird\Module\Triggers\Utilities;
use Fig\Http\Message\StatusCodeInterface;
use InvalidArgumentException;
use IPub\DoctrineCrud\Exceptions as DoctrineCrudExceptions;
use Nette\Utils;
use Psr\Http\Message;
use Ramsey\Uuid;
use Throwable;
use function end;
use function explode;
use function preg_match;
use function strtolower;
use function strval;

/**
 * Triggers notifications controller
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Controllers
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 *
 * @Secured
 * @Secured\User(loggedIn)
 */
final class NotificationsV1 extends BaseV1
{

	use Controllers\Finders\TTrigger;

	public function __construct(
		private readonly Models\Triggers\TriggersRepository $triggersRepository,
		private readonly Models\Notifications\NotificationsRepository $notificationsRepository,
		private readonly Models\Notifications\NotificationsManager $notificationsManager,
	)
	{
	}

	/**
	 * @throws Exceptions\InvalidState
	 * @throws JsonApiExceptions\JsonApi
	 */
	public function index(
		Message\ServerRequestInterface $request,
		Message\ResponseInterface $response,
	): Message\ResponseInterface
	{
		// At first, try to load trigger
		$trigger = $this->findTrigger(strval($request->getAttribute(Router\Routes::URL_TRIGGER_ID)));

		$findQuery = new Queries\FindNotifications();
		$findQuery->forTrigger($trigger);

		$rows = $this->notificationsRepository->getResultSet($findQuery);

		// @phpstan-ignore-next-line
		return $this->buildResponse($request, $response, $rows);
	}

	/**
	 * @throws Exception
	 * @throws Exceptions\InvalidState
	 * @throws JsonApiExceptions\JsonApi
	 */
	public function read(
		Message\ServerRequestInterface $request,
		Message\ResponseInterface $response,
	): Message\ResponseInterface
	{
		// At first, try to load trigger
		$trigger = $this->findTrigger(strval($request->getAttribute(Router\Routes::URL_TRIGGER_ID)));

		// & notification
		$notification = $this->findNotification(strval($request->getAttribute(Router\Routes::URL_ITEM_ID)), $trigger);

		return $this->buildResponse($request, $response, $notification);
	}

	/**
	 * @throws Exception
	 * @throws Exceptions\InvalidState
	 * @throws InvalidArgumentException
	 * @throws JsonApiExceptions\JsonApi
	 * @throws Doctrine\DBAL\ConnectionException
	 * @throws Doctrine\DBAL\Exception
	 *
	 * @Secured
	 * @Secured\Role(manager,administrator)
	 */
	public function create(
		Message\ServerRequestInterface $request,
		Message\ResponseInterface $response,
	): Message\ResponseInterface
	{
		// At first, try to load trigger
		$this->findTrigger(strval($request->getAttribute(Router\Routes::URL_TRIGGER_ID)));

		$document = $this->createDocument($request);

		$hydrator = $this->hydratorsContainer->findHydrator($document);

		if ($hydrator !== null) {
			try {
				// Start transaction connection to the database
				$this->getOrmConnection()->beginTransaction();

				$notification = $this->notificationsManager->create($hydrator->hydrate($document));

				// Commit all changes into database
				$this->getOrmConnection()->commit();

			} catch (JsonApiExceptions\JsonApi $ex) {
				throw $ex;
			} catch (DoctrineCrudExceptions\MissingRequiredFieldException $ex) {
				throw new JsonApiExceptions\JsonApiError(
					StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
					$this->translator->translate('//triggers-module.base.messages.missingAttribute.heading'),
					$this->translator->translate('//triggers-module.base.messages.missingAttribute.message'),
					[
						'pointer' => '/data/attributes/' . Utilities\Api::fieldToJsonApi($ex->getField()),
					],
				);
			} catch (DoctrineCrudExceptions\EntityCreationException $ex) {
				throw new JsonApiExceptions\JsonApiError(
					StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
					$this->translator->translate('//triggers-module.base.messages.missingAttribute.heading'),
					$this->translator->translate('//triggers-module.base.messages.missingAttribute.message'),
					[
						'pointer' => '/data/attributes/' . Utilities\Api::fieldToJsonApi($ex->getField()),
					],
				);
			} catch (Exceptions\UniqueNotificationNumberConstraint) {
				throw new JsonApiExceptions\JsonApiError(
					StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
					$this->translator->translate('//triggers-module.notifications.messages.phoneNotUnique.heading'),
					$this->translator->translate('//triggers-module.notifications.messages.phoneNotUnique.message'),
					[
						'pointer' => '/data/attributes/phone',
					],
				);
			} catch (Exceptions\UniqueNotificationEmailConstraint) {
				throw new JsonApiExceptions\JsonApiError(
					StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
					$this->translator->translate('//triggers-module.notifications.messages.emailNotUnique.heading'),
					$this->translator->translate('//triggers-module.notifications.messages.emailNotUnique.message'),
					[
						'pointer' => '/data/attributes/email',
					],
				);
			} catch (Doctrine\DBAL\Exception\UniqueConstraintViolationException $ex) {
				if (preg_match("%PRIMARY'%", $ex->getMessage(), $match) === 1) {
					throw new JsonApiExceptions\JsonApiError(
						StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
						$this->translator->translate('//triggers-module.base.messages.uniqueIdentifier.heading'),
						$this->translator->translate('//triggers-module.base.messages.uniqueIdentifier.message'),
						[
							'pointer' => '/data/id',
						],
					);
				} elseif (preg_match("%key '(?P<key>.+)_unique'%", $ex->getMessage(), $match) === 1) {
					$columnParts = explode('.', $match['key']);
					$columnKey = end($columnParts);

					if (Utils\Strings::startsWith($columnKey, 'notification_')) {
						throw new JsonApiExceptions\JsonApiError(
							StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
							$this->translator->translate('//triggers-module.base.messages.uniqueAttribute.heading'),
							$this->translator->translate('//triggers-module.base.messages.uniqueAttribute.message'),
							[
								'pointer' => '/data/attributes/' . Utilities\Api::fieldToJsonApi(
									Utils\Strings::substring($columnKey, 13),
								),
							],
						);
					}
				}

				throw new JsonApiExceptions\JsonApiError(
					StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
					$this->translator->translate('//triggers-module.base.messages.uniqueAttribute.heading'),
					$this->translator->translate('//triggers-module.base.messages.uniqueAttribute.message'),
				);
			} catch (Throwable $ex) {
				// Log caught exception
				$this->logger->error('An unhandled error occurred', [
					'source' => 'triggers-module-notifications-controller',
					'type' => 'create',
					'exception' => BootstrapHelpers\Logger::buildException($ex),
				]);

				throw new JsonApiExceptions\JsonApiError(
					StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
					$this->translator->translate('//triggers-module.base.messages.notCreated.heading'),
					$this->translator->translate('//triggers-module.base.messages.notCreated.message'),
				);
			} finally {
				// Revert all changes when error occur
				if ($this->getOrmConnection()->isTransactionActive()) {
					$this->getOrmConnection()->rollBack();
				}
			}

			$response = $this->buildResponse($request, $response, $notification);

			return $response->withStatus(StatusCodeInterface::STATUS_CREATED);
		}

		throw new JsonApiExceptions\JsonApiError(
			StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
			$this->translator->translate('//triggers-module.base.messages.invalidType.heading'),
			$this->translator->translate('//triggers-module.base.messages.invalidType.message'),
			[
				'pointer' => '/data/type',
			],
		);
	}

	/**
	 * @throws Exception
	 * @throws Exceptions\InvalidState
	 * @throws InvalidArgumentException
	 * @throws JsonApiExceptions\JsonApi
	 * @throws Doctrine\DBAL\ConnectionException
	 * @throws Doctrine\DBAL\Exception
	 *
	 * @Secured
	 * @Secured\Role(manager,administrator)
	 */
	public function update(
		Message\ServerRequestInterface $request,
		Message\ResponseInterface $response,
	): Message\ResponseInterface
	{
		// At first, try to load trigger
		$trigger = $this->findTrigger(strval($request->getAttribute(Router\Routes::URL_TRIGGER_ID)));

		// & notification
		$notification = $this->findNotification(strval($request->getAttribute(Router\Routes::URL_ITEM_ID)), $trigger);

		$document = $this->createDocument($request);

		$this->validateIdentifier($request, $document);

		$hydrator = $this->hydratorsContainer->findHydrator($document);

		if ($hydrator !== null) {
			try {
				// Start transaction connection to the database
				$this->getOrmConnection()->beginTransaction();

				$notification = $this->notificationsManager->update(
					$notification,
					$hydrator->hydrate($document, $notification),
				);

				// Commit all changes into database
				$this->getOrmConnection()->commit();

			} catch (JsonApiExceptions\JsonApi $ex) {
				throw $ex;
			} catch (Throwable $ex) {
				// Log caught exception
				$this->logger->error('An unhandled error occurred', [
					'source' => 'triggers-module-notifications-controller',
					'type' => 'update',
					'exception' => BootstrapHelpers\Logger::buildException($ex),
				]);

				throw new JsonApiExceptions\JsonApiError(
					StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
					$this->translator->translate('//triggers-module.base.messages.notUpdated.heading'),
					$this->translator->translate('//triggers-module.base.messages.notUpdated.message'),
				);
			} finally {
				// Revert all changes when error occur
				if ($this->getOrmConnection()->isTransactionActive()) {
					$this->getOrmConnection()->rollBack();
				}
			}

			return $this->buildResponse($request, $response, $notification);
		}

		throw new JsonApiExceptions\JsonApiError(
			StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
			$this->translator->translate('//triggers-module.base.messages.invalidType.heading'),
			$this->translator->translate('//triggers-module.base.messages.invalidType.message'),
			[
				'pointer' => '/data/type',
			],
		);
	}

	/**
	 * @throws Exception
	 * @throws Exceptions\InvalidState
	 * @throws InvalidArgumentException
	 * @throws JsonApiExceptions\JsonApi
	 * @throws Doctrine\DBAL\ConnectionException
	 * @throws Doctrine\DBAL\Exception
	 *
	 * @Secured
	 * @Secured\Role(manager,administrator)
	 */
	public function delete(
		Message\ServerRequestInterface $request,
		Message\ResponseInterface $response,
	): Message\ResponseInterface
	{
		// At first, try to load trigger
		$trigger = $this->findTrigger(strval($request->getAttribute(Router\Routes::URL_TRIGGER_ID)));

		// & notification
		$notification = $this->findNotification(strval($request->getAttribute(Router\Routes::URL_ITEM_ID)), $trigger);

		try {
			// Start transaction connection to the database
			$this->getOrmConnection()->beginTransaction();

			$this->notificationsManager->delete($notification);

			// Commit all changes into database
			$this->getOrmConnection()->commit();

		} catch (Throwable $ex) {
			// Log caught exception
			$this->logger->error('An unhandled error occurred', [
				'source' => 'triggers-module-notifications-controller',
				'type' => 'delete',
				'exception' => BootstrapHelpers\Logger::buildException($ex),
			]);

			throw new JsonApiExceptions\JsonApiError(
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				$this->translator->translate('//triggers-module.base.messages.notUpdated.heading'),
				$this->translator->translate('//triggers-module.base.messages.notDeleted.message'),
			);
		} finally {
			// Revert all changes when error occur
			if ($this->getOrmConnection()->isTransactionActive()) {
				$this->getOrmConnection()->rollBack();
			}
		}

		return $response->withStatus(StatusCodeInterface::STATUS_NO_CONTENT);
	}

	/**
	 * @throws Exception
	 * @throws Exceptions\InvalidState
	 * @throws JsonApiExceptions\JsonApi
	 */
	public function readRelationship(
		Message\ServerRequestInterface $request,
		Message\ResponseInterface $response,
	): Message\ResponseInterface
	{
		// At first, try to load trigger
		$trigger = $this->findTrigger(strval($request->getAttribute(Router\Routes::URL_TRIGGER_ID)));

		// & notification
		$notification = $this->findNotification(strval($request->getAttribute(Router\Routes::URL_ITEM_ID)), $trigger);

		$relationEntity = strtolower(strval($request->getAttribute(Router\Routes::RELATION_ENTITY)));

		if ($relationEntity === Schemas\Notifications\Notification::RELATIONSHIPS_TRIGGER) {
			return $this->buildResponse($request, $response, $notification->getTrigger());
		}

		return parent::readRelationship($request, $response);
	}

	/**
	 * @throws Exceptions\InvalidState
	 * @throws JsonApiExceptions\JsonApi
	 */
	protected function findNotification(
		string $id,
		Entities\Triggers\Trigger $trigger,
	): Entities\Notifications\Notification
	{
		try {
			$findQuery = new Queries\FindNotifications();
			$findQuery->byId(Uuid\Uuid::fromString($id));
			$findQuery->forTrigger($trigger);

			$notification = $this->notificationsRepository->findOneBy($findQuery);

			if ($notification === null) {
				throw new JsonApiExceptions\JsonApiError(
					StatusCodeInterface::STATUS_NOT_FOUND,
					$this->translator->translate('//triggers-module.base.messages.notFound.heading'),
					$this->translator->translate('//triggers-module.base.messages.notFound.message'),
				);
			}
		} catch (Uuid\Exception\InvalidUuidStringException) {
			throw new JsonApiExceptions\JsonApiError(
				StatusCodeInterface::STATUS_NOT_FOUND,
				$this->translator->translate('//triggers-module.base.messages.notFound.heading'),
				$this->translator->translate('//triggers-module.base.messages.notFound.message'),
			);
		}

		return $notification;
	}

}
