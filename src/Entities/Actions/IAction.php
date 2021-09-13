<?php declare(strict_types = 1);

/**
 * IAction.php
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

namespace FastyBird\TriggersModule\Entities\Actions;

use FastyBird\TriggersModule\Entities;
use IPub\DoctrineTimestampable;

/**
 * Base action entity interface
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Entities
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IAction extends Entities\IEntity,
	DoctrineTimestampable\Entities\IEntityCreated, DoctrineTimestampable\Entities\IEntityUpdated
{

	/**
	 * @return Entities\Triggers\ITrigger
	 */
	public function getTrigger(): Entities\Triggers\ITrigger;

	/**
	 * @param bool $enabled
	 *
	 * @return void
	 */
	public function setEnabled(bool $enabled): void;

	/**
	 * @return bool
	 */
	public function isEnabled(): bool;

	/**
	 * @return string
	 */
	public function getValue(): string;

	/**
	 * @param string $value
	 *
	 * @return bool
	 */
	public function validate(string $value): bool;

	/**
	 * @return mixed[]
	 */
	public function toArray(): array;

}
