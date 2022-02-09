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
final class TriggerRepositoryTest extends DbTestCase
{

	public function testReadOne(): void
	{
		/** @var Models\Triggers\ITriggersRepository $repository */
		$repository = $this->getContainer()->getByType(Models\Triggers\TriggersRepository::class);

		$findQuery = new Queries\FindTriggersQuery();
		$findQuery->byId(Uuid\Uuid::fromString('0b48dfbc-fac2-4292-88dc-7981a121602d'));

		$entity = $repository->findOneBy($findQuery);

		Assert::true(is_object($entity));
		Assert::type(Entities\Triggers\Trigger::class, $entity);
		Assert::same('Good Evening', $entity->getName());
	}

	public function testReadResultSet(): void
	{
		/** @var Models\Triggers\ITriggersRepository $repository */
		$repository = $this->getContainer()->getByType(Models\Triggers\TriggersRepository::class);

		$findQuery = new Queries\FindTriggersQuery();

		$resultSet = $repository->getResultSet($findQuery);

		Assert::type(DoctrineOrmQuery\ResultSet::class, $resultSet);
		Assert::same(6, $resultSet->getTotalCount());
	}

}

$test_case = new TriggerRepositoryTest();
$test_case->run();
