<?php declare(strict_types = 1);

/**
 * ITriggerRepository.php
 *
 * @license        More in LICENSE.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     Models
 * @since          0.1.0
 *
 * @date           04.04.20
 */

namespace FastyBird\TriggersModule\Models\Triggers;

use FastyBird\TriggersModule\Entities;
use FastyBird\TriggersModule\Queries;
use IPub\DoctrineOrmQuery;

/**
 * Trigger repository interface
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Models
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface ITriggerRepository
{

	/**
	 * @param Queries\FindTriggersQuery $queryObject
	 * @param string $type
	 *
	 * @return Entities\Triggers\ITrigger|null
	 *
	 * @phpstan-param class-string $type
	 */
	public function findOneBy(
		Queries\FindTriggersQuery $queryObject,
		string $type = Entities\Triggers\Trigger::class
	): ?Entities\Triggers\ITrigger;

	/**
	 * @param Queries\FindTriggersQuery $queryObject
	 * @param string $type
	 *
	 * @return Entities\Triggers\ITrigger[]
	 *
	 * @phpstan-param class-string $type
	 */
	public function findAllBy(
		Queries\FindTriggersQuery $queryObject,
		string $type = Entities\Triggers\Trigger::class
	): array;

	/**
	 * @param Queries\FindTriggersQuery $queryObject
	 * @param string $type
	 *
	 * @return DoctrineOrmQuery\ResultSet
	 *
	 * @phpstan-param class-string $type
	 *
	 * @phpstan-return DoctrineOrmQuery\ResultSet<Entities\Triggers\ITrigger>
	 */
	public function getResultSet(
		Queries\FindTriggersQuery $queryObject,
		string $type = Entities\Triggers\Trigger::class
	): DoctrineOrmQuery\ResultSet;

}
