<?php declare(strict_types = 1);

/**
 * Constants.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     common
 * @since          0.1.0
 *
 * @date           05.04.20
 */

namespace FastyBird\TriggersModule;

/**
 * Service constants
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     common
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
final class Constants
{

	/**
	 * Module routing
	 */

	public const ROUTE_NAME_TRIGGERS = 'triggers';
	public const ROUTE_NAME_TRIGGER = 'trigger';
	public const ROUTE_NAME_TRIGGER_RELATIONSHIP = 'trigger.relationship';

	/**
	 * Data types
	 */
	public const DATA_TYPE_INTEGER = 'integer';
	public const DATA_TYPE_FLOAT = 'float';
	public const DATA_TYPE_BOOLEAN = 'boolean';
	public const DATA_TYPE_STRING = 'string';
	public const DATA_TYPE_ENUM = 'enum';
	public const DATA_TYPE_COLOR = 'color';

}
