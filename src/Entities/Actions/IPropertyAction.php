<?php declare(strict_types = 1);

/**
 * IPropertyAction.php
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

use FastyBird\Metadata\Types as MetadataTypes;

/**
 * Property action entity interface
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Entities
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IPropertyAction extends IAction
{

	/**
	 * @return string|MetadataTypes\ButtonPayloadType|MetadataTypes\SwitchPayloadType
	 */
	public function getValue();

	/**
	 * @param string $value
	 *
	 * @return bool
	 */
	public function validate(string $value): bool;

}
