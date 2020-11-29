<?php declare(strict_types = 1);

/**
 * ConditionSchema.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     Schemas
 * @since          0.1.0
 *
 * @date           04.04.20
 */

namespace FastyBird\TriggersModule\Schemas\Conditions;

use FastyBird\JsonApi\Schemas as JsonApiSchemas;
use FastyBird\TriggersModule\Entities;
use FastyBird\TriggersModule\Router;
use FastyBird\TriggersModule\Schemas;
use IPub\SlimRouter\Routing;
use Neomerx\JsonApi;

/**
 * Condition entity schema
 *
 * @package          FastyBird:TriggersModule!
 * @subpackage       Schemas
 *
 * @author           Adam Kadlec <adam.kadlec@fastybird.com>
 *
 * @phpstan-template T of Entities\Conditions\ICondition
 * @phpstan-extends  JsonApiSchemas\JsonApiSchema<T>
 */
abstract class ConditionSchema extends JsonApiSchemas\JsonApiSchema
{

	/**
	 * Define relationships names
	 */
	public const RELATIONSHIPS_TRIGGER = 'trigger';

	/** @var Routing\IRouter */
	protected $router;

	public function __construct(
		Routing\IRouter $router
	) {
		$this->router = $router;
	}

	/**
	 * @param Entities\Conditions\ICondition $condition
	 * @param JsonApi\Contracts\Schema\ContextInterface $context
	 *
	 * @return iterable<string, bool>
	 *
	 * @phpstan-param T $condition
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function getAttributes($condition, JsonApi\Contracts\Schema\ContextInterface $context): iterable
	{
		return [
			'enabled' => $condition->isEnabled(),
		];
	}


	/**
	 * @param Entities\Conditions\ICondition $condition
	 *
	 * @return JsonApi\Contracts\Schema\LinkInterface
	 *
	 * @phpstan-param T $condition
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function getSelfLink($condition): JsonApi\Contracts\Schema\LinkInterface
	{
		return new JsonApi\Schema\Link(
			false,
			$this->router->urlFor(
				'trigger.condition',
				[
					Router\Router::URL_TRIGGER_ID => $condition->getTrigger()->getPlainId(),
					Router\Router::URL_ITEM_ID    => $condition->getPlainId(),
				]
			),
			false
		);
	}

	/**
	 * @param Entities\Conditions\ICondition $condition
	 * @param JsonApi\Contracts\Schema\ContextInterface $context
	 *
	 * @return iterable<string, mixed>
	 *
	 * @phpstan-param T $condition
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function getRelationships($condition, JsonApi\Contracts\Schema\ContextInterface $context): iterable
	{
		return [
			self::RELATIONSHIPS_TRIGGER => [
				self::RELATIONSHIP_DATA          => $condition->getTrigger(),
				self::RELATIONSHIP_LINKS_SELF    => true,
				self::RELATIONSHIP_LINKS_RELATED => true,
			],
		];
	}

	/**
	 * @param Entities\Conditions\ICondition $condition
	 * @param string $name
	 *
	 * @return JsonApi\Contracts\Schema\LinkInterface
	 *
	 * @phpstan-param T $condition
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function getRelationshipRelatedLink($condition, string $name): JsonApi\Contracts\Schema\LinkInterface
	{
		if ($name === self::RELATIONSHIPS_TRIGGER) {
			return new JsonApi\Schema\Link(
				false,
				$this->router->urlFor(
					'trigger',
					[
						Router\Router::URL_ITEM_ID => $condition->getTrigger()->getPlainId(),
					]
				),
				false
			);
		}

		return parent::getRelationshipRelatedLink($condition, $name);
	}

	/**
	 * @param Entities\Conditions\ICondition $condition
	 * @param string $name
	 *
	 * @return JsonApi\Contracts\Schema\LinkInterface
	 *
	 * @phpstan-param T $condition
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function getRelationshipSelfLink($condition, string $name): JsonApi\Contracts\Schema\LinkInterface
	{
		if ($name === self::RELATIONSHIPS_TRIGGER) {
			return new JsonApi\Schema\Link(
				false,
				$this->router->urlFor(
					'trigger.condition.relationship',
					[
						Router\Router::URL_TRIGGER_ID  => $condition->getTrigger()->getPlainId(),
						Router\Router::URL_ITEM_ID     => $condition->getPlainId(),
						Router\Router::RELATION_ENTITY => $name,
					]
				),
				false
			);
		}

		return parent::getRelationshipSelfLink($condition, $name);
	}

}
