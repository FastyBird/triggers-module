<?php declare(strict_types = 1);

/**
 * INotification.php
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

namespace FastyBird\TriggersModule\Entities\Notifications;

use FastyBird\TriggersModule\Entities;
use IPub\DoctrineTimestampable;

/**
 * Base notification entity interface
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Entities
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface INotification extends Entities\IEntity,
	DoctrineTimestampable\Entities\IEntityCreated, DoctrineTimestampable\Entities\IEntityUpdated
{

	/**
	 * @return Entities\Triggers\ITrigger
	 */
	public function getTrigger(): Entities\Triggers\ITrigger;

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
