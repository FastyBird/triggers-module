<?php declare(strict_types = 1);

namespace Tests\Cases;

use FastyBird\TriggersModule\Commands;
use FastyBird\TriggersModule\Controllers;
use FastyBird\TriggersModule\DI;
use FastyBird\TriggersModule\Hydrators;
use FastyBird\TriggersModule\Models;
use FastyBird\TriggersModule\Schemas;
use FastyBird\TriggersModule\Subscribers;
use Nette;
use Ninjify\Nunjuck\TestCase\BaseTestCase;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

/**
 * @testCase
 */
final class ServicesTest extends BaseTestCase
{

	public function testServicesRegistration(): void
	{
		$container = $this->createContainer();

		Assert::notNull($container->getByType(Commands\InitializeCommand::class));

		Assert::notNull($container->getByType(Models\Triggers\TriggerRepository::class));
		Assert::notNull($container->getByType(Models\Actions\ActionRepository::class));
		Assert::notNull($container->getByType(Models\Notifications\NotificationRepository::class));
		Assert::notNull($container->getByType(Models\Conditions\ConditionRepository::class));

		Assert::notNull($container->getByType(Models\Triggers\TriggersManager::class));
		Assert::notNull($container->getByType(Models\Actions\ActionsManager::class));
		Assert::notNull($container->getByType(Models\Notifications\NotificationsManager::class));
		Assert::notNull($container->getByType(Models\Conditions\ConditionsManager::class));

		Assert::notNull($container->getByType(Controllers\TriggersV1Controller::class));
		Assert::notNull($container->getByType(Controllers\ActionsV1Controller::class));
		Assert::notNull($container->getByType(Controllers\NotificationsV1Controller::class));
		Assert::notNull($container->getByType(Controllers\ConditionsV1Controller::class));

		Assert::notNull($container->getByType(Schemas\Triggers\AutomaticTriggerSchema::class));
		Assert::notNull($container->getByType(Schemas\Triggers\ManualTriggerSchema::class));
		Assert::notNull($container->getByType(Schemas\Actions\ChannelPropertyActionSchema::class));
		Assert::notNull($container->getByType(Schemas\Notifications\EmailNotificationSchema::class));
		Assert::notNull($container->getByType(Schemas\Notifications\SmsNotificationSchema::class));
		Assert::notNull($container->getByType(Schemas\Conditions\ChannelPropertyConditionSchema::class));
		Assert::notNull($container->getByType(Schemas\Conditions\DevicePropertyConditionSchema::class));
		Assert::notNull($container->getByType(Schemas\Conditions\DateConditionSchema::class));
		Assert::notNull($container->getByType(Schemas\Conditions\TimeConditionSchema::class));

		Assert::notNull($container->getByType(Hydrators\Triggers\AutomaticTriggerHydrator::class));
		Assert::notNull($container->getByType(Hydrators\Triggers\ManualTriggerHydrator::class));
		Assert::notNull($container->getByType(Hydrators\Actions\ChannelPropertyActionHydrator::class));
		Assert::notNull($container->getByType(Hydrators\Notifications\EmailNotificationHydrator::class));
		Assert::notNull($container->getByType(Hydrators\Notifications\SmsNotificationHydrator::class));
		Assert::notNull($container->getByType(Hydrators\Conditions\ChannelPropertyConditionHydrator::class));
		Assert::notNull($container->getByType(Hydrators\Conditions\DevicePropertyConditionHydrator::class));
		Assert::notNull($container->getByType(Hydrators\Conditions\TimeConditionHydrator::class));

		Assert::notNull($container->getByType(Subscribers\EntitiesSubscriber::class));
		Assert::notNull($container->getByType(Subscribers\ActionEntitySubscriber::class));
		Assert::notNull($container->getByType(Subscribers\ConditionEntitySubscriber::class));
		Assert::notNull($container->getByType(Subscribers\NotificationEntitySubscriber::class));
	}

	/**
	 * @return Nette\DI\Container
	 */
	protected function createContainer(): Nette\DI\Container
	{
		$rootDir = __DIR__ . '/../../../';

		$config = new Nette\Configurator();
		$config->setTempDirectory(TEMP_DIR);

		$config->addParameters(['container' => ['class' => 'SystemContainer_' . md5((string) time())]]);
		$config->addParameters(['appDir' => $rootDir, 'wwwDir' => $rootDir]);

		$config->addConfig(__DIR__ . '/../../../common.neon');

		DI\TriggersModuleExtension::register($config);

		return $config->createContainer();
	}

}

$test_case = new ServicesTest();
$test_case->run();
