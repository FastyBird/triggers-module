<?php declare(strict_types = 1);

/**
 * IAutomaticTrigger.php
 *
 * @license        More in LICENSE.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     Entities
 * @since          0.1.0
 *
 * @date           04.04.20
 */

namespace FastyBird\TriggersModule\Entities\Triggers;

use FastyBird\TriggersModule\Entities;

/**
 * Automatic trigger entity interface
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Entities
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IAutomaticTrigger extends ITrigger
{

	/**
	 * @param Entities\Conditions\ICondition[] $conditions
	 *
	 * @return void
	 */
	public function setConditions(array $conditions = []): void;

	/**
	 * @param Entities\Conditions\ICondition $condition
	 *
	 * @return void
	 */
	public function addCondition(Entities\Conditions\ICondition $condition): void;

	/**
	 * @return Entities\Conditions\ICondition[]
	 */
	public function getConditions(): array;

	/**
	 * @param string $id
	 *
	 * @return Entities\Conditions\ICondition|null
	 */
	public function getCondition(string $id): ?Entities\Conditions\ICondition;

	/**
	 * @param Entities\Conditions\ICondition $condition
	 *
	 * @return void
	 */
	public function removeCondition(Entities\Conditions\ICondition $condition): void;

}
