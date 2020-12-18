<?php declare(strict_types = 1);

/**
 * FindActionsQuery.php
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
 * @phpstan-template T of Entities\Actions\Action
 * @phpstan-extends  DoctrineOrmQuery\QueryObject<T>
 */
class FindActionsQuery extends DoctrineOrmQuery\QueryObject
{

	/** @var Closure[] */
	private array $filter = [];

	/** @var Closure[] */
	private array $select = [];

	/**
	 * @param Uuid\UuidInterface $id
	 *
	 * @return void
	 */
	public function byId(Uuid\UuidInterface $id): void
	{
		$this->filter[] = function (ORM\QueryBuilder $qb) use ($id): void {
			$qb->andWhere('a.id = :id')->setParameter('id', $id, Uuid\Doctrine\UuidBinaryType::NAME);
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
	 * @param string $device
	 *
	 * @return void
	 */
	public function forDevice(string $device): void
	{
		$this->filter[] = function (ORM\QueryBuilder $qb) use ($device): void {
			$qb->andWhere('a.device = :device')->setParameter('device', $device);
		};
	}

	/**
	 * @param string $device
	 * @param string $channel
	 *
	 * @return void
	 */
	public function forChannel(string $device, string $channel): void
	{
		$this->filter[] = function (ORM\QueryBuilder $qb) use ($device, $channel): void {
			$qb->andWhere('a.device = :device')->setParameter('device', $device);
			$qb->andWhere('a.channel = :channel')->setParameter('channel', $channel);
		};
	}

	/**
	 * @param string $device
	 * @param string $channel
	 * @param string $property
	 *
	 * @return void
	 */
	public function forChannelProperty(string $device, string $channel, string $property): void
	{
		$this->filter[] = function (ORM\QueryBuilder $qb) use ($device, $channel, $property): void {
			$qb->andWhere('a.device = :device')->setParameter('device', $device);
			$qb->andWhere('a.channel = :channel')->setParameter('channel', $channel);
			$qb->andWhere('a.property = :property')->setParameter('property', $property);
		};
	}

	/**
	 * @param ORM\EntityRepository<Entities\Actions\Action> $repository
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
	 * @param ORM\EntityRepository<Entities\Actions\Action> $repository
	 *
	 * @return ORM\QueryBuilder
	 *
	 * @phpstan-param ORM\EntityRepository<T> $repository
	 */
	private function createBasicDql(ORM\EntityRepository $repository): ORM\QueryBuilder
	{
		$qb = $repository->createQueryBuilder('a');
		$qb->addSelect('trigger');
		$qb->join('a.trigger', 'trigger');

		foreach ($this->filter as $modifier) {
			$modifier($qb);
		}

		return $qb;
	}

	/**
	 * @param ORM\EntityRepository<Entities\Actions\Action> $repository
	 *
	 * @return ORM\QueryBuilder
	 *
	 * @phpstan-param ORM\EntityRepository<T> $repository
	 */
	protected function doCreateCountQuery(ORM\EntityRepository $repository): ORM\QueryBuilder
	{
		$qb = $this->createBasicDql($repository)->select('COUNT(a.id)');

		foreach ($this->select as $modifier) {
			$modifier($qb);
		}

		return $qb;
	}

}
