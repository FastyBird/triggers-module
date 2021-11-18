<?php declare(strict_types = 1);

/**
 * PropertyCondition.php
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

use Consistence\Doctrine\Enum\EnumAnnotation as Enum;
use Doctrine\ORM\Mapping as ORM;
use FastyBird\ModulesMetadata\Types as ModulesMetadataTypes;
use FastyBird\TriggersModule\Entities;
use IPub\DoctrineCrud\Mapping\Annotation as IPubDoctrine;
use Ramsey\Uuid;
use Throwable;

/**
 * @ORM\MappedSuperclass
 */
abstract class PropertyCondition extends Condition implements IPropertyCondition
{

	/**
	 * @var Uuid\UuidInterface
	 *
	 * @IPubDoctrine\Crud(is="required")
	 * @ORM\Column(type="uuid_binary", name="condition_device", nullable=true)
	 */
	protected Uuid\UuidInterface $device;

	/**
	 * @var ModulesMetadataTypes\TriggerConditionOperatorType
	 *
	 * @IPubDoctrine\Crud(is={"required", "writable"})
	 * @Enum(class=ModulesMetadataTypes\TriggerConditionOperatorType::class)
	 * @ORM\Column(type="string_enum", name="condition_operator", length=15, nullable=true)
	 */
	protected $operator;

	/**
	 * @var string
	 *
	 * @IPubDoctrine\Crud(is={"required", "writable"})
	 * @ORM\Column(type="string", name="condition_operand", length=20, nullable=true)
	 */
	protected string $operand;

	/**
	 * @param ModulesMetadataTypes\TriggerConditionOperatorType $operator
	 * @param string $operand
	 * @param Entities\Triggers\IAutomaticTrigger $trigger
	 * @param Uuid\UuidInterface|null $id
	 *
	 * @throws Throwable
	 */
	public function __construct(
		Uuid\UuidInterface $device,
		ModulesMetadataTypes\TriggerConditionOperatorType $operator,
		string $operand,
		Entities\Triggers\IAutomaticTrigger $trigger,
		?Uuid\UuidInterface $id = null
	) {
		parent::__construct($trigger, $id);

		$this->device = $device;
		$this->operator = $operator;
		$this->operand = $operand;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getDevice(): Uuid\UuidInterface
	{
		return $this->device;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getOperator(): ModulesMetadataTypes\TriggerConditionOperatorType
	{
		return $this->operator;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setOperator(ModulesMetadataTypes\TriggerConditionOperatorType $operator): void
	{
		$this->operator = $operator;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getOperand()
	{
		if (ModulesMetadataTypes\ButtonPayloadType::isValidValue($this->operand)) {
			return ModulesMetadataTypes\ButtonPayloadType::get($this->operand);
		}

		if (ModulesMetadataTypes\SwitchPayloadType::isValidValue($this->operand)) {
			return ModulesMetadataTypes\SwitchPayloadType::get($this->operand);
		}

		return $this->operand;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setOperand(string $operand): void
	{
		$this->operand = $operand;
	}

	/**
	 * {@inheritDoc}
	 */
	public function validate(string $value): bool
	{
		if ($this->operator->equalsValue(ModulesMetadataTypes\TriggerConditionOperatorType::OPERATOR_VALUE_EQUAL)) {
			return (string) $this->operand === $value;
		}

		if ($this->operator->equalsValue(ModulesMetadataTypes\TriggerConditionOperatorType::OPERATOR_VALUE_ABOVE)) {
			return (float) ((string) $this->operand) < (float) $value;
		}

		if ($this->operator->equalsValue(ModulesMetadataTypes\TriggerConditionOperatorType::OPERATOR_VALUE_BELOW)) {
			return (float) ((string) $this->operand) > (float) $value;
		}

		return false;
	}

	/**
	 * {@inheritDoc}
	 */
	public function toArray(): array
	{
		return array_merge(parent::toArray(), [
			'device'   => $this->getDevice()->toString(),
			'operator' => $this->getOperator()->getValue(),
			'operand'  => (string) $this->getOperand(),
		]);
	}

}
