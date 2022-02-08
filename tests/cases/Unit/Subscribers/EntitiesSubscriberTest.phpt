<?php declare(strict_types = 1);

namespace Tests\Cases;

use Doctrine\ORM;
use FastyBird\Exchange\Publisher as ExchangePublisher;
use FastyBird\Metadata;
use FastyBird\TriggersModule\Entities;
use FastyBird\TriggersModule\Subscribers;
use Mockery;
use Nette\Utils;
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
		$publisher = Mockery::mock(ExchangePublisher\Publisher::class);
		$entityManager = Mockery::mock(ORM\EntityManagerInterface::class);

		$subscriber = new Subscribers\EntitiesSubscriber(
			$entityManager,
			$publisher
		);

		Assert::same(['onFlush', 'prePersist', 'postPersist', 'postUpdate'], $subscriber->getSubscribedEvents());
	}

	public function testPublishCreatedEntity(): void
	{
		$publisher = Mockery::mock(ExchangePublisher\Publisher::class);
		$publisher
			->shouldReceive('publish')
			->withArgs(function (string $source, string $key, Utils\ArrayHash $data): bool {
				Assert::same(Metadata\Constants::MODULE_TRIGGERS_SOURCE, $source);

				unset($data['id']);

				Assert::same(Metadata\Constants::MESSAGE_BUS_TRIGGERS_CREATED_ENTITY_ROUTING_KEY, $key);
				Assert::equal(Utils\ArrayHash::from([
					'name'    => 'Trigger name',
					'comment' => null,
					'enabled' => true,
					'owner'   => null,
					'type'    => 'manual',
				]), $data);

				return true;
			})
			->times(1);

		$entityManager = $this->getEntityManager();

		$subscriber = new Subscribers\EntitiesSubscriber(
			$entityManager,
			$publisher
		);

		$entity = new Entities\Triggers\ManualTrigger(
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
		$publisher = Mockery::mock(ExchangePublisher\Publisher::class);
		$publisher
			->shouldReceive('publish')
			->withArgs(function (string $source, string $key, Utils\ArrayHash $data): bool {
				Assert::same(Metadata\Constants::MODULE_TRIGGERS_SOURCE, $source);

				unset($data['id']);

				Assert::same(Metadata\Constants::MESSAGE_BUS_TRIGGERS_UPDATED_ENTITY_ROUTING_KEY, $key);
				Assert::equal(Utils\ArrayHash::from([
					'name'    => 'Trigger name',
					'comment' => null,
					'enabled' => true,
					'owner'   => null,
					'type'    => 'manual',
				]), $data);

				return true;
			})
			->times(1);

		$entityManager = $this->getEntityManager(true);

		$subscriber = new Subscribers\EntitiesSubscriber(
			$entityManager,
			$publisher
		);

		$entity = new Entities\Triggers\ManualTrigger(
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
		$publisher = Mockery::mock(ExchangePublisher\Publisher::class);
		$publisher
			->shouldReceive('publish')
			->withArgs(function (string $source, string $key, Utils\ArrayHash $data): bool {
				Assert::same(Metadata\Constants::MODULE_TRIGGERS_SOURCE, $source);

				unset($data['id']);

				Assert::same(Metadata\Constants::MESSAGE_BUS_TRIGGERS_DELETED_ENTITY_ROUTING_KEY, $key);
				Assert::equal(Utils\ArrayHash::from([
					'name'    => 'Trigger name',
					'comment' => null,
					'enabled' => true,
					'owner'   => null,
					'type'    => 'manual',
				]), $data);

				return true;
			})
			->times(1);

		$entity = new Entities\Triggers\ManualTrigger(
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
			$entityManager,
			$publisher
		);

		$subscriber->onFlush();
	}

}

$test_case = new EntitiesSubscriberTest();
$test_case->run();
