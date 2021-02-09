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
final class ChannelPropertyMessageHandlerTest extends DbTestCase
{

	/**
	 * @param string $routingKey
	 * @param Utils\ArrayHash $message
	 * @param int $publishCallCount
	 * @param mixed[] $fixture
	 *
	 * @dataProvider ./../../../fixtures/Consumers/channelPropertyDeleteMessage.php
	 */
	public function testProcessMessageDeleteTrigger(
		string $routingKey,
		Utils\ArrayHash $message,
		int $publishCallCount,
		array $fixture
	): void {
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

		/** @var Consumers\ChannelPropertyMessageConsumer $consumer */
		$consumer = $this->getContainer()->getByType(Consumers\ChannelPropertyMessageConsumer::class);

		$consumer->consume(ModulesMetadata\Constants::MODULE_DEVICES_ORIGIN, $routingKey, $message);
	}

	/**
	 * @param string $routingKey
	 * @param Utils\ArrayHash $message
	 * @param int $publishCallCount
	 * @param mixed[] $fixture
	 *
	 * @dataProvider ./../../../fixtures/Consumers/channelPropertyDeleteMessage.php
	 */
	public function testProcessMessageDeleteAction(
		string $routingKey,
		Utils\ArrayHash $message,
		int $publishCallCount,
		array $fixture
	): void {
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

		/** @var Consumers\ChannelPropertyMessageConsumer $consumer */
		$consumer = $this->getContainer()->getByType(Consumers\ChannelPropertyMessageConsumer::class);

		$consumer->consume(ModulesMetadata\Constants::MODULE_DEVICES_ORIGIN, $routingKey, $message);
	}

	public function testProcessMessageFireAction(): void
	{
		$routingKey = ModulesMetadata\Constants::MESSAGE_BUS_CHANNELS_PROPERTY_UPDATED_ENTITY_ROUTING_KEY;
		$message = Utils\ArrayHash::from([
			'id'        => 'fe2badf6-2e85-4ef6-9009-fe247d473069',
			'key'       => 'k7pT0Q',
			'value'     => '3',
			'pending'   => false,
			'data_type' => null,
			'format'    => null,
			'owner'     => '89ce7161-12dd-427e-9a35-92bc4390d98d',
		]);

		$publisher = Mockery::mock(ApplicationExchangePublisher\PublisherProxy::class);
		$publisher
			->shouldReceive('publish')
			->withArgs(function (string $routingKey, array $data): bool {
				Assert::same(ModulesMetadata\Constants::MESSAGE_BUS_CHANNELS_PROPERTIES_DATA_ROUTING_KEY, $routingKey);
				Assert::same(
					[
						'device'   => 'GB8F0Q',
						'channel'  => '2B8F0Q',
						'property' => 'h1WQ0Q',
						'expected' => 'toggle',
					],
					$data
				);

				return true;
			});

		$this->mockContainerService(
			ApplicationExchangePublisher\PublisherProxy::class,
			$publisher
		);

		$consumer = $this->getContainer()->getByType(Consumers\ChannelPropertyMessageConsumer::class);

		$consumer->consume(ModulesMetadata\Constants::MODULE_DEVICES_ORIGIN, $routingKey, $message);
	}

}

$test_case = new ChannelPropertyMessageHandlerTest();
$test_case->run();
