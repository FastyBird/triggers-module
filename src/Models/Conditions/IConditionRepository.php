<?php declare(strict_types = 1);

/**
 * IConditionRepository.php
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

namespace FastyBird\TriggersModule\Models\Conditions;

use FastyBird\TriggersModule\Entities;
use FastyBird\TriggersModule\Queries;
use IPub\DoctrineOrmQuery;

/**
 * Condition repository interface
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Models
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IConditionRepository
{

	/**
	 * @param Queries\FindConditionsQuery $queryObject
	 * @param string $type
	 *
	 * @return Entities\Conditions\ICondition|null
	 *
	 * @phpstan-param class-string $type
	 */
	public function findOneBy(
		Queries\FindConditionsQuery $queryObject,
		string $type = Entities\Conditions\Condition::class
	): ?Entities\Conditions\ICondition;

	/**
	 * @param Queries\FindConditionsQuery $queryObject
	 * @param string $type
	 *
	 * @return Entities\Conditions\ICondition[]
	 *
	 * @phpstan-param class-string $type
	 */
	public function findAllBy(
		Queries\FindConditionsQuery $queryObject,
		string $type = Entities\Conditions\Condition::class
	): array;

	/**
	 * @param Queries\FindConditionsQuery $queryObject
	 * @param string $type
	 *
	 * @return DoctrineOrmQuery\ResultSet
	 *
	 * @phpstan-param class-string $type
	 *
	 * @phpstan-return DoctrineOrmQuery\ResultSet<Entities\Conditions\Condition>
	 */
	public function getResultSet(
		Queries\FindConditionsQuery $queryObject,
		string $type = Entities\Conditions\Condition::class
	): DoctrineOrmQuery\ResultSet;

}
