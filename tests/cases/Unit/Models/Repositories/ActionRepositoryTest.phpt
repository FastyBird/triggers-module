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
final class ActionRepositoryTest extends DbTestCase
{

	public function testReadOne(): void
	{
		/** @var Models\Actions\IActionRepository $repository */
		$repository = $this->getContainer()->getByType(Models\Actions\ActionRepository::class);

		$findQuery = new Queries\FindActionsQuery();
		$findQuery->byId(Uuid\Uuid::fromString('4aa84028-d8b7-4128-95b2-295763634aa4'));

		$entity = $repository->findOneBy($findQuery);

		Assert::true(is_object($entity));
		Assert::type(Entities\Actions\ChannelPropertyAction::class, $entity);
	}

	public function testReadResultSet(): void
	{
		/** @var Models\Actions\IActionRepository $repository */
		$repository = $this->getContainer()->getByType(Models\Actions\ActionRepository::class);

		$findQuery = new Queries\FindActionsQuery();

		$resultSet = $repository->getResultSet($findQuery);

		Assert::type(DoctrineOrmQuery\ResultSet::class, $resultSet);
		Assert::same(13, $resultSet->getTotalCount());
	}

}

$test_case = new ActionRepositoryTest();
$test_case->run();
