<?php declare(strict_types = 1);

/**
 * TriggersModuleExtension.php
 *
 * @license        More in LICENSE.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     DI
 * @since          0.1.0
 *
 * @date           29.11.20
 */

namespace FastyBird\TriggersModule\DI;

use Doctrine\Persistence;
use FastyBird\TriggersModule\Commands;
use FastyBird\TriggersModule\Controllers;
use FastyBird\TriggersModule\Entities;
use FastyBird\TriggersModule\Hydrators;
use FastyBird\TriggersModule\Middleware;
use FastyBird\TriggersModule\Models;
use FastyBird\TriggersModule\Router;
use FastyBird\TriggersModule\Schemas;
use FastyBird\TriggersModule\Subscribers;
use IPub\DoctrineCrud;
use Nette;
use Nette\DI;
use Nette\PhpGenerator;
use Nette\Schema;
use stdClass;

/**
 * Triggers module extension container
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     DI
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
class TriggersModuleExtension extends DI\CompilerExtension
{

	/**
	 * @param Nette\Configurator $config
	 * @param string $extensionName
	 *
	 * @return void
	 */
	public static function register(
		Nette\Configurator $config,
		string $extensionName = 'fbTriggersModule'
	): void {
		$config->onCompile[] = function (
			Nette\Configurator $config,
			DI\Compiler $compiler
		) use ($extensionName): void {
			$compiler->addExtension($extensionName, new TriggersModuleExtension());
		};
	}

	/**
	 * {@inheritdoc}
	 */
	public function getConfigSchema(): Schema\Schema
	{
		return Schema\Expect::structure([
			'apiPrefix' => Schema\Expect::bool(false),
		]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		/** @var stdClass $configuration */
		$configuration = $this->getConfig();

		// Http router
		$builder->addDefinition($this->prefix('middleware.access'), new DI\Definitions\ServiceDefinition())
			->setType(Middleware\AccessMiddleware::class);

		$builder->addDefinition($this->prefix('router.routes'), new DI\Definitions\ServiceDefinition())
			->setType(Router\Routes::class)
			->setArguments(['usePrefix' => $configuration->apiPrefix]);

		$builder->addDefinition($this->prefix('router.validator'), new DI\Definitions\ServiceDefinition())
			->setType(Router\Validator::class);

		// Console commands
		$builder->addDefinition($this->prefix('commands.initialize'), new DI\Definitions\ServiceDefinition())
			->setType(Commands\InitializeCommand::class);

		// Database repositories
		$builder->addDefinition($this->prefix('models.triggerRepository'), new DI\Definitions\ServiceDefinition())
			->setType(Models\Triggers\TriggerRepository::class);

		$builder->addDefinition($this->prefix('models.triggerControlRepository'), new DI\Definitions\ServiceDefinition())
			->setType(Models\Triggers\Controls\ControlRepository::class);

		$builder->addDefinition($this->prefix('models.actionRepository'), new DI\Definitions\ServiceDefinition())
			->setType(Models\Actions\ActionRepository::class);

		$builder->addDefinition($this->prefix('models.conditionRepository'), new DI\Definitions\ServiceDefinition())
			->setType(Models\Conditions\ConditionRepository::class);

		$builder->addDefinition($this->prefix('models.notificationRepository'), new DI\Definitions\ServiceDefinition())
			->setType(Models\Notifications\NotificationRepository::class);

		// Database managers
		$builder->addDefinition($this->prefix('models.triggersManager'), new DI\Definitions\ServiceDefinition())
			->setType(Models\Triggers\TriggersManager::class)
			->setArgument('entityCrud', '__placeholder__');

		$builder->addDefinition($this->prefix('models.triggersControlsManager'), new DI\Definitions\ServiceDefinition())
			->setType(Models\Triggers\Controls\ControlsManager::class)
			->setArgument('entityCrud', '__placeholder__');

		$builder->addDefinition($this->prefix('models.actionsManager'), new DI\Definitions\ServiceDefinition())
			->setType(Models\Actions\ActionsManager::class)
			->setArgument('entityCrud', '__placeholder__');

		$builder->addDefinition($this->prefix('models.conditionsManager'), new DI\Definitions\ServiceDefinition())
			->setType(Models\Conditions\ConditionsManager::class)
			->setArgument('entityCrud', '__placeholder__');

		$builder->addDefinition($this->prefix('models.notificationsManager'), new DI\Definitions\ServiceDefinition())
			->setType(Models\Notifications\NotificationsManager::class)
			->setArgument('entityCrud', '__placeholder__');

		// Events subscribers
		$builder->addDefinition($this->prefix('subscribers.actionEntity'), new DI\Definitions\ServiceDefinition())
			->setType(Subscribers\ActionEntitySubscriber::class);

		$builder->addDefinition($this->prefix('subscribers.conditionEntity'), new DI\Definitions\ServiceDefinition())
			->setType(Subscribers\ConditionEntitySubscriber::class);

		$builder->addDefinition($this->prefix('subscribers.notificationEntity'), new DI\Definitions\ServiceDefinition())
			->setType(Subscribers\NotificationEntitySubscriber::class);

		$builder->addDefinition($this->prefix('subscribers.entities'), new DI\Definitions\ServiceDefinition())
			->setType(Subscribers\EntitiesSubscriber::class);

		// API controllers
		$builder->addDefinition($this->prefix('controllers.triggers'), new DI\Definitions\ServiceDefinition())
			->setType(Controllers\TriggersV1Controller::class)
			->addTag('nette.inject');

		$builder->addDefinition($this->prefix('controllers.actions'), new DI\Definitions\ServiceDefinition())
			->setType(Controllers\ActionsV1Controller::class)
			->addTag('nette.inject');

		$builder->addDefinition($this->prefix('controllers.conditions'), new DI\Definitions\ServiceDefinition())
			->setType(Controllers\ConditionsV1Controller::class)
			->addTag('nette.inject');

		$builder->addDefinition($this->prefix('controllers.notifications'), new DI\Definitions\ServiceDefinition())
			->setType(Controllers\NotificationsV1Controller::class)
			->addTag('nette.inject');

		$builder->addDefinition($this->prefix('controllers.triggersControls'), new DI\Definitions\ServiceDefinition())
			->setType(Controllers\TriggerControlsV1Controller::class)
			->addTag('nette.inject');

		// API schemas
		$builder->addDefinition($this->prefix('schemas.triggers.automatic'), new DI\Definitions\ServiceDefinition())
			->setType(Schemas\Triggers\AutomaticTriggerSchema::class);

		$builder->addDefinition($this->prefix('schemas.triggers.manual'), new DI\Definitions\ServiceDefinition())
			->setType(Schemas\Triggers\ManualTriggerSchema::class);

		$builder->addDefinition($this->prefix('schemas.trigger.control'), new DI\Definitions\ServiceDefinition())
			->setType(Schemas\Triggers\Controls\ControlSchema::class);

		$builder->addDefinition($this->prefix('schemas.actions.deviceProperty'), new DI\Definitions\ServiceDefinition())
			->setType(Schemas\Actions\DevicePropertyActionSchema::class);

		$builder->addDefinition($this->prefix('schemas.actions.channelProperty'), new DI\Definitions\ServiceDefinition())
			->setType(Schemas\Actions\ChannelPropertyActionSchema::class);

		$builder->addDefinition($this->prefix('schemas.conditions.channelProperty'), new DI\Definitions\ServiceDefinition())
			->setType(Schemas\Conditions\ChannelPropertyConditionSchema::class);

		$builder->addDefinition($this->prefix('schemas.conditions.deviceProperty'), new DI\Definitions\ServiceDefinition())
			->setType(Schemas\Conditions\DevicePropertyConditionSchema::class);

		$builder->addDefinition($this->prefix('schemas.conditions.date'), new DI\Definitions\ServiceDefinition())
			->setType(Schemas\Conditions\DateConditionSchema::class);

		$builder->addDefinition($this->prefix('schemas.conditions.time'), new DI\Definitions\ServiceDefinition())
			->setType(Schemas\Conditions\TimeConditionSchema::class);

		$builder->addDefinition($this->prefix('schemas.notifications.email'), new DI\Definitions\ServiceDefinition())
			->setType(Schemas\Notifications\EmailNotificationSchema::class);

		$builder->addDefinition($this->prefix('schemas.notifications.sms'), new DI\Definitions\ServiceDefinition())
			->setType(Schemas\Notifications\SmsNotificationSchema::class);

		// API hydrators
		$builder->addDefinition($this->prefix('hydrators.triggers.automatic'), new DI\Definitions\ServiceDefinition())
			->setType(Hydrators\Triggers\AutomaticTriggerHydrator::class);

		$builder->addDefinition($this->prefix('hydrators.triggers.manual'), new DI\Definitions\ServiceDefinition())
			->setType(Hydrators\Triggers\ManualTriggerHydrator::class);

		$builder->addDefinition($this->prefix('hydrators.actions.deviceProperty'), new DI\Definitions\ServiceDefinition())
			->setType(Hydrators\Actions\DevicePropertyActionHydrator::class);

		$builder->addDefinition($this->prefix('hydrators.actions.channelProperty'), new DI\Definitions\ServiceDefinition())
			->setType(Hydrators\Actions\ChannelPropertyActionHydrator::class);

		$builder->addDefinition($this->prefix('hydrators.conditions.channelProperty'), new DI\Definitions\ServiceDefinition())
			->setType(Hydrators\Conditions\ChannelPropertyConditionHydrator::class);

		$builder->addDefinition($this->prefix('hydrators.conditions.deviceProperty'), new DI\Definitions\ServiceDefinition())
			->setType(Hydrators\Conditions\DevicePropertyConditionHydrator::class);

		$builder->addDefinition($this->prefix('hydrators.conditions.date'), new DI\Definitions\ServiceDefinition())
			->setType(Hydrators\Conditions\DataConditionHydrator::class);

		$builder->addDefinition($this->prefix('hydrators.conditions.time'), new DI\Definitions\ServiceDefinition())
			->setType(Hydrators\Conditions\TimeConditionHydrator::class);

		$builder->addDefinition($this->prefix('hydrators.notifications.email'), new DI\Definitions\ServiceDefinition())
			->setType(Hydrators\Notifications\EmailNotificationHydrator::class);

		$builder->addDefinition($this->prefix('hydrators.notifications.sms'), new DI\Definitions\ServiceDefinition())
			->setType(Hydrators\Notifications\SmsNotificationHydrator::class);
	}

	/**
	 * {@inheritDoc}
	 */
	public function beforeCompile(): void
	{
		parent::beforeCompile();

		$builder = $this->getContainerBuilder();

		/**
		 * Doctrine entities
		 */

		$ormAnnotationDriverService = $builder->getDefinition('nettrineOrmAnnotations.annotationDriver');

		if ($ormAnnotationDriverService instanceof DI\Definitions\ServiceDefinition) {
			$ormAnnotationDriverService->addSetup('addPaths', [[__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Entities']]);
		}

		$ormAnnotationDriverChainService = $builder->getDefinitionByType(Persistence\Mapping\Driver\MappingDriverChain::class);

		if ($ormAnnotationDriverChainService instanceof DI\Definitions\ServiceDefinition) {
			$ormAnnotationDriverChainService->addSetup('addDriver', [
				$ormAnnotationDriverService,
				'FastyBird\TriggersModule\Entities',
			]);
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function afterCompile(
		PhpGenerator\ClassType $class
	): void {
		$builder = $this->getContainerBuilder();

		$entityFactoryServiceName = $builder->getByType(DoctrineCrud\Crud\IEntityCrudFactory::class, true);

		$triggersManagerService = $class->getMethod('createService' . ucfirst($this->name) . '__models__triggersManager');
		$triggersManagerService->setBody('return new ' . Models\Triggers\TriggersManager::class . '($this->getService(\'' . $entityFactoryServiceName . '\')->create(\'' . Entities\Triggers\Trigger::class . '\'));');

		$triggersControlsManagerService = $class->getMethod('createService' . ucfirst($this->name) . '__models__triggersControlsManager');
		$triggersControlsManagerService->setBody('return new ' . Models\Triggers\Controls\ControlsManager::class . '($this->getService(\'' . $entityFactoryServiceName . '\')->create(\'' . Entities\Triggers\Controls\Control::class . '\'));');

		$actionsManagerService = $class->getMethod('createService' . ucfirst($this->name) . '__models__actionsManager');
		$actionsManagerService->setBody('return new ' . Models\Actions\ActionsManager::class . '($this->getService(\'' . $entityFactoryServiceName . '\')->create(\'' . Entities\Actions\Action::class . '\'));');

		$conditionsManagerService = $class->getMethod('createService' . ucfirst($this->name) . '__models__conditionsManager');
		$conditionsManagerService->setBody('return new ' . Models\Conditions\ConditionsManager::class . '($this->getService(\'' . $entityFactoryServiceName . '\')->create(\'' . Entities\Conditions\Condition::class . '\'));');

		$notificationsManagerService = $class->getMethod('createService' . ucfirst($this->name) . '__models__notificationsManager');
		$notificationsManagerService->setBody('return new ' . Models\Notifications\NotificationsManager::class . '($this->getService(\'' . $entityFactoryServiceName . '\')->create(\'' . Entities\Notifications\Notification::class . '\'));');
	}

}
