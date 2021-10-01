<?php declare(strict_types = 1);

/**
 * IControlRepository.php
 *
 * @license        More in LICENSE.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     Models
 * @since          0.4.0
 *
 * @date           01.10.21
 */

namespace FastyBird\TriggersModule\Models\Triggers\Controls;

use FastyBird\TriggersModule\Entities;
use FastyBird\TriggersModule\Queries;
use IPub\DoctrineOrmQuery;

/**
 * Trigger control repository interface
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Models
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IControlRepository
{

	/**
	 * @param Queries\FindTriggerControlsQuery $queryObject
	 *
	 * @return Entities\Triggers\Controls\IControl|null
	 */
	public function findOneBy(Queries\FindTriggerControlsQuery $queryObject): ?Entities\Triggers\Controls\IControl;

	/**
	 * @param Queries\FindTriggerControlsQuery $queryObject
	 *
	 * @return DoctrineOrmQuery\ResultSet
	 *
	 * @phpstan-return DoctrineOrmQuery\ResultSet<Entities\Triggers\Controls\IControl>
	 */
	public function getResultSet(
		Queries\FindTriggerControlsQuery $queryObject
	): DoctrineOrmQuery\ResultSet;

}
