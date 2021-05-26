<?php declare(strict_types = 1);

/**
 * ActionHydrator.php
 *
 * @license        More in LICENSE.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     Hydrators
 * @since          0.1.0
 *
 * @date           04.04.20
 */

namespace FastyBird\TriggersModule\Hydrators\Actions;

use FastyBird\JsonApi\Hydrators as JsonApiHydrators;
use FastyBird\TriggersModule\Entities;
use FastyBird\TriggersModule\Hydrators;
use FastyBird\TriggersModule\Schemas;
use IPub\JsonAPIDocument;

/**
 * Action entity hydrator
 *
 * @package         FastyBird:TriggersModule!
 * @subpackage      Hydrators
 *
 * @author          Adam Kadlec <adam.kadlec@fastybird.com>
 *
 * @phpstan-template TEntityClass of Entities\Actions\IAction
 * @phpstan-extends  JsonApiHydrators\Hydrator<TEntityClass>
 */
abstract class ActionHydrator extends JsonApiHydrators\Hydrator
{

	/** @var string[] */
	protected array $relationships = [
		Schemas\Actions\ActionSchema::RELATIONSHIPS_TRIGGER,
	];

	/** @var string */
	protected string $translationDomain = 'triggers-module.actions';

	/**
	 * @param JsonAPIDocument\Objects\IStandardObject $attributes
	 *
	 * @return bool
	 */
	protected function hydrateEnabledAttribute(JsonAPIDocument\Objects\IStandardObject $attributes): bool
	{
		return is_scalar($attributes->get('enabled')) && (bool) $attributes->get('enabled');
	}

}
