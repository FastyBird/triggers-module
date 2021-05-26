<?php declare(strict_types = 1);

/**
 * IActionRepository.php
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

namespace FastyBird\TriggersModule\Models\Actions;

use FastyBird\TriggersModule\Entities;
use FastyBird\TriggersModule\Queries;
use IPub\DoctrineOrmQuery;

/**
 * Action repository interface
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Models
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IActionRepository
{

	/**
	 * @param Queries\FindActionsQuery $queryObject
	 * @param string $type
	 *
	 * @return Entities\Actions\IAction|null
	 *
	 * @phpstan-param class-string $type
	 */
	public function findOneBy(
		Queries\FindActionsQuery $queryObject,
		string $type = Entities\Actions\Action::class
	): ?Entities\Actions\IAction;

	/**
	 * @param Queries\FindActionsQuery $queryObject
	 * @param string $type
	 *
	 * @return Entities\Actions\IAction[]
	 *
	 * @phpstan-param class-string $type
	 */
	public function findAllBy(
		Queries\FindActionsQuery $queryObject,
		string $type = Entities\Actions\Action::class
	): array;

	/**
	 * @param Queries\FindActionsQuery $queryObject
	 * @param string $type
	 *
	 * @return DoctrineOrmQuery\ResultSet
	 *
	 * @phpstan-param class-string $type
	 *
	 * @phpstan-return DoctrineOrmQuery\ResultSet<Entities\Actions\Action>
	 */
	public function getResultSet(
		Queries\FindActionsQuery $queryObject,
		string $type = Entities\Actions\Action::class
	): DoctrineOrmQuery\ResultSet;

}
