<?php declare(strict_types = 1);

/**
 * TriggerHydrator.php
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

namespace FastyBird\TriggersModule\Hydrators\Triggers;

use FastyBird\JsonApi\Hydrators as JsonApiHydrators;
use FastyBird\TriggersModule\Schemas;
use IPub\JsonAPIDocument;

/**
 * Trigger entity hydrator
 *
 * @package         FastyBird:TriggersModule!
 * @subpackage      Hydrators
 *
 * @author          Adam Kadlec <adam.kadlec@fastybird.com>
 */
abstract class TriggerHydrator extends JsonApiHydrators\Hydrator
{

	/** @var string[] */
	protected array $attributes = [
		'name',
		'comment',
		'enabled',
	];

	/** @var string[] */
	protected array $relationships = [
		Schemas\Triggers\TriggerSchema::RELATIONSHIPS_ACTIONS,
		Schemas\Triggers\TriggerSchema::RELATIONSHIPS_NOTIFICATIONS,
	];

	/** @var string */
	protected string $translationDomain = 'triggers-module.triggers';

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
