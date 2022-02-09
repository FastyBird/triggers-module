<?php declare(strict_types = 1);

namespace Tests\Cases;

use FastyBird\TriggersModule\Entities;
use FastyBird\TriggersModule\Models;
use FastyBird\TriggersModule\Queries;
use IPub\DoctrineOrmQuery;
use Ramsey\Uuid;
use Tester\Assert;

require_once __DIR__ . '/../../../../bootstrap.php';
require_once __DIR__ . '/../../DbTestCase.php';

/**
 * @testCase
 */
final class NotificationRepositoryTest extends DbTestCase
{

	public function testReadOne(): void
	{
		/** @var Models\Notifications\INotificationsRepository $repository */
		$repository = $this->getContainer()->getByType(Models\Notifications\NotificationsRepository::class);

		$findQuery = new Queries\FindNotificationsQuery();
		$findQuery->byId(Uuid\Uuid::fromString('05f28df9-5f19-4923-b3f8-b9090116dadc'));

		$entity = $repository->findOneBy($findQuery);

		Assert::true(is_object($entity));
		Assert::type(Entities\Notifications\EmailNotification::class, $entity);

		$findQuery = new Queries\FindNotificationsQuery();
		$findQuery->byId(Uuid\Uuid::fromString('4fe1019c-f49e-4cbf-83e6-20b394e76317'));

		$entity = $repository->findOneBy($findQuery);

		Assert::true(is_object($entity));
		Assert::type(Entities\Notifications\SmsNotification::class, $entity);
	}

	public function testReadResultSet(): void
	{
		/** @var Models\Notifications\INotificationsRepository $repository */
		$repository = $this->getContainer()->getByType(Models\Notifications\NotificationsRepository::class);

		$findQuery = new Queries\FindNotificationsQuery();

		$resultSet = $repository->getResultSet($findQuery);

		Assert::type(DoctrineOrmQuery\ResultSet::class, $resultSet);
		Assert::same(2, $resultSet->getTotalCount());
	}

}

$test_case = new NotificationRepositoryTest();
$test_case->run();
