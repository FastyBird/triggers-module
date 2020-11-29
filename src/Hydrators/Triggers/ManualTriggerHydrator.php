<?php declare(strict_types = 1);

/**
 * ManualTriggerHydrator.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     Hydrators
 * @since          0.1.0
 *
 * @date           04.04.20
 */

namespace FastyBird\TriggersModule\Hydrators\Triggers;

use FastyBird\TriggersModule\Entities;

/**
 * Manual trigger entity hydrator
 *
 * @package         FastyBird:TriggersModule!
 * @subpackage      Hydrators
 *
 * @author          Adam Kadlec <adam.kadlec@fastybird.com>
 */
final class ManualTriggerHydrator extends TriggerHydrator
{

	/**
	 * {@inheritDoc}
	 */
	protected function getEntityName(): string
	{
		return Entities\Triggers\ManualTrigger::class;
	}

}
