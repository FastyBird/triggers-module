<?php declare(strict_types = 1);

/**
 * IDevicePropertyCondition.php
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

namespace FastyBird\TriggersModule\Entities\Conditions;

/**
 * Device state condition entity interface
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Entities
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IDevicePropertyCondition extends IPropertyCondition
{

	/**
	 * @return string
	 */
	public function getDevice(): string;

	/**
	 * @return string
	 */
	public function getProperty(): string;

}
