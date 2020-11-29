<?php declare(strict_types = 1);

/**
 * ActionsV1Controller.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     Controllers
 * @since          0.1.0
 *
 * @date           04.04.20
 */

namespace FastyBird\TriggersModule\Controllers;

use Doctrine;
use FastyBird\JsonApi\Exceptions as JsonApiExceptions;
use FastyBird\TriggersModule\Controllers;
use FastyBird\TriggersModule\Entities;
use FastyBird\TriggersModule\Exceptions;
use FastyBird\TriggersModule\Hydrators;
use FastyBird\TriggersModule\Models;
use FastyBird\TriggersModule\Queries;
use FastyBird\TriggersModule\Router;
use FastyBird\TriggersModule\Schemas;
use FastyBird\WebServer\Http as WebServerHttp;
use Fig\Http\Message\StatusCodeInterface;
use IPub\DoctrineCrud\Exceptions as DoctrineCrudExceptions;
use Nette\Utils;
use Psr\Http\Message;
use Ramsey\Uuid;
use Throwable;

/**
 * Triggers actions controller
 *
 * @package         FastyBird:TriggersModule!
 * @subpackage      Controllers
 *
 * @author          Adam Kadlec <adam.kadlec@fastybird.com>
 *
 * @Secured
 * @Secured\User(loggedIn)
 */
final class ActionsV1Controller extends BaseV1Controller
{

	use Controllers\Finders\TTriggerFinder;

	/** @var Models\Triggers\ITriggerRepository */
	protected $triggerRepository;

	/** @var Models\Actions\IActionRepository */
	private $actionRepository;

	/** @var Models\Actions\IActionsManager */
	private $actionsManager;

	/** @var Hydrators\Actions\ChannelPropertyActionHydrator */
	private $channelPropertyActionHydrator;

	/** @var string */
	protected $translationDomain = 'module.actions';

	public function __construct(
		Models\Triggers\ITriggerRepository $triggerRepository,
		Models\Actions\IActionRepository $actionRepository,
		Models\Actions\IActionsManager $actionsManager,
		Hydrators\Actions\ChannelPropertyActionHydrator $channelPropertyActionHydrator
	) {
		$this->triggerRepository = $triggerRepository;
		$this->actionRepository = $actionRepository;
		$this->actionsManager = $actionsManager;

		$this->channelPropertyActionHydrator = $channelPropertyActionHydrator;
	}

	/**
	 * @param Message\ServerRequestInterface $request
	 * @param WebServerHttp\Response $response
	 *
	 * @return WebServerHttp\Response
	 *
	 * @throws JsonApiExceptions\IJsonApiException
	 */
	public function index(
		Message\ServerRequestInterface $request,
		WebServerHttp\Response $response
	): WebServerHttp\Response {
		// At first, try to load trigger
		$trigger = $this->findTrigger($request->getAttribute(Router\Router::URL_TRIGGER_ID));

		$findQuery = new Queries\FindActionsQuery();
		$findQuery->forTrigger($trigger);

		$rows = $this->actionRepository->getResultSet($findQuery);

		return $response
			->withEntity(WebServerHttp\ScalarEntity::from($rows));
	}

	/**
	 * @param Message\ServerRequestInterface $request
	 * @param WebServerHttp\Response $response
	 *
	 * @return WebServerHttp\Response
	 *
	 * @throws JsonApiExceptions\IJsonApiException
	 */
	public function read(
		Message\ServerRequestInterface $request,
		WebServerHttp\Response $response
	): WebServerHttp\Response {
		// At first, try to load trigger
		$trigger = $this->findTrigger($request->getAttribute(Router\Router::URL_TRIGGER_ID));

		// & action
		$action = $this->findAction($request->getAttribute(Router\Router::URL_ITEM_ID), $trigger);

		return $response
			->withEntity(WebServerHttp\ScalarEntity::from($action));
	}

