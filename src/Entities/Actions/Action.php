<?php declare(strict_types = 1);

/**
 * Action.php
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

use Doctrine\ORM\Mapping as ORM;
use FastyBird\TriggersModule\Entities;
use IPub\DoctrineCrud\Mapping\Annotation as IPubDoctrine;
use IPub\DoctrineTimestampable;
use Ramsey\Uuid;
use Throwable;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="fb_actions",
 *     options={
 *       "collate"="utf8mb4_general_ci",
 *       "charset"="utf8mb4",
 *       "comment"="Triggers actions"
 *     }
 * )
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="action_type", type="string", length=20)
 * @ORM\DiscriminatorMap({
 *    "channel-property" = "FastyBird\TriggersModule\Entities\Actions\ChannelPropertyAction"
 * })
 * @ORM\MappedSuperclass
 */
abstract class Action implements IAction
{

	use Entities\TEntity;
	use DoctrineTimestampable\Entities\TEntityCreated;
	use DoctrineTimestampable\Entities\TEntityUpdated;

	/**
	 * @var Uuid\UuidInterface
	 *
	 * @ORM\Id
	 * @ORM\Column(type="uuid_binary", name="action_id")
	 * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
	 */
	protected Uuid\UuidInterface $id;

	/**
	 * @var bool
	 *
	 * @IPubDoctrine\Crud(is="writable")
	 * @ORM\Column(type="boolean", name="action_enabled", length=1, nullable=false, options={"default": true})
	 */
	protected bool $enabled = true;

	/**
	 * @var string
	 *
	 * @IPubDoctrine\Crud(is={"required", "writable"})
	 * @ORM\Column(type="string", name="action_value", length=100, nullable=true)
	 */
	protected string $value;

	/**
	 * @var Entities\Triggers\ITrigger
	 *
	 * @IPubDoctrine\Crud(is="required")
	 * @ORM\ManyToOne(targetEntity="FastyBird\TriggersModule\Entities\Triggers\Trigger", inversedBy="actions")
	 * @ORM\JoinColumn(name="trigger_id", referencedColumnName="trigger_id", onDelete="CASCADE")
	 */
	protected Entities\Triggers\ITrigger $trigger;

	/**
	 * @param string $value
	 * @param Entities\Triggers\ITrigger $trigger
	 * @param Uuid\UuidInterface|null $id
	 *
	 * @throws Throwable
	 */
	public function __construct(
		string $value,
		Entities\Triggers\ITrigger $trigger,
		?Uuid\UuidInterface $id = null
	) {
		$this->id = $id ?? Uuid\Uuid::uuid4();

		$this->trigger = $trigger;

		$this->value = $value;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTrigger(): Entities\Triggers\ITrigger
	{
		return $this->trigger;
	}

	/**
	 * {@inheritDoc}
	 */
	public function isEnabled(): bool
	{
		return $this->enabled;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setEnabled(bool $enabled): void
	{
		$this->enabled = $enabled;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getValue(): string
	{
		return $this->value;
	}

	/**
	 * {@inheritDoc}
	 */
	public function validate(string $value): bool
	{
		return $this->value === $value;
	}

	/**
	 * {@inheritDoc}
	 */
	public function toArray(): array
	{
		return [
			'id'      => $this->getPlainId(),
			'enabled' => $this->isEnabled(),
			'value'   => $this->getValue(),
			'trigger' => $this->getTrigger()->getPlainId(),
			'owner'   => $this->getTrigger()->getOwnerId(),
		];
	}

}
