<?php declare(strict_types = 1);

/**
 * ConditionsV1Controller.php
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
 * Triggers conditions controller
 *
 * @package         FastyBird:TriggersModule!
 * @subpackage      Controllers
 *
 * @author          Adam Kadlec <adam.kadlec@fastybird.com>
 *
 * @Secured
 * @Secured\User(loggedIn)
 */
final class ConditionsV1Controller extends BaseV1Controller
{

	use Controllers\Finders\TTriggerFinder;

	/** @var Models\Triggers\ITriggerRepository */
	protected Models\Triggers\ITriggerRepository $triggerRepository;

	/** @var string */
	protected string $translationDomain = 'triggers-module.conditions';

	/** @var Models\Conditions\IConditionRepository */
	private Models\Conditions\IConditionRepository $conditionRepository;

	/** @var Models\Conditions\IConditionsManager */
	private Models\Conditions\IConditionsManager $conditionsManager;

	/** @var Hydrators\Conditions\DevicePropertyConditionHydrator */
	private Hydrators\Conditions\DevicePropertyConditionHydrator $devicePropertyConditionHydrator;

	/** @var Hydrators\Conditions\ChannelPropertyConditionHydrator */
	private Hydrators\Conditions\ChannelPropertyConditionHydrator $channelPropertyConditionHydrator;

	/** @var Hydrators\Conditions\TimeConditionHydrator */
	private Hydrators\Conditions\TimeConditionHydrator $timeConditionHydrator;

	public function __construct(
		Models\Triggers\ITriggerRepository $triggerRepository,
		Models\Conditions\IConditionRepository $conditionRepository,
		Models\Conditions\IConditionsManager $conditionsManager,
		Hydrators\Conditions\DevicePropertyConditionHydrator $devicePropertyConditionHydrator,
		Hydrators\Conditions\ChannelPropertyConditionHydrator $channelPropertyConditionHydrator,
		Hydrators\Conditions\TimeConditionHydrator $timeConditionHydrator
	) {
		$this->triggerRepository = $triggerRepository;
		$this->conditionRepository = $conditionRepository;
		$this->conditionsManager = $conditionsManager;

		$this->devicePropertyConditionHydrator = $devicePropertyConditionHydrator;
		$this->channelPropertyConditionHydrator = $channelPropertyConditionHydrator;
		$this->timeConditionHydrator = $timeConditionHydrator;
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
		$trigger = $this->findTrigger($request->getAttribute(Router\Routes::URL_TRIGGER_ID));

		if (!$trigger instanceof Entities\Triggers\IAutomaticTrigger) {
			throw new JsonApiExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_NOT_FOUND,
				$this->translator->translate('//triggers-module.base.messages.notFound.heading'),
				$this->translator->translate('//triggers-module.base.messages.notFound.message')
			);
		}

		$findQuery = new Queries\FindConditionsQuery();
		$findQuery->forTrigger($trigger);

		$rows = $this->conditionRepository->getResultSet($findQuery);

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
		$trigger = $this->findTrigger($request->getAttribute(Router\Routes::URL_TRIGGER_ID));

