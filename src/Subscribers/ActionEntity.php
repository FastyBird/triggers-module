<?php declare(strict_types = 1);

/**
 * ActionEntity.php
 *
 * @license        More in LICENSE.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     Events
 * @since          0.1.0
 *
 * @date           05.04.20
 */

namespace FastyBird\Module\Triggers\Subscribers;

use Doctrine\Common;
use Doctrine\ORM;
use FastyBird\Module\Triggers\Entities;
use FastyBird\Module\Triggers\Exceptions;
use Nette;
use function array_merge;

/**
 * Trigger action entity listener
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Events
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
final class ActionEntity implements Common\EventSubscriber
{

	use Nette\SmartObject;

	/**
	 * Register events
	 *
	 * @return Array<string>
	 */
	public function getSubscribedEvents(): array
	{
		return [
			ORM\Events::onFlush,
		];
	}

	/**
	 * @throws Exceptions\UniqueActionConstraint
	 */
	public function onFlush(ORM\Event\OnFlushEventArgs $eventArgs): void
	{
		$manager = $eventArgs->getObjectManager();
		$uow = $manager->getUnitOfWork();

		// Check all scheduled updates
		foreach (array_merge($uow->getScheduledEntityInsertions(), $uow->getScheduledEntityUpdates()) as $object) {
			if ($object instanceof Entities\Actions\ChannelPropertyAction) {
				$trigger = $object->getTrigger();

				foreach ($trigger->getActions() as $action) {
					if (!$action->getId()->equals($object->getId())) {
						if (
							$action instanceof Entities\Actions\ChannelPropertyAction
							&& $action->getDevice()->equals($object->getDevice())
							&& $action->getChannel()->equals($object->getChannel())
							&& $action->getProperty()->equals($object->getProperty())
						) {
							throw new Exceptions\UniqueActionConstraint('Not same property in trigger actions');
						}
					}
				}
			}
		}
	}

}
