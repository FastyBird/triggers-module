<?php declare(strict_types = 1);

/**
 * DevicePropertyAction.php
 *
 * @license        More in LICENSE.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     Entities
 * @since          0.4.1
 *
 * @date           06.10.21
 */

namespace FastyBird\TriggersModule\Entities\Actions;

use Doctrine\ORM\Mapping as ORM;
use FastyBird\TriggersModule\Entities;
use IPub\DoctrineCrud\Mapping\Annotation as IPubDoctrine;
use Ramsey\Uuid;
use Throwable;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="fb_actions_device_property",
 *     options={
 *       "collate"="utf8mb4_general_ci",
 *       "charset"="utf8mb4",
 *       "comment"="Devices actions"
 *     },
 *     indexes={
 *       @ORM\Index(name="action_device_idx", columns={"action_device"}),
 *       @ORM\Index(name="action_property_idx", columns={"action_device_property"})
 *     }
 * )
 */
class DevicePropertyAction extends Action implements IDevicePropertyAction
{

	/**
	 * @var Uuid\UuidInterface
	 *
	 * @IPubDoctrine\Crud(is="required")
	 * @ORM\Column(type="uuid_binary", name="action_device", nullable=true)
	 */
	private Uuid\UuidInterface $device;

	/**
	 * @var Uuid\UuidInterface
	 *
	 * @IPubDoctrine\Crud(is="required")
	 * @ORM\Column(type="uuid_binary", name="action_device_property", nullable=true)
	 */
	private Uuid\UuidInterface $property;

	/**
	 * @param Uuid\UuidInterface $device
	 * @param Uuid\UuidInterface $property
	 * @param string $value
	 * @param Entities\Triggers\ITrigger $trigger
	 * @param Uuid\UuidInterface|null $id
	 *
	 * @throws Throwable
	 */
	public function __construct(
		Uuid\UuidInterface $device,
		Uuid\UuidInterface $property,
		string $value,
		Entities\Triggers\ITrigger $trigger,
		?Uuid\UuidInterface $id = null
	) {
		parent::__construct($value, $trigger, $id);

		$this->device = $device;
		$this->property = $property;
	}

	/**
	 * {@inheritDoc}
	 */
	public function toArray(): array
	{
		return array_merge(parent::toArray(), [
			'type'     => 'device-property',
			'device'   => $this->getDevice()->toString(),
			'property' => $this->getProperty()->toString(),
		]);
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
	public function getProperty(): Uuid\UuidInterface
	{
		return $this->property;
	}

}