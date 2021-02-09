<?php declare(strict_types = 1);

/**
 * ConditionOperatorType.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     Types
 * @since          0.1.0
 *
 * @date           04.04.20
 */

namespace FastyBird\TriggersModule\Types;

use Consistence;

/**
 * Doctrine2 DB type for trigger condition operator column
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Types
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
class ConditionOperatorType extends Consistence\Enum\Enum
{

	/**
	 * Define states
	 */
	public const OPERATOR_VALUE_EQUAL = 'eq';
	public const OPERATOR_VALUE_ABOVE = 'above';
	public const OPERATOR_VALUE_BELOW = 'below';

	/**
	 * @return string
	 */
	public function __toString(): string
	{
		return (string) self::getValue();
	}

}
