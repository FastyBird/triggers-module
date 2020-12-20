<?php declare(strict_types = 1);

/**
 * TriggersModuleExtension.php
 *
 * @license        More in license.md
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
	 * {@inheritDoc}
	 */
	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();

		// Http router
		$builder->addDefinition(null)
			->setType(Middleware\AccessMiddleware::class);

		$builder->addDefinition(null)
			->setType(Router\Routes::class);

		// Console commands
		$builder->addDefinition(null)
			->setType(Commands\InitializeCommand::class);

		// Database repositories
		$builder->addDefinition(null)
			->setType(Models\Triggers\TriggerRepository::class);

		$builder->addDefinition(null)
			->setType(Models\Actions\ActionRepository::class);

		$builder->addDefinition(null)
			->setType(Models\Conditions\ConditionRepository::class);

		$builder->addDefinition(null)
			->setType(Models\Notifications\NotificationRepository::class);

		// Database managers
		$builder->addDefinition($this->prefix('doctrine.triggersManager'))
			->setType(Models\Triggers\TriggersManager::class)
			->setArgument('entityCrud', '__placeholder__');

		$builder->addDefinition($this->prefix('doctrine.actionsManager'))
			->setType(Models\Actions\ActionsManager::class)
			->setArgument('entityCrud', '__placeholder__');

		$builder->addDefinition($this->prefix('doctrine.conditionsManager'))
			->setType(Models\Conditions\ConditionsManager::class)
			->setArgument('entityCrud', '__placeholder__');

		$builder->addDefinition($this->prefix('doctrine.notificationsManager'))
			->setType(Models\Notifications\NotificationsManager::class)
			->setArgument('entityCrud', '__placeholder__');

		// Events subscribers
		$builder->addDefinition(null)
			->setType(Subscribers\ActionEntitySubscriber::class);

		$builder->addDefinition(null)
			->setType(Subscribers\ConditionEntitySubscriber::class);

		$builder->addDefinition(null)
			->setType(Subscribers\NotificationEntitySubscriber::class);

		$builder->addDefinition(null)
			->setType(Subscribers\EntitiesSubscriber::class);

		// API controllers
		$builder->addDefinition(null)
			->setType(Controllers\TriggersV1Controller::class)
			->addTag('nette.inject');

		$builder->addDefinition(null)
			->setType(Controllers\ActionsV1Controller::class)
			->addTag('nette.inject');

		$builder->addDefinition(null)
			->setType(Controllers\ConditionsV1Controller::class)
			->addTag('nette.inject');

		$builder->addDefinition(null)
			->setType(Controllers\NotificationsV1Controller::class)
			->addTag('nette.inject');

		// API schemas
		$builder->addDefinition(null)
			->setType(Schemas\Triggers\AutomaticTriggerSchema::class);

		$builder->addDefinition(null)
			->setType(Schemas\Triggers\ManualTriggerSchema::class);

		$builder->addDefinition(null)
			->setType(Schemas\Triggers\ChannelPropertyTriggerSchema::class);

		$builder->addDefinition(null)
			->setType(Schemas\Actions\ChannelPropertyActionSchema::class);

		$builder->addDefinition(null)
			->setType(Schemas\Conditions\ChannelPropertyConditionSchema::class);

		$builder->addDefinition(null)
			->setType(Schemas\Conditions\DevicePropertyConditionSchema::class);

		$builder->addDefinition(null)
			->setType(Schemas\Conditions\DateConditionSchema::class);

		$builder->addDefinition(null)
			->setType(Schemas\Conditions\TimeConditionSchema::class);

		$builder->addDefinition(null)
			->setType(Schemas\Notifications\EmailNotificationSchema::class);

		$builder->addDefinition(null)
			->setType(Schemas\Notifications\SmsNotificationSchema::class);

		// API hydrators
		$builder->addDefinition(null)
			->setType(Hydrators\Triggers\AutomaticTriggerHydrator::class);

		$builder->addDefinition(null)
			->setType(Hydrators\Triggers\ManualTriggerHydrator::class);

		$builder->addDefinition(null)
			->setType(Hydrators\Triggers\ChannelPropertyTriggerHydrator::class);

		$builder->addDefinition(null)
			->setType(Hydrators\Actions\ChannelPropertyActionHydrator::class);

		$builder->addDefinition(null)
			->setType(Hydrators\Conditions\ChannelPropertyConditionHydrator::class);

		$builder->addDefinition(null)
			->setType(Hydrators\Conditions\DevicePropertyConditionHydrator::class);

		$builder->addDefinition(null)
			->setType(Hydrators\Conditions\TimeConditionHydrator::class);

		$builder->addDefinition(null)
			->setType(Hydrators\Notifications\EmailNotificationHydrator::class);

		$builder->addDefinition(null)
			->setType(Hydrators\Notifications\SmsNotificationHydrator::class);

		// Message bus consumers
		$builder->addDefinition(null)
			->setType(Consumers\DeviceMessageHandler::class);

		$builder->addDefinition(null)
			->setType(Consumers\DevicePropertyMessageHandler::class);

		$builder->addDefinition(null)
			->setType(Consumers\ChannelMessageHandler::class);

		$builder->addDefinition(null)
			->setType(Consumers\ChannelPropertyMessageHandler::class);
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

		$triggersManagerService = $class->getMethod('createService' . ucfirst($this->name) . '__doctrine__triggersManager');
		$triggersManagerService->setBody('return new ' . Models\Triggers\TriggersManager::class . '($this->getService(\'' . $entityFactoryServiceName . '\')->create(\'' . Entities\Triggers\Trigger::class . '\'));');

		$actionsManagerService = $class->getMethod('createService' . ucfirst($this->name) . '__doctrine__actionsManager');
		$actionsManagerService->setBody('return new ' . Models\Actions\ActionsManager::class . '($this->getService(\'' . $entityFactoryServiceName . '\')->create(\'' . Entities\Actions\Action::class . '\'));');

		$conditionsManagerService = $class->getMethod('createService' . ucfirst($this->name) . '__doctrine__conditionsManager');
		$conditionsManagerService->setBody('return new ' . Models\Conditions\ConditionsManager::class . '($this->getService(\'' . $entityFactoryServiceName . '\')->create(\'' . Entities\Conditions\Condition::class . '\'));');

		$notificationsManagerService = $class->getMethod('createService' . ucfirst($this->name) . '__doctrine__notificationsManager');
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
