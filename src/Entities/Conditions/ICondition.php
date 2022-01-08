<?php declare(strict_types = 1);

/**
 * ICondition.php
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

use FastyBird\ModulesMetadata\Types as ModulesMetadataTypes;
use FastyBird\TriggersModule\Entities;
use IPub\DoctrineTimestampable;

/**
 * Base condition entity interface
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Entities
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface ICondition extends Entities\IEntity,
	DoctrineTimestampable\Entities\IEntityCreated, DoctrineTimestampable\Entities\IEntityUpdated
{

	/**
	 * @return ModulesMetadataTypes\TriggerConditionTypeType
	 */
	public function getType(): ModulesMetadataTypes\TriggerConditionTypeType;

	/**
	 * @return Entities\Triggers\IAutomaticTrigger
	 */
	public function getTrigger(): Entities\Triggers\IAutomaticTrigger;

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
	 * @return mixed[]
	 */
	public function toArray(): array;

}
