<?php declare(strict_types = 1);

/**
 * ITriggerItem.php
 *
 * @license        More in LICENSE.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     States
 * @since          0.3.0
 *
 * @date           13.09.20
 */

namespace FastyBird\TriggersModule\States;

use Ramsey\Uuid;

/**
 * Property interface
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     States
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface ITriggerItem
{

	/**
	 * @return Uuid\UuidInterface
	 */
	public function getId(): Uuid\UuidInterface;

	/**
	 * @param bool $result
	 *
	 * @return void
	 */
	public function setValidationResult(bool $result): void;

	/**
	 * @return bool
	 */
	public function getValidationResult(): bool;

	/**
	 * @return mixed[]
	 */
	public function toArray(): array;

}
