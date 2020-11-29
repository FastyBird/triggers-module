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
final class ConditionRepositoryTest extends DbTestCase
{

	public function testReadOne(): void
	{
		/** @var Models\Conditions\IConditionRepository $repository */
		$repository = $this->getContainer()->getByType(Models\Conditions\ConditionRepository::class);

		$findQuery = new Queries\FindConditionsQuery();
		$findQuery->byId(Uuid\Uuid::fromString('09c453b3-c55f-4050-8f1c-b50f8d5728c2'));

		$entity = $repository->findOneBy($findQuery);

		Assert::true(is_object($entity));
		Assert::type(Entities\Conditions\TimeCondition::class, $entity);
	}

	public function testReadResultSet(): void
	{
		/** @var Models\Conditions\IConditionRepository $repository */
		$repository = $this->getContainer()->getByType(Models\Conditions\ConditionRepository::class);

		$findQuery = new Queries\FindConditionsQuery();

		$resultSet = $repository->getResultSet($findQuery);

		Assert::type(DoctrineOrmQuery\ResultSet::class, $resultSet);
		Assert::same(3, $resultSet->getTotalCount());
	}

}

$test_case = new ConditionRepositoryTest();
$test_case->run();
