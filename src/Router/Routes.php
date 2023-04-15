<?php declare(strict_types = 1);

/**
 * Routes.php
 *
 * @license        More in LICENSE.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     Router
 * @since          1.0.0
 *
 * @date           04.04.20
 */

namespace FastyBird\Module\Triggers\Router;

use FastyBird\Library\Metadata;
use FastyBird\Module\Triggers;
use FastyBird\Module\Triggers\Controllers;
use FastyBird\Module\Triggers\Middleware;
use FastyBird\SimpleAuth\Middleware as SimpleAuthMiddleware;
use IPub\SlimRouter\Routing;

/**
 * Module router configuration
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Router
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
class Routes
{

	public const URL_ITEM_ID = 'id';

	public const URL_TRIGGER_ID = 'trigger';

	public const RELATION_ENTITY = 'relationEntity';

	public function __construct(
		private readonly bool $usePrefix,
		private readonly Controllers\TriggersV1 $triggersV1Controller,
		private readonly Controllers\ActionsV1 $actionsV1Controller,
		private readonly Controllers\NotificationsV1 $notificationsV1Controller,
		private readonly Controllers\ConditionsV1 $conditionsV1Controller,
		private readonly Controllers\TriggerControlsV1 $controlsV1Controller,
		private readonly Middleware\Access $triggersAccessControlMiddleware,
		private readonly SimpleAuthMiddleware\Access $accessControlMiddleware,
		private readonly SimpleAuthMiddleware\User $userMiddleware,
	)
	{
	}

	public function registerRoutes(Routing\IRouter $router): void
	{
		if ($this->usePrefix) {
			$routes = $router->group('/' . Metadata\Constants::MODULE_TRIGGERS_PREFIX, function (
				Routing\RouteCollector $group,
			): void {
				$this->buildRoutes($group);
			});

		} else {
			$routes = $this->buildRoutes($router);
		}

		$routes->addMiddleware($this->accessControlMiddleware);
		$routes->addMiddleware($this->userMiddleware);
		$routes->addMiddleware($this->triggersAccessControlMiddleware);
	}

	private function buildRoutes(Routing\IRouter|Routing\IRouteCollector $group): Routing\IRouteGroup
	{
		return $group->group('/v1', function (Routing\RouteCollector $group): void {
			$group->group('/triggers', function (Routing\RouteCollector $group): void {
				/**
				 * TRIGGERS
				 */
				$route = $group->get('', [$this->triggersV1Controller, 'index']);
				$route->setName(Triggers\Constants::ROUTE_NAME_TRIGGERS);

				$route = $group->get('/{' . self::URL_ITEM_ID . '}', [$this->triggersV1Controller, 'read']);
				$route->setName(Triggers\Constants::ROUTE_NAME_TRIGGER);

				$group->post('', [$this->triggersV1Controller, 'create']);

				$group->patch('/{' . self::URL_ITEM_ID . '}', [$this->triggersV1Controller, 'update']);

				$group->delete('/{' . self::URL_ITEM_ID . '}', [$this->triggersV1Controller, 'delete']);

				$route = $group->get('/{' . self::URL_ITEM_ID . '}/relationships/{' . self::RELATION_ENTITY . '}', [
					$this->triggersV1Controller,
					'readRelationship',
				]);
				$route->setName(Triggers\Constants::ROUTE_NAME_TRIGGER_RELATIONSHIP);
			});

			$group->group('/triggers/{' . self::URL_TRIGGER_ID . '}', function (Routing\RouteCollector $group): void {
				$group->group('/actions', function (Routing\RouteCollector $group): void {
					/**
					 * ACTIONS
					 */
					$route = $group->get('', [$this->actionsV1Controller, 'index']);
					$route->setName(Triggers\Constants::ROUTE_NAME_TRIGGER_ACTIONS);

					$route = $group->get('/{' . self::URL_ITEM_ID . '}', [$this->actionsV1Controller, 'read']);
					$route->setName(Triggers\Constants::ROUTE_NAME_TRIGGER_ACTION);

					$group->post('', [$this->actionsV1Controller, 'create']);

					$group->patch('/{' . self::URL_ITEM_ID . '}', [$this->actionsV1Controller, 'update']);

					$group->delete('/{' . self::URL_ITEM_ID . '}', [$this->actionsV1Controller, 'delete']);

					$route = $group->get(
						'/{' . self::URL_ITEM_ID . '}/relationships/{' . self::RELATION_ENTITY . '}',
						[
							$this->actionsV1Controller,
							'readRelationship',
						],
					);
					$route->setName(Triggers\Constants::ROUTE_NAME_TRIGGER_ACTION_RELATIONSHIP);
				});

				$group->group('/notifications', function (Routing\RouteCollector $group): void {
					/**
					 * NOTIFICATIONS
					 */
					$route = $group->get('', [$this->notificationsV1Controller, 'index']);
					$route->setName(Triggers\Constants::ROUTE_NAME_TRIGGER_NOTIFICATIONS);

					$route = $group->get('/{' . self::URL_ITEM_ID . '}', [$this->notificationsV1Controller, 'read']);
					$route->setName(Triggers\Constants::ROUTE_NAME_TRIGGER_NOTIFICATION);

					$group->post('', [$this->notificationsV1Controller, 'create']);

					$group->patch('/{' . self::URL_ITEM_ID . '}', [$this->notificationsV1Controller, 'update']);

					$group->delete('/{' . self::URL_ITEM_ID . '}', [$this->notificationsV1Controller, 'delete']);

					$route = $group->get(
						'/{' . self::URL_ITEM_ID . '}/relationships/{' . self::RELATION_ENTITY . '}',
						[
							$this->notificationsV1Controller,
							'readRelationship',
						],
					);
					$route->setName(Triggers\Constants::ROUTE_NAME_TRIGGER_NOTIFICATION_RELATIONSHIP);
				});

				$group->group('/conditions', function (Routing\RouteCollector $group): void {
					/**
					 * CONDITIONS
					 */
					$route = $group->get('', [$this->conditionsV1Controller, 'index']);
					$route->setName(Triggers\Constants::ROUTE_NAME_TRIGGER_CONDITIONS);

					$route = $group->get('/{' . self::URL_ITEM_ID . '}', [$this->conditionsV1Controller, 'read']);
					$route->setName(Triggers\Constants::ROUTE_NAME_TRIGGER_CONDITION);

					$group->post('', [$this->conditionsV1Controller, 'create']);

					$group->patch('/{' . self::URL_ITEM_ID . '}', [$this->conditionsV1Controller, 'update']);

					$group->delete('/{' . self::URL_ITEM_ID . '}', [$this->conditionsV1Controller, 'delete']);

					$route = $group->get(
						'/{' . self::URL_ITEM_ID . '}/relationships/{' . self::RELATION_ENTITY . '}',
						[
							$this->conditionsV1Controller,
							'readRelationship',
						],
					);
					$route->setName(Triggers\Constants::ROUTE_NAME_TRIGGER_CONDITION_RELATIONSHIP);
				});

				$group->group('/controls', function (Routing\RouteCollector $group): void {
					/**
					 * CONTROLS
					 */
					$route = $group->get('', [$this->controlsV1Controller, 'index']);
					$route->setName(Triggers\Constants::ROUTE_NAME_TRIGGER_CONTROLS);

					$route = $group->get('/{' . self::URL_ITEM_ID . '}', [$this->controlsV1Controller, 'read']);
					$route->setName(Triggers\Constants::ROUTE_NAME_TRIGGER_CONTROL);

					$group->post('', [$this->controlsV1Controller, 'create']);

					$group->patch('/{' . self::URL_ITEM_ID . '}', [$this->controlsV1Controller, 'update']);

					$group->delete('/{' . self::URL_ITEM_ID . '}', [$this->controlsV1Controller, 'delete']);

					$route = $group->get(
						'/{' . self::URL_ITEM_ID . '}/relationships/{' . self::RELATION_ENTITY . '}',
						[
							$this->controlsV1Controller,
							'readRelationship',
						],
					);
					$route->setName(Triggers\Constants::ROUTE_NAME_TRIGGER_CONTROL_RELATIONSHIP);
				});
			});
		});
	}

}
