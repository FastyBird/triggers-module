<?php declare(strict_types = 1);

/**
 * ConditionRepository.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     Models
 * @since          0.1.0
 *
 * @date           04.04.20
 */

namespace FastyBird\TriggersModule\Models\Conditions;

use Doctrine\Common;
use Doctrine\Persistence;
use FastyBird\TriggersModule\Entities;
use FastyBird\TriggersModule\Exceptions;
use FastyBird\TriggersModule\Queries;
use IPub\DoctrineOrmQuery;
use Nette;
use Throwable;

/**
 * Condition repository
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Models
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
final class ConditionRepository implements IConditionRepository
{

	use Nette\SmartObject;

	/** @var Common\Persistence\ManagerRegistry */
	private Common\Persistence\ManagerRegistry $managerRegistry;

	/** @var Persistence\ObjectRepository<Entities\Conditions\Condition>[] */
	private array $repository = [];

	public function __construct(Common\Persistence\ManagerRegistry $managerRegistry)
	{
		$this->managerRegistry = $managerRegistry;
	}

	/**
	 * {@inheritDoc}
	 */
	public function findOneBy(
		Queries\FindConditionsQuery $queryObject,
		string $type = Entities\Conditions\Condition::class
	): ?Entities\Conditions\ICondition {
		/** @var Entities\Conditions\ICondition|null $condition */
		$condition = $queryObject->fetchOne($this->getRepository($type));

		return $condition;
	}

	/**
	 * @param string $type
	 *
	 * @return Persistence\ObjectRepository<Entities\Conditions\Condition>
	 *
	 * @phpstan-template T of Entities\Conditions\Condition
	 * @phpstan-param    class-string<T> $type
	 */
	private function getRepository(string $type): Persistence\ObjectRepository
	{
		if (!isset($this->repository[$type])) {
			$this->repository[$type] = $this->managerRegistry->getRepository($type);
		}

		return $this->repository[$type];
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws Throwable
	 */
	public function findAllBy(
		Queries\FindConditionsQuery $queryObject,
		string $type = Entities\Conditions\Condition::class
	): array {
		$result = $queryObject->fetch($this->getRepository($type));

		return is_array($result) ? $result : $result->toArray();
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws Throwable
	 */
	public function getResultSet(
		Queries\FindConditionsQuery $queryObject,
		string $type = Entities\Conditions\Condition::class
	): DoctrineOrmQuery\ResultSet {
		$result = $queryObject->fetch($this->getRepository($type));

		if (!$result instanceof DoctrineOrmQuery\ResultSet) {
			throw new Exceptions\InvalidStateException('Result set for given query could not be loaded.');
		}

		return $result;
	}

}
