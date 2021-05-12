<?php declare(strict_types = 1);

/**
 * IActionsManager.php
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
use Nette\Utils;

/**
 * Actions entities manager interface
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Models
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IActionsManager
{

	/**
	 * @param Utils\ArrayHash $values
	 *
	 * @return Entities\Actions\IAction
	 */
	public function create(
		Utils\ArrayHash $values
	): Entities\Actions\IAction;

	/**
	 * @param Entities\Actions\IAction $entity
	 * @param Utils\ArrayHash $values
	 *
	 * @return Entities\Actions\IAction
	 */
	public function update(
		Entities\Actions\IAction $entity,
		Utils\ArrayHash $values
	): Entities\Actions\IAction;

	/**
	 * @param Entities\Actions\IAction $entity
	 *
	 * @return bool
	 */
	public function delete(
		Entities\Actions\IAction $entity
	): bool;

}
