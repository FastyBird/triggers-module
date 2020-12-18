<?php declare(strict_types = 1);

/**
 * TriggerRepository.php
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

namespace FastyBird\TriggersModule\Models\Triggers;

use Doctrine\Common;
use Doctrine\Persistence;
use FastyBird\TriggersModule\Entities;
use FastyBird\TriggersModule\Exceptions;
use FastyBird\TriggersModule\Queries;
use IPub\DoctrineOrmQuery;
use Nette;
use Throwable;

/**
 * Trigger repository
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Models
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
final class TriggerRepository implements ITriggerRepository
{

	use Nette\SmartObject;

	/** @var Common\Persistence\ManagerRegistry */
	private Common\Persistence\ManagerRegistry $managerRegistry;

	/** @var Persistence\ObjectRepository<Entities\Triggers\Trigger>[] */
	private array $repository = [];

	public function __construct(Common\Persistence\ManagerRegistry $managerRegistry)
	{
		$this->managerRegistry = $managerRegistry;
	}

	/**
	 * {@inheritDoc}
	 */
	public function findOneBy(
		Queries\FindTriggersQuery $queryObject,
		string $type = Entities\Triggers\Trigger::class
	): ?Entities\Triggers\ITrigger {
		/** @var Entities\Triggers\ITrigger|null $trigger */
		$trigger = $queryObject->fetchOne($this->getRepository($type));

		return $trigger;
	}

	/**
	 * @param string $type
	 *
	 * @return Persistence\ObjectRepository<Entities\Triggers\Trigger>
	 *
	 * @phpstan-template T of Entities\Triggers\Trigger
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
		Queries\FindTriggersQuery $queryObject,
		string $type = Entities\Triggers\Trigger::class
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
		Queries\FindTriggersQuery $queryObject,
		string $type = Entities\Triggers\Trigger::class
	): DoctrineOrmQuery\ResultSet {
		$result = $queryObject->fetch($this->getRepository($type));

		if (!$result instanceof DoctrineOrmQuery\ResultSet) {
			throw new Exceptions\InvalidStateException('Result set for given query could not be loaded.');
		}

		return $result;
	}

}
