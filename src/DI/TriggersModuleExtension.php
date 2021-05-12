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

use Contributte\Translation;
use Doctrine\Persistence;
use FastyBird\TriggersModule\Commands;
use FastyBird\TriggersModule\Consumers;
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
class TriggersModuleExtension extends DI\CompilerExtension implements Translation\DI\TranslationProviderInterface
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
		$builder->addDefinition($this->prefix('middleware.access'))
			->setType(Middleware\AccessMiddleware::class);

		$builder->addDefinition($this->prefix('router.routes'))
			->setType(Router\Routes::class)
			->setArguments(['usePrefix' => $configuration->apiPrefix]);

		// Console commands
		$builder->addDefinition($this->prefix('commands.initialize'))
			->setType(Commands\InitializeCommand::class);

		// Database repositories
		$builder->addDefinition($this->prefix('models.triggerRepository'))
			->setType(Models\Triggers\TriggerRepository::class);

		$builder->addDefinition($this->prefix('models.actionRepository'))
			->setType(Models\Actions\ActionRepository::class);

		$builder->addDefinition($this->prefix('models.conditionRepository'))
			->setType(Models\Conditions\ConditionRepository::class);

		$builder->addDefinition($this->prefix('models.notificationRepository'))
			->setType(Models\Notifications\NotificationRepository::class);

		// Database managers
		$builder->addDefinition($this->prefix('models.triggersManager'))
			->setType(Models\Triggers\TriggersManager::class)
			->setArgument('entityCrud', '__placeholder__');

		$builder->addDefinition($this->prefix('models.actionsManager'))
			->setType(Models\Actions\ActionsManager::class)
			->setArgument('entityCrud', '__placeholder__');

		$builder->addDefinition($this->prefix('models.conditionsManager'))
			->setType(Models\Conditions\ConditionsManager::class)
			->setArgument('entityCrud', '__placeholder__');

		$builder->addDefinition($this->prefix('models.notificationsManager'))
			->setType(Models\Notifications\NotificationsManager::class)
			->setArgument('entityCrud', '__placeholder__');

		// Events subscribers
		$builder->addDefinition($this->prefix('subscribers.actionEntity'))
			->setType(Subscribers\ActionEntitySubscriber::class);

		$builder->addDefinition($this->prefix('subscribers.conditionEntity'))
			->setType(Subscribers\ConditionEntitySubscriber::class);

		$builder->addDefinition($this->prefix('subscribers.notificationEntity'))
			->setType(Subscribers\NotificationEntitySubscriber::class);

		$builder->addDefinition($this->prefix('subscribers.entities'))
			->setType(Subscribers\EntitiesSubscriber::class);

		// Message bus consumers
		$builder->addDefinition($this->prefix('consumers.device'))
			->setType(Consumers\DeviceMessageConsumer::class);

		$builder->addDefinition($this->prefix('consumers.deviceProperty'))
			->setType(Consumers\DevicePropertyMessageConsumer::class);

		$builder->addDefinition($this->prefix('consumers.channel'))
			->setType(Consumers\ChannelMessageConsumer::class);

		$builder->addDefinition($this->prefix('consumers.channelProperty'))
			->setType(Consumers\ChannelPropertyMessageConsumer::class);

		// API controllers
		$builder->addDefinition($this->prefix('controllers.triggers'))
			->setType(Controllers\TriggersV1Controller::class)
			->addTag('nette.inject');

		$builder->addDefinition($this->prefix('controllers.actions'))
			->setType(Controllers\ActionsV1Controller::class)
			->addTag('nette.inject');

		$builder->addDefinition($this->prefix('controllers.conditions'))
			->setType(Controllers\ConditionsV1Controller::class)
			->addTag('nette.inject');

		$builder->addDefinition($this->prefix('controllers.notifications'))
			->setType(Controllers\NotificationsV1Controller::class)
			->addTag('nette.inject');

		// API schemas
		$builder->addDefinition($this->prefix('schemas.triggers.automatic'))
			->setType(Schemas\Triggers\AutomaticTriggerSchema::class);

		$builder->addDefinition($this->prefix('schemas.triggers.manual'))
			->setType(Schemas\Triggers\ManualTriggerSchema::class);

		$builder->addDefinition($this->prefix('schemas.actions.channelProperty'))
			->setType(Schemas\Actions\ChannelPropertyActionSchema::class);

		$builder->addDefinition($this->prefix('schemas.conditions.channelProperty'))
			->setType(Schemas\Conditions\ChannelPropertyConditionSchema::class);

		$builder->addDefinition($this->prefix('schemas.conditions.deviceProperty'))
			->setType(Schemas\Conditions\DevicePropertyConditionSchema::class);

		$builder->addDefinition($this->prefix('schemas.conditions.date'))
			->setType(Schemas\Conditions\DateConditionSchema::class);

		$builder->addDefinition($this->prefix('schemas.conditions.time'))
			->setType(Schemas\Conditions\TimeConditionSchema::class);

		$builder->addDefinition($this->prefix('schemas.notifications.email'))
			->setType(Schemas\Notifications\EmailNotificationSchema::class);

		$builder->addDefinition($this->prefix('schemas.notifications.sms'))
			->setType(Schemas\Notifications\SmsNotificationSchema::class);

		// API hydrators
		$builder->addDefinition($this->prefix('hydrators.triggers.automatic'))
			->setType(Hydrators\Triggers\AutomaticTriggerHydrator::class);

		$builder->addDefinition($this->prefix('hydrators.triggers.manual'))
			->setType(Hydrators\Triggers\ManualTriggerHydrator::class);

		$builder->addDefinition($this->prefix('hydrators.actions.channelProperty'))
			->setType(Hydrators\Actions\ChannelPropertyActionHydrator::class);

		$builder->addDefinition($this->prefix('hydrators.conditions.channelProperty'))
			->setType(Hydrators\Conditions\ChannelPropertyConditionHydrator::class);

		$builder->addDefinition($this->prefix('hydrators.conditions.deviceProperty'))
			->setType(Hydrators\Conditions\DevicePropertyConditionHydrator::class);

		$builder->addDefinition($this->prefix('hydrators.conditions.time'))
			->setType(Hydrators\Conditions\TimeConditionHydrator::class);

		$builder->addDefinition($this->prefix('hydrators.notifications.email'))
			->setType(Hydrators\Notifications\EmailNotificationHydrator::class);

		$builder->addDefinition($this->prefix('hydrators.notifications.sms'))
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

		$actionsManagerService = $class->getMethod('createService' . ucfirst($this->name) . '__models__actionsManager');
		$actionsManagerService->setBody('return new ' . Models\Actions\ActionsManager::class . '($this->getService(\'' . $entityFactoryServiceName . '\')->create(\'' . Entities\Actions\Action::class . '\'));');

		$conditionsManagerService = $class->getMethod('createService' . ucfirst($this->name) . '__models__conditionsManager');
		$conditionsManagerService->setBody('return new ' . Models\Conditions\ConditionsManager::class . '($this->getService(\'' . $entityFactoryServiceName . '\')->create(\'' . Entities\Conditions\Condition::class . '\'));');

		$notificationsManagerService = $class->getMethod('createService' . ucfirst($this->name) . '__models__notificationsManager');
		$notificationsManagerService->setBody('return new ' . Models\Notifications\NotificationsManager::class . '($this->getService(\'' . $entityFactoryServiceName . '\')->create(\'' . Entities\Notifications\Notification::class . '\'));');
	}

	/**
	 * @return string[]
	 */
	public function getTranslationResources(): array
	{
		return [
			__DIR__ . '/../Translations',
		];
	}

}
