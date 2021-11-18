<?php declare(strict_types = 1);

/**
 * PropertyAction.php
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

use Consistence;
use Doctrine\ORM\Mapping as ORM;
use FastyBird\ModulesMetadata\Types as ModulesMetadataTypes;
use FastyBird\TriggersModule\Entities;
use IPub\DoctrineCrud\Mapping\Annotation as IPubDoctrine;
use Ramsey\Uuid;

/**
 * @ORM\MappedSuperclass
 */
abstract class PropertyAction extends Action implements IPropertyAction
{

	/**
	 * @var Uuid\UuidInterface
	 *
	 * @IPubDoctrine\Crud(is="required")
	 * @ORM\Column(type="uuid_binary", name="action_device", nullable=true)
	 */
	protected Uuid\UuidInterface $device;

	/**
	 * @var string
	 *
	 * @IPubDoctrine\Crud(is={"required", "writable"})
	 * @ORM\Column(type="string", name="action_value", length=100, nullable=true)
	 */
	protected string $value;

	/**
	 * @param Uuid\UuidInterface $device
	 * @param string $value
	 * @param Entities\Triggers\ITrigger $trigger
	 * @param Uuid\UuidInterface|null $id
	 */
	public function __construct(
		Uuid\UuidInterface $device,
		string $value,
		Entities\Triggers\ITrigger $trigger,
		?Uuid\UuidInterface $id = null
	) {
		parent::__construct($trigger, $id);

		$this->device = $device;
		$this->value = $value;
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
	public function getValue()
	{
		if (ModulesMetadataTypes\ButtonPayloadType::isValidValue($this->value)) {
			return ModulesMetadataTypes\ButtonPayloadType::get($this->value);
		}

		if (ModulesMetadataTypes\SwitchPayloadType::isValidValue($this->value)) {
			return ModulesMetadataTypes\SwitchPayloadType::get($this->value);
		}

		return $this->value;
	}

	/**
	 * {@inheritDoc}
	 */
	public function validate(string $value): bool
	{
		return (string) $this->value === $value;
	}

	/**
	 * {@inheritDoc}
	 */
	public function toArray(): array
	{
		return array_merge(parent::toArray(), [
			'device' => $this->getDevice()->toString(),
			'value'  => (string) $this->getValue(),
		]);
	}

}
