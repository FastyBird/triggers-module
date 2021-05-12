<?php declare(strict_types = 1);

/**
 * INotificationRepository.php
 *
 * @license        More in LICENSE.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     Models
 * @since          0.1.0
 *
 * @date           04.04.20
 */

namespace FastyBird\TriggersModule\Models\Notifications;

use FastyBird\TriggersModule\Entities;
use FastyBird\TriggersModule\Queries;
use IPub\DoctrineOrmQuery;

/**
 * Notification repository interface
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Models
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface INotificationRepository
{

	/**
	 * @param Queries\FindNotificationsQuery $queryObject
	 * @param string $type
	 *
	 * @return Entities\Notifications\INotification|null
	 *
	 * @phpstan-template T of Entities\Notifications\Notification
	 * @phpstan-param    Queries\FindNotificationsQuery<T> $queryObject
	 * @phpstan-param    class-string<T> $type
	 */
	public function findOneBy(
		Queries\FindNotificationsQuery $queryObject,
		string $type = Entities\Notifications\Notification::class
	): ?Entities\Notifications\INotification;

	/**
	 * @param Queries\FindNotificationsQuery $queryObject
	 * @param string $type
	 *
	 * @return Entities\Notifications\INotification[]
	 *
	 * @phpstan-template T of Entities\Notifications\Notification
	 * @phpstan-param    Queries\FindNotificationsQuery<T> $queryObject
	 * @phpstan-param    class-string<T> $type
	 */
	public function findAllBy(
		Queries\FindNotificationsQuery $queryObject,
		string $type = Entities\Notifications\Notification::class
	): array;

	/**
	 * @param Queries\FindNotificationsQuery $queryObject
	 * @param string $type
	 *
	 * @return DoctrineOrmQuery\ResultSet
	 *
	 * @phpstan-template T of Entities\Notifications\Notification
	 * @phpstan-param    Queries\FindNotificationsQuery<T> $queryObject
	 * @phpstan-param    class-string<T> $type
	 * @phpstan-return   DoctrineOrmQuery\ResultSet<T>
	 */
	public function getResultSet(
		Queries\FindNotificationsQuery $queryObject,
		string $type = Entities\Notifications\Notification::class
	): DoctrineOrmQuery\ResultSet;

}
