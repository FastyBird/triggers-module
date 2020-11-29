<?php declare(strict_types = 1);

/**
 * RouterFactory.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     Router
 * @since          0.1.0
 *
 * @date           04.04.20
 */

namespace FastyBird\TriggersModule\Router;

use FastyBird\SimpleAuth\Middleware as SimpleAuthMiddleware;
use FastyBird\TriggersModule\Controllers;
use FastyBird\TriggersModule\Middleware;
use IPub\SlimRouter\Routing;
use Psr\Http\Message\ResponseFactoryInterface;

/**
 * Module router configuration
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Router
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
class Router extends Routing\Router
{

	public const URL_ITEM_ID = 'id';

	public const URL_TRIGGER_ID = 'trigger';

	public const RELATION_ENTITY = 'relationEntity';

	/** @var Controllers\TriggersV1Controller */
	private $triggersV1Controller;

	/** @var Controllers\ActionsV1Controller */
	private $actionsV1Controller;

	/** @var Controllers\NotificationsV1Controller */
	private $notificationsV1Controller;

	/** @var Controllers\ConditionsV1Controller */
	private $conditionsV1Controller;

	/** @var Middleware\AccessMiddleware */
	private $devicesAccessControlMiddleware;

	/** @var SimpleAuthMiddleware\AccessMiddleware */
	private $accessControlMiddleware;

	/** @var SimpleAuthMiddleware\UserMiddleware */
	private $userMiddleware;

	public function __construct(
		Controllers\TriggersV1Controller $triggersV1Controller,
		Controllers\ActionsV1Controller $actionsV1Controller,
		Controllers\NotificationsV1Controller $notificationsV1Controller,
		Controllers\ConditionsV1Controller $conditionsV1Controller,
		Middleware\AccessMiddleware $devicesAccessControlMiddleware,
		SimpleAuthMiddleware\AccessMiddleware $accessControlMiddleware,
		SimpleAuthMiddleware\UserMiddleware $userMiddleware,
		?ResponseFactoryInterface $responseFactory = null
	) {
		parent::__construct($responseFactory, null);

		$this->triggersV1Controller = $triggersV1Controller;
		$this->actionsV1Controller = $actionsV1Controller;
		$this->notificationsV1Controller = $notificationsV1Controller;
		$this->conditionsV1Controller = $conditionsV1Controller;

		$this->devicesAccessControlMiddleware = $devicesAccessControlMiddleware;
		$this->accessControlMiddleware = $accessControlMiddleware;
		$this->userMiddleware = $userMiddleware;
	}

	/**
	 * @return void
	 */
	public function registerRoutes(): void
	{
		$routes = $this->group('/v1', function (Routing\RouteCollector $group): void {
			$group->group('/triggers', function (Routing\RouteCollector $group): void {
				/**
				 * TRIGGERS
				 */
				$route = $group->get('', [$this->triggersV1Controller, 'index']);
				$route->setName('triggers');

				$route = $group->get('/{' . self::URL_ITEM_ID . '}', [$this->triggersV1Controller, 'read']);
				$route->setName('trigger');

				$group->post('', [$this->triggersV1Controller, 'create']);

				$group->patch('/{' . self::URL_ITEM_ID . '}', [$this->triggersV1Controller, 'update']);

				$group->delete('/{' . self::URL_ITEM_ID . '}', [$this->triggersV1Controller, 'delete']);

				$route = $group->get('/{' . self::URL_ITEM_ID . '}/relationships/{' . self::RELATION_ENTITY . '}', [$this->triggersV1Controller, 'readRelationship']);
				$route->setName('trigger.relationship');
			});

			$group->group('/triggers/{' . self::URL_TRIGGER_ID . '}', function (Routing\RouteCollector $group): void {
				$group->group('/actions', function (Routing\RouteCollector $group): void {
					/**
					 * ACTIONS
					 */
					$route = $group->get('', [$this->actionsV1Controller, 'index']);
					$route->setName('trigger.actions');

					$route = $group->get('/{' . self::URL_ITEM_ID . '}', [$this->actionsV1Controller, 'read']);
					$route->setName('trigger.action');

					$group->post('', [$this->actionsV1Controller, 'create']);

					$group->patch('/{' . self::URL_ITEM_ID . '}', [$this->actionsV1Controller, 'update']);

					$group->delete('/{' . self::URL_ITEM_ID . '}', [$this->actionsV1Controller, 'delete']);

					$route = $group->get('/{' . self::URL_ITEM_ID . '}/relationships/{' . self::RELATION_ENTITY . '}', [$this->actionsV1Controller, 'readRelationship']);
					$route->setName('trigger.action.relationship');
				});

				$group->group('/notifications', function (Routing\RouteCollector $group): void {
					/**
					 * NOTIFICATIONS
					 */
					$route = $group->get('', [$this->notificationsV1Controller, 'index']);
					$route->setName('trigger.notifications');

					$route = $group->get('/{' . self::URL_ITEM_ID . '}', [$this->notificationsV1Controller, 'read']);
					$route->setName('trigger.notification');

					$group->post('', [$this->notificationsV1Controller, 'create']);

					$group->patch('/{' . self::URL_ITEM_ID . '}', [$this->notificationsV1Controller, 'update']);

					$group->delete('/{' . self::URL_ITEM_ID . '}', [$this->notificationsV1Controller, 'delete']);

					$route = $group->get('/{' . self::URL_ITEM_ID . '}/relationships/{' . self::RELATION_ENTITY . '}', [$this->notificationsV1Controller, 'readRelationship']);
					$route->setName('trigger.notification.relationship');
				});

				$group->group('/conditions', function (Routing\RouteCollector $group): void {
					/**
					 * CONDITIONS
					 */
					$route = $group->get('', [$this->conditionsV1Controller, 'index']);
					$route->setName('trigger.conditions');

					$route = $group->get('/{' . self::URL_ITEM_ID . '}', [$this->conditionsV1Controller, 'read']);
					$route->setName('trigger.condition');

					$group->post('', [$this->conditionsV1Controller, 'create']);

					$group->patch('/{' . self::URL_ITEM_ID . '}', [$this->conditionsV1Controller, 'update']);

					$group->delete('/{' . self::URL_ITEM_ID . '}', [$this->conditionsV1Controller, 'delete']);

					$route = $group->get('/{' . self::URL_ITEM_ID . '}/relationships/{' . self::RELATION_ENTITY . '}', [$this->conditionsV1Controller, 'readRelationship']);
					$route->setName('trigger.condition.relationship');
				});
			});
		});

		$routes->addMiddleware($this->accessControlMiddleware);
		$routes->addMiddleware($this->userMiddleware);
		$routes->addMiddleware($this->devicesAccessControlMiddleware);
	}

}