	/**
	 * @param Message\ServerRequestInterface $request
	 * @param WebServerHttp\Response $response
	 *
	 * @return WebServerHttp\Response
	 *
	 * @throws JsonApiExceptions\IJsonApiException
	 * @throws Doctrine\DBAL\ConnectionException
	 *
	 * @Secured
	 * @Secured\Role(manager,administrator)
	 */
	public function create(
		Message\ServerRequestInterface $request,
		WebServerHttp\Response $response
	): WebServerHttp\Response {
		// At first, try to load trigger
		$trigger = $this->findTrigger($request->getAttribute(Router\Router::URL_TRIGGER_ID));

		$document = $this->createDocument($request);

		try {
			// Start transaction connection to the database
			$this->getOrmConnection()->beginTransaction();

			if ($document->getResource()->getType() === Schemas\Actions\ChannelPropertyActionSchema::SCHEMA_TYPE) {
				$action = $this->actionsManager->create($this->channelPropertyActionHydrator->hydrate($document));

			} else {
				throw new JsonApiExceptions\JsonApiErrorException(
					StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
					$this->translator->translate('//module.base.messages.invalidType.heading'),
					$this->translator->translate('//module.base.messages.invalidType.message'),
					[
						'pointer' => '/data/type',
					]
				);
			}

			// Commit all changes into database
			$this->getOrmConnection()->commit();

		} catch (JsonApiExceptions\IJsonApiException $ex) {
			throw $ex;

		} catch (DoctrineCrudExceptions\EntityCreationException $ex) {
			throw new JsonApiExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				$this->translator->translate('//module.base.messages.missingAttribute.heading'),
				$this->translator->translate('//module.base.messages.missingAttribute.message'),
				[
					'pointer' => 'data/attributes/' . $ex->getField(),
				]
			);

		} catch (Exceptions\UniqueActionConstraint $ex) {
			throw new JsonApiExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				$this->translator->translate('messages.propertyNotUnique.heading'),
				$this->translator->translate('messages.propertyNotUnique.message'),
				[
					'pointer' => '/data/relationships/property',
				]
			);

		} catch (Doctrine\DBAL\Exception\UniqueConstraintViolationException $ex) {
			if (preg_match("%PRIMARY'%", $ex->getMessage(), $match) === 1) {
				throw new JsonApiExceptions\JsonApiErrorException(
					StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
					$this->translator->translate('//module.base.messages.uniqueIdentifier.heading'),
					$this->translator->translate('//module.base.messages.uniqueIdentifier.message'),
					[
						'pointer' => '/data/id',
					]
				);

			} elseif (preg_match("%key '(?P<key>.+)_unique'%", $ex->getMessage(), $match) === 1) {
				$columnParts = explode('.', $match['key']);
				$columnKey = end($columnParts);

				if (is_string($columnKey) && Utils\Strings::startsWith($columnKey, 'action_')) {
					throw new JsonApiExceptions\JsonApiErrorException(
						StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
						$this->translator->translate('//module.base.messages.uniqueAttribute.heading'),
						$this->translator->translate('//module.base.messages.uniqueAttribute.message'),
						[
							'pointer' => '/data/attributes/' . Utils\Strings::substring($columnKey, 7),
						]
					);
				}
			}

			throw new JsonApiExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				$this->translator->translate('//module.base.messages.uniqueAttribute.heading'),
				$this->translator->translate('//module.base.messages.uniqueAttribute.message')
			);

		} catch (Throwable $ex) {
			// Log catched exception
			$this->logger->error('[CONTROLLER] ' . $ex->getMessage(), [
				'exception' => [
					'message' => $ex->getMessage(),
					'code'    => $ex->getCode(),
				],
			]);

			throw new JsonApiExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				$this->translator->translate('//module.base.messages.notCreated.heading'),
				$this->translator->translate('//module.base.messages.notCreated.message')
			);

		} finally {
			// Revert all changes when error occur
			if ($this->getOrmConnection()->isTransactionActive()) {
				$this->getOrmConnection()->rollBack();
			}
		}

		/** @var WebServerHttp\Response $response */
		$response = $response
			->withEntity(WebServerHttp\ScalarEntity::from($action))
			->withStatus(StatusCodeInterface::STATUS_CREATED);

		return $response;
	}

	/**
	 * @param Message\ServerRequestInterface $request
	 * @param WebServerHttp\Response $response
	 *
	 * @return WebServerHttp\Response
	 *
	 * @throws JsonApiExceptions\IJsonApiException
	 * @throws Doctrine\DBAL\ConnectionException
	 *
	 * @Secured
	 * @Secured\Role(manager,administrator)
	 */
	public function update(
		Message\ServerRequestInterface $request,
		WebServerHttp\Response $response
	): WebServerHttp\Response {
		// At first, try to load trigger
		$trigger = $this->findTrigger($request->getAttribute(Router\Router::URL_TRIGGER_ID));

		// & action
		$action = $this->findAction($request->getAttribute(Router\Router::URL_ITEM_ID), $trigger);

		$document = $this->createDocument($request);

		$this->validateIdentifier($request, $document);

		try {
			// Start transaction connection to the database
			$this->getOrmConnection()->beginTransaction();

			if ($document->getResource()->getType() === Schemas\Actions\ChannelPropertyActionSchema::SCHEMA_TYPE) {
				$action = $this->actionsManager->update(
					$action,
					$this->channelPropertyActionHydrator->hydrate($document, $action)
				);

			} else {
				throw new JsonApiExceptions\JsonApiErrorException(
					StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
					$this->translator->translate('//module.base.messages.invalidType.heading'),
					$this->translator->translate('//module.base.messages.invalidType.message'),
					[
						'pointer' => '/data/type',
					]
				);
			}

			// Commit all changes into database
			$this->getOrmConnection()->commit();

		} catch (JsonApiExceptions\IJsonApiException $ex) {
			throw $ex;

		} catch (Throwable $ex) {
			// Log catched exception
			$this->logger->error('[CONTROLLER] ' . $ex->getMessage(), [
				'exception' => [
					'message' => $ex->getMessage(),
					'code'    => $ex->getCode(),
				],
			]);

			throw new JsonApiExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				$this->translator->translate('//module.base.messages.notUpdated.heading'),
				$this->translator->translate('//module.base.messages.notUpdated.message')
			);

		} finally {
			// Revert all changes when error occur
			if ($this->getOrmConnection()->isTransactionActive()) {
				$this->getOrmConnection()->rollBack();
			}
		}

		return $response
			->withEntity(WebServerHttp\ScalarEntity::from($action));
	}

	/**
	 * @param Message\ServerRequestInterface $request
	 * @param WebServerHttp\Response $response
	 *
	 * @return WebServerHttp\Response
	 *
	 * @throws JsonApiExceptions\IJsonApiException
	 * @throws Doctrine\DBAL\ConnectionException
	 *
	 * @Secured
	 * @Secured\Role(manager,administrator)
	 */
	public function delete(
		Message\ServerRequestInterface $request,
		WebServerHttp\Response $response
	): WebServerHttp\Response {
		// At first, try to load trigger
		$trigger = $this->findTrigger($request->getAttribute(Router\Router::URL_TRIGGER_ID));

		// & action
		$action = $this->findAction($request->getAttribute(Router\Router::URL_ITEM_ID), $trigger);

		try {
			// Start transaction connection to the database
			$this->getOrmConnection()->beginTransaction();

			$this->actionsManager->delete($action);

			// Commit all changes into database
			$this->getOrmConnection()->commit();

		} catch (Throwable $ex) {
			// Log catched exception
			$this->logger->error('[CONTROLLER] ' . $ex->getMessage(), [
				'exception' => [
					'message' => $ex->getMessage(),
					'code'    => $ex->getCode(),
				],
			]);

			throw new JsonApiExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				$this->translator->translate('//module.base.messages.notUpdated.heading'),
				$this->translator->translate('//module.base.messages.notDeleted.message')
			);

		} finally {
			// Revert all changes when error occur
			if ($this->getOrmConnection()->isTransactionActive()) {
				$this->getOrmConnection()->rollBack();
			}
		}

		/** @var WebServerHttp\Response $response */
		$response = $response->withStatus(StatusCodeInterface::STATUS_NO_CONTENT);

		return $response;
	}

	/**
	 * @param Message\ServerRequestInterface $request
	 * @param WebServerHttp\Response $response
	 *
	 * @return WebServerHttp\Response
	 *
	 * @throws JsonApiExceptions\IJsonApiException
	 */
	public function readRelationship(
		Message\ServerRequestInterface $request,
		WebServerHttp\Response $response
	): WebServerHttp\Response {
		// At first, try to load trigger
		$trigger = $this->findTrigger($request->getAttribute(Router\Router::URL_TRIGGER_ID));

		// & action
		$action = $this->findAction($request->getAttribute(Router\Router::URL_ITEM_ID), $trigger);

		$relationEntity = strtolower($request->getAttribute(Router\Router::RELATION_ENTITY));

		if ($relationEntity === Schemas\Actions\ActionSchema::RELATIONSHIPS_TRIGGER) {
			return $response
				->withEntity(WebServerHttp\ScalarEntity::from($action->getTrigger()));
		}

		return parent::readRelationship($request, $response);
	}

	/**
	 * @param string $id
	 * @param Entities\Triggers\ITrigger $trigger
	 *
	 * @return Entities\Actions\IAction
	 *
	 * @throws JsonApiExceptions\IJsonApiException
	 */
	protected function findAction(
		string $id,
		Entities\Triggers\ITrigger $trigger
	): Entities\Actions\IAction {
		try {
			$findQuery = new Queries\FindActionsQuery();
			$findQuery->byId(Uuid\Uuid::fromString($id));
			$findQuery->forTrigger($trigger);

			$action = $this->actionRepository->findOneBy($findQuery);

			if ($action === null) {
				throw new JsonApiExceptions\JsonApiErrorException(
					StatusCodeInterface::STATUS_NOT_FOUND,
					$this->translator->translate('//module.base.messages.notFound.heading'),
					$this->translator->translate('//module.base.messages.notFound.message')
				);
			}

		} catch (Uuid\Exception\InvalidUuidStringException $ex) {
			throw new JsonApiExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_NOT_FOUND,
				$this->translator->translate('//module.base.messages.notFound.heading'),
				$this->translator->translate('//module.base.messages.notFound.message')
			);
		}

		return $action;
	}

}
