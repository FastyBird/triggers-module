<?php declare(strict_types = 1);

/**
 * IPropertyCondition.php
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
use Ramsey\Uuid;

/**
 * Device or channel property condition entity interface
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Entities
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IPropertyCondition extends ICondition
{

	/**
	 * @return Uuid\UuidInterface
	 */
	public function getDevice(): Uuid\UuidInterface;

	/**
	 * @param ModulesMetadataTypes\TriggerConditionOperatorType $operator
	 *
	 * @return void
	 */
	public function setOperator(ModulesMetadataTypes\TriggerConditionOperatorType $operator): void;

	/**
	 * @return ModulesMetadataTypes\TriggerConditionOperatorType
	 */
	public function getOperator(): ModulesMetadataTypes\TriggerConditionOperatorType;

	/**
	 * @param string $operand
	 *
	 * @return void
	 */
	public function setOperand(string $operand): void;

	/**
	 * @return string|ModulesMetadataTypes\ButtonPayloadType|ModulesMetadataTypes\SwitchPayloadType
	 */
	public function getOperand();

	/**
	 * @param string $value
	 *
	 * @return bool
	 */
	public function validate(string $value): bool;

}
