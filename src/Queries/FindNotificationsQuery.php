<?php declare(strict_types = 1);

/**
 * FindNotificationsQuery.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     Queries
 * @since          0.1.0
 *
 * @date           04.04.20
 */

namespace FastyBird\TriggersModule\Queries;

use Closure;
use Doctrine\ORM;
use FastyBird\TriggersModule\Entities;
use IPub\DoctrineOrmQuery;
use Ramsey\Uuid;

/**
 * Find action entities query
 *
 * @package          FastyBird:TriggersModule!
 * @subpackage       Queries
 *
 * @author           Adam Kadlec <adam.kadlec@fastybird.com>
 *
 * @phpstan-template T of Entities\Notifications\Notification
 * @phpstan-extends  DoctrineOrmQuery\QueryObject<T>
 */
class FindNotificationsQuery extends DoctrineOrmQuery\QueryObject
{

	/** @var Closure[] */
	private $filter = [];

	/** @var Closure[] */
	private $select = [];

	/**
	 * @param Uuid\UuidInterface $id
	 *
	 * @return void
	 */
	public function byId(Uuid\UuidInterface $id): void
	{
		$this->filter[] = function (ORM\QueryBuilder $qb) use ($id): void {
			$qb->andWhere('n.id = :id')->setParameter('id', $id, Uuid\Doctrine\UuidBinaryType::NAME);
		};
	}

	/**
	 * @param Entities\Triggers\ITrigger $trigger
	 *
	 * @return void
	 */
	public function forTrigger(Entities\Triggers\ITrigger $trigger): void
	{
		$this->filter[] = function (ORM\QueryBuilder $qb) use ($trigger): void {
			$qb->andWhere('trigger.id = :trigger')->setParameter('trigger', $trigger->getId(), Uuid\Doctrine\UuidBinaryType::NAME);
		};
	}

	/**
	 * @param ORM\EntityRepository<Entities\Notifications\Notification> $repository
	 *
	 * @return ORM\QueryBuilder
	 *
	 * @phpstan-param ORM\EntityRepository<T> $repository
	 */
	protected function doCreateQuery(ORM\EntityRepository $repository): ORM\QueryBuilder
	{
		$qb = $this->createBasicDql($repository);

		foreach ($this->select as $modifier) {
			$modifier($qb);
		}

		return $qb;
	}

	/**
	 * @param ORM\EntityRepository<Entities\Notifications\Notification> $repository
	 *
	 * @return ORM\QueryBuilder
	 *
	 * @phpstan-param ORM\EntityRepository<T> $repository
	 */
	private function createBasicDql(ORM\EntityRepository $repository): ORM\QueryBuilder
	{
		$qb = $repository->createQueryBuilder('n');
		$qb->addSelect('trigger');
		$qb->join('n.trigger', 'trigger');

		foreach ($this->filter as $modifier) {
			$modifier($qb);
		}

		return $qb;
	}

	/**
	 * @param ORM\EntityRepository<Entities\Notifications\Notification> $repository
	 *
	 * @return ORM\QueryBuilder
	 *
	 * @phpstan-param ORM\EntityRepository<T> $repository
	 */
	protected function doCreateCountQuery(ORM\EntityRepository $repository): ORM\QueryBuilder
	{
		$qb = $this->createBasicDql($repository)->select('COUNT(n.id)');

		foreach ($this->select as $modifier) {
			$modifier($qb);
		}

		return $qb;
	}

}
