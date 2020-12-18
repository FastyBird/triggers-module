<?php declare(strict_types = 1);

/**
 * Trigger.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     Entities
 * @since          0.1.0
 *
 * @date           04.04.20
 */

namespace FastyBird\TriggersModule\Entities\Triggers;

use Doctrine\Common;
use Doctrine\ORM\Mapping as ORM;
use FastyBird\Database\Entities as DatabaseEntities;
use FastyBird\SimpleAuth\Entities as SimpleAuthEntities;
use FastyBird\TriggersModule\Entities;
use IPub\DoctrineCrud\Mapping\Annotation as IPubDoctrine;
use IPub\DoctrineTimestampable;
use Ramsey\Uuid;
use Throwable;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="fb_triggers",
 *     options={
 *       "collate"="utf8mb4_general_ci",
 *       "charset"="utf8mb4",
 *       "comment"="Actions triggers"
 *     }
 * )
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="trigger_type", type="string", length=20)
 * @ORM\DiscriminatorMap({
 *    "automatic"        = "FastyBird\TriggersModule\Entities\Triggers\AutomaticTrigger",
 *    "manual"           = "FastyBird\TriggersModule\Entities\Triggers\ManualTrigger",
 *    "channel_property" = "FastyBird\TriggersModule\Entities\Triggers\ChannelPropertyTrigger"
 * })
 * @ORM\MappedSuperclass
 */
abstract class Trigger implements ITrigger
{

	use DatabaseEntities\TEntity;
	use DatabaseEntities\TEntityParams;
	use SimpleAuthEntities\TEntityOwner;
	use DoctrineTimestampable\Entities\TEntityCreated;
	use DoctrineTimestampable\Entities\TEntityUpdated;

	/**
	 * @var Uuid\UuidInterface
	 *
	 * @ORM\Id
	 * @ORM\Column(type="uuid_binary", name="trigger_id")
	 * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
	 */
	protected Uuid\UuidInterface $id;

	/**
	 * @var string
	 *
	 * @IPubDoctrine\Crud(is={"required", "writable"})
	 * @ORM\Column(type="string", name="trigger_name", length=100, nullable=false)
	 */
	protected string $name;

	/**
	 * @var string|null
	 *
	 * @IPubDoctrine\Crud(is="writable")
	 * @ORM\Column(type="text", name="trigger_comment", nullable=true, options={"default": null})
	 */
	protected ?string $comment = null;

	/**
	 * @var bool
	 *
	 * @IPubDoctrine\Crud(is="writable")
	 * @ORM\Column(type="boolean", name="trigger_enabled", length=1, nullable=false, options={"default": true})
	 */
	protected bool $enabled = true;

	/**
	 * @var Common\Collections\Collection<int, Entities\Actions\IAction>
	 *
	 * @IPubDoctrine\Crud(is="writable")
	 * @ORM\OneToMany(targetEntity="FastyBird\TriggersModule\Entities\Actions\Action", mappedBy="trigger", cascade={"persist", "remove"}, orphanRemoval=true)
	 */
	protected Common\Collections\Collection $actions;

	/**
	 * @var Common\Collections\Collection<int, Entities\Notifications\INotification>
	 *
	 * @IPubDoctrine\Crud(is="writable")
	 * @ORM\OneToMany(targetEntity="FastyBird\TriggersModule\Entities\Notifications\Notification", mappedBy="trigger", cascade={"persist", "remove"}, orphanRemoval=true)
	 */
	protected Common\Collections\Collection $notifications;

	/**
	 * @param string $name
	 * @param Uuid\UuidInterface|null $id
	 *
	 * @throws Throwable
	 */
	public function __construct(
		string $name,
		?Uuid\UuidInterface $id = null
	) {
		$this->id = $id ?? Uuid\Uuid::uuid4();

		$this->setName($name);

		$this->actions = new Common\Collections\ArrayCollection();
		$this->notifications = new Common\Collections\ArrayCollection();
	}

	/**
	 * {@inheritDoc}
	 */
	public function addAction(Entities\Actions\IAction $action): void
	{
		// Check if collection does not contain inserting entity
		if (!$this->actions->contains($action)) {
			// ...and assign it to collection
			$this->actions->add($action);
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function getActions(): array
	{
		return $this->actions->toArray();
	}

	/**
	 * {@inheritDoc}
	 */
	public function setActions(array $actions = []): void
	{
		$this->actions = new Common\Collections\ArrayCollection();

		// Process all passed entities...
		/** @var Entities\Actions\IAction $entity */
		foreach ($actions as $entity) {
			if (!$this->actions->contains($entity)) {
				// ...and assign them to collection
				$this->actions->add($entity);
			}
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function getAction(string $id): ?Entities\Actions\IAction
	{
		$found = $this->actions
			->filter(function (Entities\Actions\IAction $row) use ($id): bool {
				return $id === $row->getPlainId();
			});

		return $found->isEmpty() || $found->first() === false ? null : $found->first();
	}

	/**
	 * {@inheritDoc}
	 */
	public function removeAction(Entities\Actions\IAction $action): void
	{
		// Check if collection contain removing entity...
		if ($this->actions->contains($action)) {
			// ...and remove it from collection
			$this->actions->removeElement($action);
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function addNotification(Entities\Notifications\INotification $notification): void
	{
		// Check if collection does not contain inserting entity
		if (!$this->notifications->contains($notification)) {
			// ...and assign it to collection
			$this->notifications->add($notification);
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function getNotifications(): array
	{
		return $this->notifications->toArray();
	}

	/**
	 * {@inheritDoc}
	 */
	public function setNotifications(array $notifications = []): void
	{
		$this->notifications = new Common\Collections\ArrayCollection();

		// Process all passed entities...
		/** @var Entities\Notifications\INotification $entity */
		foreach ($notifications as $entity) {
			if (!$this->notifications->contains($entity)) {
				// ...and assign them to collection
				$this->notifications->add($entity);
			}
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function getNotification(string $id): ?Entities\Notifications\INotification
	{
		$found = $this->notifications
			->filter(function (Entities\Notifications\INotification $row) use ($id): bool {
				return $id === $row->getPlainId();
			});

		return $found->isEmpty() || $found->first() === false ? null : $found->first();
	}

	/**
	 * {@inheritDoc}
	 */
	public function removeNotification(Entities\Notifications\INotification $notification): void
	{
		// Check if collection contain removing entity...
		if ($this->notifications->contains($notification)) {
			// ...and remove it from collection
			$this->notifications->removeElement($notification);
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function toArray(): array
	{
		return [
			'id'      => $this->getPlainId(),
			'name'    => $this->getName(),
			'comment' => $this->getComment(),
			'enabled' => $this->isEnabled(),
			'owner'   => $this->getOwnerId(),
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setName(string $name): void
	{
		$this->name = $name;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getComment(): ?string
	{
		return $this->comment;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setComment(?string $comment = null): void
	{
		$this->comment = $comment;
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

}
