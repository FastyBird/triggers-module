<?php declare(strict_types = 1);

namespace Tests\Cases;

use Doctrine\ORM;
use FastyBird\ApplicationExchange\Publisher as ApplicationExchangePublisher;
use FastyBird\TriggersModule\Entities;
use FastyBird\TriggersModule\Subscribers;
use FastyBird\TriggersModule\Types;
use Mockery;
use Ninjify\Nunjuck\TestCase\BaseMockeryTestCase;
use stdClass;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

/**
 * @testCase
 */
final class EntitiesSubscriberTest extends BaseMockeryTestCase
{

	public function testSubscriberEvents(): void
	{
		$publisher = Mockery::mock(ApplicationExchangePublisher\IPublisher::class);
		$entityManager = Mockery::mock(ORM\EntityManagerInterface::class);

		$subscriber = new Subscribers\EntitiesSubscriber(
			$publisher,
			$entityManager
		);

		Assert::same(['onFlush', 'postPersist', 'postUpdate'], $subscriber->getSubscribedEvents());
	}

	public function testPublishCreatedEntity(): void
	{
		$publisher = Mockery::mock(ApplicationExchangePublisher\IPublisher::class);
		$publisher
			->shouldReceive('publish')
			->withArgs(function (string $key, array $data): bool {
				unset($data['id']);

				Assert::same('fb.bus.entity.created.trigger', $key);
				Assert::equal([
					'name'     => 'Trigger name',
					'comment'  => null,
					'enabled'  => true,
					'owner'    => null,
					'type'     => 'channel-property',
					'device'   => 'cB8F0Q',
					'channel'  => '1B8F0Q',
					'property' => 'h1WQ0Q',
					'operand'  => '10',
					'operator' => 'eq',
				], $data);

				return true;
			})
			->times(1);

		$entityManager = $this->getEntityManager();

		$subscriber = new Subscribers\EntitiesSubscriber(
			$publisher,
			$entityManager
		);

		$entity = new Entities\Triggers\ChannelPropertyTrigger(
			'cB8F0Q',
			'1B8F0Q',
			'h1WQ0Q',
			Types\ConditionOperatorType::get(Types\ConditionOperatorType::OPERATOR_VALUE_EQUAL),
			'10',
			'Trigger name'
		);

		$eventArgs = Mockery::mock(ORM\Event\LifecycleEventArgs::class);
		$eventArgs
			->shouldReceive('getObject')
			->withNoArgs()
			->andReturn($entity)
			->times(1);

		$subscriber->postPersist($eventArgs);
	}

	/**
	 * @param bool $withUow
	 *
	 * @return ORM\EntityManagerInterface
	 */
	private function getEntityManager(bool $withUow = false): ORM\EntityManagerInterface
	{
		$metadata = new stdClass();
		$metadata->fieldMappings = [
			[
				'fieldName' => 'name',
			],
			[
				'fieldName' => 'comment',
			],
			[
				'fieldName' => 'enabled',
			],
		];

		$entityManager = Mockery::mock(ORM\EntityManagerInterface::class);
		$entityManager
			->shouldReceive('getClassMetadata')
			->withArgs([Entities\Triggers\Trigger::class])
			->andReturn($metadata);

		if ($withUow) {
			$uow = Mockery::mock(ORM\UnitOfWork::class);
			$uow
				->shouldReceive('getEntityChangeSet')
				->andReturn(['name'])
				->times(1)
				->getMock()
				->shouldReceive('isScheduledForDelete')
				->andReturn(false)
				->getMock();

			$entityManager
				->shouldReceive('getUnitOfWork')
				->withNoArgs()
				->andReturn($uow)
				->times(1);
		}

		return $entityManager;
	}

	public function testPublishUpdatedEntity(): void
	{
		$publisher = Mockery::mock(ApplicationExchangePublisher\IPublisher::class);
		$publisher
			->shouldReceive('publish')
			->withArgs(function (string $key, array $data): bool {
				unset($data['id']);

				Assert::same('fb.bus.entity.updated.trigger', $key);
				Assert::equal([
					'name'     => 'Trigger name',
					'comment'  => null,
					'enabled'  => true,
					'owner'    => null,
					'type'     => 'channel-property',
					'device'   => 'cB8F0Q',
					'channel'  => '1B8F0Q',
					'property' => 'h1WQ0Q',
					'operand'  => '10',
					'operator' => 'eq',
				], $data);

				return true;
			})
			->times(1);

		$entityManager = $this->getEntityManager(true);

		$subscriber = new Subscribers\EntitiesSubscriber(
			$publisher,
			$entityManager
		);

		$entity = new Entities\Triggers\ChannelPropertyTrigger(
			'cB8F0Q',
			'1B8F0Q',
			'h1WQ0Q',
			Types\ConditionOperatorType::get(Types\ConditionOperatorType::OPERATOR_VALUE_EQUAL),
			'10',
			'Trigger name'
		);

		$eventArgs = Mockery::mock(ORM\Event\LifecycleEventArgs::class);
		$eventArgs
			->shouldReceive('getObject')
			->andReturn($entity)
			->times(1);

		$subscriber->postUpdate($eventArgs);
	}

	public function testPublishDeletedEntity(): void
	{
		$publisher = Mockery::mock(ApplicationExchangePublisher\IPublisher::class);
		$publisher
			->shouldReceive('publish')
			->withArgs(function (string $key, array $data): bool {
				unset($data['id']);

				Assert::same('fb.bus.entity.deleted.trigger', $key);
				Assert::equal([
					'name'     => 'Trigger name',
					'comment'  => null,
					'enabled'  => true,
					'owner'    => null,
					'type'     => 'channel-property',
					'device'   => 'cB8F0Q',
					'channel'  => '1B8F0Q',
					'property' => 'h1WQ0Q',
					'operand'  => '10',
					'operator' => 'eq',
				], $data);

				return true;
			})
			->times(1);

		$entity = new Entities\Triggers\ChannelPropertyTrigger(
			'cB8F0Q',
			'1B8F0Q',
			'h1WQ0Q',
			Types\ConditionOperatorType::get(Types\ConditionOperatorType::OPERATOR_VALUE_EQUAL),
			'10',
			'Trigger name'
		);

		$uow = Mockery::mock(ORM\UnitOfWork::class);
		$uow
			->shouldReceive('getScheduledEntityDeletions')
			->withNoArgs()
			->andReturn([$entity])
			->times(1)
			->getMock()
			->shouldReceive('getEntityIdentifier')
			->andReturn([
				123,
			])
			->times(1);

		$entityManager = $this->getEntityManager();
		$entityManager
			->shouldReceive('getUnitOfWork')
			->withNoArgs()
			->andReturn($uow)
			->times(1);

		$subscriber = new Subscribers\EntitiesSubscriber(
			$publisher,
			$entityManager
		);

		$subscriber->onFlush();
	}

}

$test_case = new EntitiesSubscriberTest();
$test_case->run();
