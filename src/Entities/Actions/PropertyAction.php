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

namespace FastyBird\Module\Triggers\Entities\Actions;

use Doctrine\ORM\Mapping as ORM;
use FastyBird\Library\Metadata\Types as MetadataTypes;
use FastyBird\Module\Triggers\Entities;
use IPub\DoctrineCrud\Mapping\Annotation as IPubDoctrine;
use Ramsey\Uuid;
use function array_merge;

/**
 * @ORM\MappedSuperclass
 */
abstract class PropertyAction extends Action
{

	/**
	 * @IPubDoctrine\Crud(is="required")
	 * @ORM\Column(type="uuid_binary", name="action_device", nullable=true)
	 */
	protected Uuid\UuidInterface $device;

	/**
	 * @IPubDoctrine\Crud(is={"required", "writable"})
	 * @ORM\Column(type="string", name="action_value", length=100, nullable=true)
	 */
	protected string $value;

	public function __construct(
		Uuid\UuidInterface $device,
		string $value,
		Entities\Triggers\Trigger $trigger,
		Uuid\UuidInterface|null $id = null,
	)
	{
		parent::__construct($trigger, $id);

		$this->device = $device;
		$this->value = $value;
	}

	public function getValue(): string|MetadataTypes\ButtonPayload|MetadataTypes\SwitchPayload
	{
		if (MetadataTypes\ButtonPayload::isValidValue($this->value)) {
			return MetadataTypes\ButtonPayload::get($this->value);
		}

		if (MetadataTypes\SwitchPayload::isValidValue($this->value)) {
			return MetadataTypes\SwitchPayload::get($this->value);
		}

		return $this->value;
	}

	public function validate(string $value): bool
	{
		return $this->value === $value;
	}

	public function getDevice(): Uuid\UuidInterface
	{
		return $this->device;
	}

	/**
	 * {@inheritDoc}
	 */
	public function toArray(): array
	{
		return array_merge(parent::toArray(), [
			'device' => $this->getDevice()->toString(),
			'value' => (string) $this->getValue(),
		]);
	}

}
