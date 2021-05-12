<?php declare(strict_types = 1);

/**
 * IConditionsManager.php
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
use Nette\Utils;

/**
 * Conditions entities manager interface
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Models
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IConditionsManager
{

	/**
	 * @param Utils\ArrayHash $values
	 *
	 * @return Entities\Conditions\ICondition
	 */
	public function create(
		Utils\ArrayHash $values
	): Entities\Conditions\ICondition;

	/**
	 * @param Entities\Conditions\ICondition $entity
	 * @param Utils\ArrayHash $values
	 *
	 * @return Entities\Conditions\ICondition
	 */
	public function update(
		Entities\Conditions\ICondition $entity,
		Utils\ArrayHash $values
	): Entities\Conditions\ICondition;

	/**
	 * @param Entities\Conditions\ICondition $entity
	 *
	 * @return bool
	 */
	public function delete(
		Entities\Conditions\ICondition $entity
	): bool;

}
