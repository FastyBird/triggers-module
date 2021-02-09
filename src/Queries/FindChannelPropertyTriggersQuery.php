<?php declare(strict_types = 1);

/**
 * FindChannelPropertyTriggersQuery.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     Queries
 * @since          0.1.0
 *
 * @date           05.04.20
 */

namespace FastyBird\TriggersModule\Queries;

use Doctrine\ORM;
use FastyBird\TriggersModule\Entities;

/**
 * Find channel property trigger entities query
 *
 * @package          FastyBird:TriggersModule!
 * @subpackage       Queries
 *
 * @author           Adam Kadlec <adam.kadlec@fastybird.com>
 *
 * @phpstan-template T of Entities\Triggers\ChannelPropertyTrigger
 * @phpstan-extends  FindTriggersQuery<T>
 */
class FindChannelPropertyTriggersQuery extends FindTriggersQuery
{

	/**
	 * @param string $device
	 *
	 * @return void
	 */
	public function forDevice(string $device): void
	{
		$this->filter[] = function (ORM\QueryBuilder $qb) use ($device): void {
			$qb->andWhere('cpt.device = :device')->setParameter('device', $device);
		};
	}

	/**
	 * @param string $channel
	 *
	 * @return void
	 */
	public function forChannel(string $channel): void
	{
		$this->filter[] = function (ORM\QueryBuilder $qb) use ($channel): void {
			$qb->andWhere('cpt.channel = :channel')->setParameter('channel', $channel);
		};
	}

	/**
	 * @param string $property
	 *
	 * @return void
	 */
	public function forProperty(string $property): void
	{
		$this->filter[] = function (ORM\QueryBuilder $qb) use ($property): void {
			$qb->andWhere('cpt.property = :property')->setParameter('property', $property);
		};
	}

	/**
	 * @param ORM\EntityRepository $repository
	 *
	 * @return ORM\QueryBuilder
	 *
	 * @phpstan-param ORM\EntityRepository<T> $repository
	 */
	protected function createBasicDql(ORM\EntityRepository $repository): ORM\QueryBuilder
	{
		$qb = $repository->createQueryBuilder('t');
		$qb->select('cpt');
		$qb->leftJoin(Entities\Triggers\ChannelPropertyTrigger::class, 'cpt', ORM\Query\Expr\Join::WITH, 't = cpt');

		foreach ($this->filter as $modifier) {
			$modifier($qb);
		}

		return $qb;
	}

}
