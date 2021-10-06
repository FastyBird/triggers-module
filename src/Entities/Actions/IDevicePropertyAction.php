<?php declare(strict_types = 1);

/**
 * IDevicePropertyAction.php
 *
 * @license        More in LICENSE.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     Entities
 * @since          0.4.1
 *
 * @date           06.10.21
 */

namespace FastyBird\TriggersModule\Entities\Actions;

use Ramsey\Uuid;

/**
 * Device property action entity interface
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Entities
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IDevicePropertyAction extends IAction
{

	/**
	 * @return Uuid\UuidInterface
	 */
	public function getDevice(): Uuid\UuidInterface;

	/**
	 * @return Uuid\UuidInterface
	 */
	public function getProperty(): Uuid\UuidInterface;

}
