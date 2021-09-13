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
final class ActionEntityTest extends DbTestCase
{

	public function testValidation(): void
	{
		/** @var Models\Actions\IActionRepository $repository */
		$repository = $this->getContainer()->getByType(Models\Actions\ActionRepository::class);

		$findQuery = new Queries\FindActionsQuery();
		$findQuery->byId(Uuid\Uuid::fromString('4aa84028-d8b7-4128-95b2-295763634aa4'));

		$entity = $repository->findOneBy($findQuery);

		Assert::true(is_object($entity));
		Assert::type(Entities\Actions\ChannelPropertyAction::class, $entity);

		Assert::true($entity->validate('on'));

		Assert::false($entity->validate('off'));
	}

}

$test_case = new ActionEntityTest();
$test_case->run();
