<?php declare(strict_types = 1);

/**
 * NotificationHydrator.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     Hydrators
 * @since          0.1.0
 *
 * @date           04.04.20
 */

namespace FastyBird\TriggersModule\Hydrators\Notifications;

use FastyBird\JsonApi\Hydrators as JsonApiHydrators;
use FastyBird\TriggersModule\Schemas;
use IPub\JsonAPIDocument;

/**
 * Notification entity hydrator
 *
 * @package         FastyBird:TriggersModule!
 * @subpackage      Hydrators
 *
 * @author          Adam Kadlec <adam.kadlec@fastybird.com>
 */
abstract class NotificationHydrator extends JsonApiHydrators\Hydrator
{

	/** @var string */
	protected $entityIdentifier = self::IDENTIFIER_KEY;

	/** @var string[] */
	protected $relationships = [
		Schemas\Notifications\NotificationSchema::RELATIONSHIPS_TRIGGER,
	];

	/** @var string */
	protected $translationDomain = 'module.notifications';

	/**
	 * @param JsonAPIDocument\Objects\IStandardObject<mixed> $attributes
	 *
	 * @return bool
	 */
	protected function hydrateEnabledAttribute(JsonAPIDocument\Objects\IStandardObject $attributes): bool
	{
		return (bool) $attributes->get('enabled');
	}

}
