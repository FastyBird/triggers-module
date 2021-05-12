<?php declare(strict_types = 1);

/**
 * ITriggersManager.php
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
use Nette\Utils;

/**
 * Triggers entities manager interface
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Models
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface ITriggersManager
{

	/**
	 * @param Utils\ArrayHash $values
	 *
	 * @return Entities\Triggers\ITrigger
	 */
	public function create(
		Utils\ArrayHash $values
	): Entities\Triggers\ITrigger;

	/**
	 * @param Entities\Triggers\ITrigger $entity
	 * @param Utils\ArrayHash $values
	 *
	 * @return Entities\Triggers\ITrigger
	 */
	public function update(
		Entities\Triggers\ITrigger $entity,
		Utils\ArrayHash $values
	): Entities\Triggers\ITrigger;

	/**
	 * @param Entities\Triggers\ITrigger $entity
	 *
	 * @return bool
	 */
	public function delete(
		Entities\Triggers\ITrigger $entity
	): bool;

}
