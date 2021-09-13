<?php declare(strict_types = 1);

/**
 * ITriggersItemsManager.php
 *
 * @license        More in LICENSE.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     Models
 * @since          0.3.0
 *
 * @date           13.09.20
 */

namespace FastyBird\TriggersModule\Models\States;

use FastyBird\TriggersModule\States;
use Nette\Utils;
use Ramsey\Uuid;

/**
 * Base trigger item manager interface
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Models
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface ITriggersItemsManager
{

	/**
	 * @param Uuid\UuidInterface $id
	 * @param Utils\ArrayHash $values
	 *
	 * @return States\ITriggerItem
	 */
	public function create(
		Uuid\UuidInterface $id,
		Utils\ArrayHash $values
	): States\ITriggerItem;

	/**
	 * @param States\ITriggerItem $state
	 * @param Utils\ArrayHash $values
	 *
	 * @return States\ITriggerItem
	 */
	public function update(
		States\ITriggerItem $state,
		Utils\ArrayHash $values
	): States\ITriggerItem;

	/**
	 * @param States\ITriggerItem $state
	 * @param Utils\ArrayHash $values
	 *
	 * @return States\ITriggerItem
	 */
	public function updateState(
		States\ITriggerItem $state,
		Utils\ArrayHash $values
	): States\ITriggerItem;

	/**
	 * @param States\ITriggerItem $state
	 *
	 * @return bool
	 */
	public function delete(
		States\ITriggerItem $state
	): bool;

}