		if (!$trigger instanceof Entities\Triggers\IAutomaticTrigger) {
			throw new JsonApiExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_NOT_FOUND,
				$this->translator->translate('//triggers-module.base.messages.notFound.heading'),
				$this->translator->translate('//triggers-module.base.messages.notFound.message')
			);
		}

		// & condition
		$condition = $this->findCondition($request->getAttribute(Router\Routes::URL_ITEM_ID), $trigger);

		return $response
			->withEntity(WebServerHttp\ScalarEntity::from($condition));
	}

	/**
	 * @param string $id
	 * @param Entities\Triggers\ITrigger $trigger
	 *
	 * @return Entities\Conditions\ICondition
	 *
	 * @throws JsonApiExceptions\IJsonApiException
	 */
	protected function findCondition(
		string $id,
		Entities\Triggers\ITrigger $trigger
	): Entities\Conditions\ICondition {
		try {
			$findQuery = new Queries\FindConditionsQuery();
			$findQuery->byId(Uuid\Uuid::fromString($id));
			$findQuery->forTrigger($trigger);

			$condition = $this->conditionRepository->findOneBy($findQuery);

			if ($condition === null) {
				throw new JsonApiExceptions\JsonApiErrorException(
					StatusCodeInterface::STATUS_NOT_FOUND,
					$this->translator->translate('//triggers-module.base.messages.notFound.heading'),
					$this->translator->translate('//triggers-module.base.messages.notFound.message')
				);
			}

		} catch (Uuid\Exception\InvalidUuidStringException $ex) {
			throw new JsonApiExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_NOT_FOUND,
				$this->translator->translate('//triggers-module.base.messages.notFound.heading'),
				$this->translator->translate('//triggers-module.base.messages.notFound.message')
			);
		}

		return $condition;
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
		$trigger = $this->findTrigger($request->getAttribute(Router\Routes::URL_TRIGGER_ID));

		if ($trigger instanceof Entities\Triggers\IAutomaticTrigger) {
			$document = $this->createDocument($request);

			try {
				// Start transaction connection to the database
				$this->getOrmConnection()->beginTransaction();

				if ($document->getResource()->getType() === Schemas\Conditions\DevicePropertyConditionSchema::SCHEMA_TYPE) {
					$condition = $this->conditionsManager->create($this->devicePropertyConditionHydrator->hydrate($document));

				} elseif ($document->getResource()->getType() === Schemas\Conditions\ChannelPropertyConditionSchema::SCHEMA_TYPE) {
					$condition = $this->conditionsManager->create($this->channelPropertyConditionHydrator->hydrate($document));

				} elseif ($document->getResource()->getType() === Schemas\Conditions\TimeConditionSchema::SCHEMA_TYPE) {
					$condition = $this->conditionsManager->create($this->timeConditionHydrator->hydrate($document));

				} else {
					throw new JsonApiExceptions\JsonApiErrorException(
						StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
						$this->translator->translate('//triggers-module.base.messages.invalidType.heading'),
						$this->translator->translate('//triggers-module.base.messages.invalidType.message'),
						[
							'pointer' => '/data/type',
						]
					);
				}

				// Commit all changes into database
				$this->getOrmConnection()->commit();

			} catch (DoctrineCrudExceptions\EntityCreationException $ex) {
				throw new JsonApiExceptions\JsonApiErrorException(
					StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
					$this->translator->translate('//triggers-module.base.messages.missingAttribute.heading'),
					$this->translator->translate('//triggers-module.base.messages.missingAttribute.message'),
					[
						'pointer' => 'data/attributes/' . $ex->getField(),
					]
				);

			} catch (JsonApiExceptions\IJsonApiException $ex) {
				throw $ex;

			} catch (Exceptions\UniqueConditionConstraint $ex) {
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
						$this->translator->translate('//triggers-module.base.messages.uniqueIdentifier.heading'),
						$this->translator->translate('//triggers-module.base.messages.uniqueIdentifier.message'),
						[
							'pointer' => '/data/id',
						]
					);

				} elseif (preg_match("%key '(?P<key>.+)_unique'%", $ex->getMessage(), $match) === 1) {
					$columnParts = explode('.', $match['key']);
					$columnKey = end($columnParts);

					if (is_string($columnKey) && Utils\Strings::startsWith($columnKey, 'condition_')) {
						throw new JsonApiExceptions\JsonApiErrorException(
							StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
							$this->translator->translate('//triggers-module.base.messages.uniqueAttribute.heading'),
							$this->translator->translate('//triggers-module.base.messages.uniqueAttribute.message'),
							[
								'pointer' => '/data/attributes/' . Utils\Strings::substring($columnKey, 10),
							]
						);
					}
				}

				throw new JsonApiExceptions\JsonApiErrorException(
					StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
					$this->translator->translate('//triggers-module.base.messages.uniqueAttribute.heading'),
					$this->translator->translate('//triggers-module.base.messages.uniqueAttribute.message')
				);

			} catch (Throwable $ex) {
				// Log catched exception
				$this->logger->error('[FB:TRIGGERS_NODE:CONTROLLER] ' . $ex->getMessage(), [
					'exception' => [
						'message' => $ex->getMessage(),
						'code'    => $ex->getCode(),
					],
				]);

				throw new JsonApiExceptions\JsonApiErrorException(
					StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
					$this->translator->translate('//triggers-module.base.messages.notCreated.heading'),
					$this->translator->translate('//triggers-module.base.messages.notCreated.message')
				);

			} finally {
				// Revert all changes when error occur
				if ($this->getOrmConnection()->isTransactionActive()) {
					$this->getOrmConnection()->rollBack();
				}
			}

			/** @var WebServerHttp\Response $response */
			$response = $response
				->withEntity(WebServerHttp\ScalarEntity::from($condition))
				->withStatus(StatusCodeInterface::STATUS_CREATED);

			return $response;
		}

		throw new JsonApiExceptions\JsonApiErrorException(
			StatusCodeInterface::STATUS_NOT_FOUND,
			$this->translator->translate('//triggers-module.base.messages.notFound.heading'),
			$this->translator->translate('//triggers-module.base.messages.notFound.message')
		);
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
		$trigger = $this->findTrigger($request->getAttribute(Router\Routes::URL_TRIGGER_ID));

		// & condition
		$condition = $this->findCondition($request->getAttribute(Router\Routes::URL_ITEM_ID), $trigger);

		$document = $this->createDocument($request);

		$this->validateIdentifier($request, $document);

		try {
			// Start transaction connection to the database
			$this->getOrmConnection()->beginTransaction();

			if ($document->getResource()->getType() === Schemas\Conditions\DevicePropertyConditionSchema::SCHEMA_TYPE) {
				$condition = $this->conditionsManager->update(
					$condition,
					$this->devicePropertyConditionHydrator->hydrate($document, $condition)
				);

			} elseif ($document->getResource()->getType() === Schemas\Conditions\ChannelPropertyConditionSchema::SCHEMA_TYPE) {
				$condition = $this->conditionsManager->update(
					$condition,
					$this->channelPropertyConditionHydrator->hydrate($document, $condition)
				);

			} elseif ($document->getResource()->getType() === Schemas\Conditions\TimeConditionSchema::SCHEMA_TYPE) {
				$condition = $this->conditionsManager->update(
					$condition,
					$this->timeConditionHydrator->hydrate($document, $condition)
				);

			} else {
				throw new JsonApiExceptions\JsonApiErrorException(
					StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
					$this->translator->translate('//triggers-module.base.messages.invalidType.heading'),
					$this->translator->translate('//triggers-module.base.messages.invalidType.message'),
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
			$this->logger->error('[FB:TRIGGERS_NODE:CONTROLLER] ' . $ex->getMessage(), [
				'exception' => [
					'message' => $ex->getMessage(),
					'code'    => $ex->getCode(),
				],
			]);

			throw new JsonApiExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				$this->translator->translate('//triggers-module.base.messages.notUpdated.heading'),
				$this->translator->translate('//triggers-module.base.messages.notUpdated.message')
			);

		} finally {
			// Revert all changes when error occur
			if ($this->getOrmConnection()->isTransactionActive()) {
				$this->getOrmConnection()->rollBack();
			}
		}

		return $response
			->withEntity(WebServerHttp\ScalarEntity::from($condition));
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
		$trigger = $this->findTrigger($request->getAttribute(Router\Routes::URL_TRIGGER_ID));

		// & condition
		$condition = $this->findCondition($request->getAttribute(Router\Routes::URL_ITEM_ID), $trigger);

		try {
			// Start transaction connection to the database
			$this->getOrmConnection()->beginTransaction();

			$this->conditionsManager->delete($condition);

			// Commit all changes into database
			$this->getOrmConnection()->commit();

		} catch (Throwable $ex) {
			// Log catched exception
			$this->logger->error('[FB:TRIGGERS_NODE:CONTROLLER] ' . $ex->getMessage(), [
				'exception' => [
					'message' => $ex->getMessage(),
					'code'    => $ex->getCode(),
				],
			]);

			throw new JsonApiExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				$this->translator->translate('//triggers-module.base.messages.notUpdated.heading'),
				$this->translator->translate('//triggers-module.base.messages.notDeleted.message')
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
		$trigger = $this->findTrigger($request->getAttribute(Router\Routes::URL_TRIGGER_ID));

		// & condition
		$condition = $this->findCondition($request->getAttribute(Router\Routes::URL_ITEM_ID), $trigger);

		$relationEntity = strtolower($request->getAttribute(Router\Routes::RELATION_ENTITY));

		if ($relationEntity === Schemas\Conditions\ConditionSchema::RELATIONSHIPS_TRIGGER) {
			return $response
				->withEntity(WebServerHttp\ScalarEntity::from($condition->getTrigger()));
		}

		return parent::readRelationship($request, $response);
	}

}
