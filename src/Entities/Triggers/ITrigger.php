<?php declare(strict_types = 1);

/**
 * ITrigger.php
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

namespace FastyBird\TriggersModule\Entities\Triggers;

use FastyBird\SimpleAuth\Entities as SimpleAuthEntities;
use FastyBird\TriggersModule\Entities;
use IPub\DoctrineTimestampable;

/**
 * Base trigger entity interface
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Entities
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface ITrigger extends Entities\IEntity,
	Entities\IEntityParams,
	SimpleAuthEntities\IEntityOwner,
	DoctrineTimestampable\Entities\IEntityCreated, DoctrineTimestampable\Entities\IEntityUpdated
{

	/**
	 * @param string $name
	 *
	 * @return void
	 */
	public function setName(string $name): void;

	/**
	 * @return string
	 */
	public function getName(): string;

	/**
	 * @param string|null $comment
	 *
	 * @return void
	 */
	public function setComment(?string $comment = null): void;

	/**
	 * @return string|null
	 */
	public function getComment(): ?string;

	/**
	 * @param bool $enabled
	 */
	public function setEnabled(bool $enabled): void;

	/**
	 * @return bool
	 */
	public function isEnabled(): bool;

	/**
	 * @param Entities\Actions\IAction[] $actions
	 *
	 * @return void
	 */
	public function setActions(array $actions = []): void;

	/**
	 * @param Entities\Actions\IAction $action
	 *
	 * @return void
	 */
	public function addAction(Entities\Actions\IAction $action): void;

	/**
	 * @return Entities\Actions\IAction[]
	 */
	public function getActions(): array;

	/**
	 * @param string $id
	 *
	 * @return Entities\Actions\IAction|null
	 */
	public function getAction(string $id): ?Entities\Actions\IAction;

	/**
	 * @param Entities\Actions\IAction $action
	 *
	 * @return void
	 */
	public function removeAction(Entities\Actions\IAction $action): void;

	/**
	 * @param Entities\Notifications\INotification[] $notifications
	 *
	 * @return void
	 */
	public function setNotifications(array $notifications = []): void;

	/**
	 * @param Entities\Notifications\INotification $notification
	 *
	 * @return void
	 */
	public function addNotification(Entities\Notifications\INotification $notification): void;

	/**
	 * @return Entities\Notifications\INotification[]
	 */
	public function getNotifications(): array;

	/**
	 * @param string $id
	 *
	 * @return Entities\Notifications\INotification|null
	 */
	public function getNotification(string $id): ?Entities\Notifications\INotification;

	/**
	 * @param Entities\Notifications\INotification $notification
	 *
	 * @return void
	 */
	public function removeNotification(Entities\Notifications\INotification $notification): void;

	/**
	 * @return Entities\Triggers\Controls\IControl[]
	 */
	public function getControls(): array;

	/**
	 * @param string $name
	 *
	 * @return Entities\Triggers\Controls\IControl|null
	 */
	public function getControl(string $name): ?Entities\Triggers\Controls\IControl;

	/**
	 * @param string $name
	 *
	 * @return Entities\Triggers\Controls\IControl|null
	 */
	public function findControl(string $name): ?Entities\Triggers\Controls\IControl;

	/**
	 * @param string $name
	 *
	 * @return bool
	 */
	public function hasControl(string $name): bool;

	/**
	 * @param Entities\Triggers\Controls\IControl[] $control
	 *
	 * @return void
	 */
	public function setControls(array $control): void;

	/**
	 * @param Entities\Triggers\Controls\IControl $control
	 *
	 * @return void
	 */
	public function addControl(Entities\Triggers\Controls\IControl $control): void;

	/**
	 * @param Entities\Triggers\Controls\IControl $control
	 *
	 * @return void
	 */
	public function removeControl(Entities\Triggers\Controls\IControl $control): void;

	/**
	 * @return mixed[]
	 */
	public function toArray(): array;

}
