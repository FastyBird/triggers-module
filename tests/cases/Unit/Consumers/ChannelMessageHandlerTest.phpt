<?php declare(strict_types = 1);

namespace Tests\Cases;

use FastyBird\ApplicationExchange\Publisher as ApplicationExchangePublisher;
use FastyBird\ModulesMetadata;
use FastyBird\TriggersModule\Consumers;
use FastyBird\TriggersModule\Entities;
use FastyBird\TriggersModule\Models;
use FastyBird\TriggersModule\Queries;
use Mockery;
use Nette\Utils;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';
require_once __DIR__ . '/../DbTestCase.php';

/**
 * @testCase
 */
final class ChannelMessageHandlerTest extends DbTestCase
{

	/**
	 * @param string $routingKey
	 * @param Utils\ArrayHash $message
	 * @param int $publishCallCount
	 * @param mixed[] $fixture
	 *
	 * @dataProvider ./../../../fixtures/Consumers/channelDeleteMessage.php
	 */
	public function testProcessMessageDelete(
		string $routingKey,
		Utils\ArrayHash $message,
		int $publishCallCount,
		array $fixture
	): void {
		$triggersRepository = $this->getContainer()->getByType(Models\Triggers\TriggerRepository::class);
		$actionRepository = $this->getContainer()->getByType(Models\Actions\ActionRepository::class);

		$findQuery = new Queries\FindChannelPropertyTriggersQuery();
		$findQuery->forChannel('zB8F0Q');

		$found = $triggersRepository->findAllBy($findQuery, Entities\Triggers\ChannelPropertyTrigger::class);

		Assert::count(1, $found);

		$findQuery = new Queries\FindActionsQuery();
		$findQuery->forChannel('zB8F0Q');

		$found = $actionRepository->findAllBy($findQuery, Entities\Actions\ChannelPropertyAction::class);

		Assert::count(2, $found);

		$publisher = Mockery::mock(ApplicationExchangePublisher\PublisherProxy::class);
		$publisher
			->shouldReceive('publish')
			->withArgs(function (string $routingKey, array $data) use ($fixture): bool {
				if (Utils\Strings::contains($routingKey, 'created')) {
					unset($data['id']);
				}

				Assert::false($data === []);
				Assert::true(isset($fixture[$routingKey]));

				if (isset($fixture[$routingKey]['primaryKey'])) {
					Assert::equal($fixture[$routingKey][$data[$fixture[$routingKey]['primaryKey']]], $data);

				} else {
					Assert::equal($fixture[$routingKey], $data);
				}

				return true;
			})
			->times($publishCallCount);

		$this->mockContainerService(
			ApplicationExchangePublisher\PublisherProxy::class,
			$publisher
		);

		$consumer = $this->getContainer()->getByType(Consumers\ChannelMessageConsumer::class);

		$consumer->consume(ModulesMetadata\Constants::MODULE_DEVICES_ORIGIN, $routingKey, $message);

		$findQuery = new Queries\FindChannelPropertyTriggersQuery();
		$findQuery->forChannel('zB8F0Q');

		$found = $triggersRepository->findAllBy($findQuery, Entities\Triggers\ChannelPropertyTrigger::class);

		Assert::count(0, $found);

		$findQuery = new Queries\FindActionsQuery();
		$findQuery->forChannel('zB8F0Q');

		$found = $actionRepository->findAllBy($findQuery, Entities\Actions\ChannelPropertyAction::class);

		Assert::count(0, $found);
	}

}

$test_case = new ChannelMessageHandlerTest();
$test_case->run();
