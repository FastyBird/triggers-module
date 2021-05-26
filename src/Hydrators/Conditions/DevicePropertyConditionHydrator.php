<?php declare(strict_types = 1);

/**
 * DevicePropertyConditionHydrator.php
 *
 * @license        More in LICENSE.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     Hydrators
 * @since          0.1.0
 *
 * @date           04.04.20
 */

namespace FastyBird\TriggersModule\Hydrators\Conditions;

use FastyBird\TriggersModule\Entities;

/**
 * Device property condition entity hydrator
 *
 * @package         FastyBird:TriggersModule!
 * @subpackage      Hydrators
 *
 * @author          Adam Kadlec <adam.kadlec@fastybird.com>
 *
 * @phpstan-extends PropertyConditionHydrator<Entities\Conditions\DevicePropertyCondition>
 */
final class DevicePropertyConditionHydrator extends PropertyConditionHydrator
{

	/** @var string[] */
	protected array $attributes = [
		'device',
		'property',
		'operator',
		'operand',
		'enabled',
	];

	/**
	 * {@inheritDoc}
	 */
	protected function getEntityName(): string
	{
		return Entities\Conditions\DevicePropertyCondition::class;
	}

}
