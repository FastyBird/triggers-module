<?php declare(strict_types = 1);

/**
 * ActionSchema.php
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

namespace FastyBird\TriggersModule\Schemas\Actions;

use FastyBird\JsonApi\Schemas as JsonApiSchemas;
use FastyBird\TriggersModule\Entities;
use FastyBird\TriggersModule\Router;
use FastyBird\TriggersModule\Schemas;
use IPub\SlimRouter\Routing;
use Neomerx\JsonApi;

/**
 * Action entity schema
 *
 * @package          FastyBird:TriggersModule!
 * @subpackage       Schemas
 *
 * @author           Adam Kadlec <adam.kadlec@fastybird.com>
 *
 * @phpstan-template T of Entities\Actions\IAction
 * @phpstan-extends  JsonApiSchemas\JsonApiSchema<T>
 */
abstract class ActionSchema extends JsonApiSchemas\JsonApiSchema
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
	 * @param Entities\Actions\IAction $action
	 * @param JsonApi\Contracts\Schema\ContextInterface $context
	 *
	 * @return iterable<string, bool>
	 *
	 * @phpstan-param T $action
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function getAttributes($action, JsonApi\Contracts\Schema\ContextInterface $context): iterable
	{
		return [
			'enabled' => $action->isEnabled(),
		];
	}

	/**
	 * @param Entities\Actions\IAction $action
	 *
	 * @return JsonApi\Contracts\Schema\LinkInterface
	 *
	 * @phpstan-param T $action
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function getSelfLink($action): JsonApi\Contracts\Schema\LinkInterface
	{
		return new JsonApi\Schema\Link(
			false,
			$this->router->urlFor(
				'trigger.action',
				[
					Router\Router::URL_TRIGGER_ID => $action->getTrigger()->getPlainId(),
					Router\Router::URL_ITEM_ID    => $action->getPlainId(),
				]
			),
			false
		);
	}

	/**
	 * @param Entities\Actions\IAction $action
	 * @param JsonApi\Contracts\Schema\ContextInterface $context
	 *
	 * @return iterable<string, mixed>
	 *
	 * @phpstan-param T $action
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function getRelationships($action, JsonApi\Contracts\Schema\ContextInterface $context): iterable
	{
		return [
			self::RELATIONSHIPS_TRIGGER => [
				self::RELATIONSHIP_DATA          => $action->getTrigger(),
				self::RELATIONSHIP_LINKS_SELF    => true,
				self::RELATIONSHIP_LINKS_RELATED => true,
			],
		];
	}

	/**
	 * @param Entities\Actions\IAction $action
	 * @param string $name
	 *
	 * @return JsonApi\Contracts\Schema\LinkInterface
	 *
	 * @phpstan-param T $action
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function getRelationshipRelatedLink($action, string $name): JsonApi\Contracts\Schema\LinkInterface
	{
		if ($name === self::RELATIONSHIPS_TRIGGER) {
			return new JsonApi\Schema\Link(
				false,
				$this->router->urlFor(
					'trigger',
					[
						Router\Router::URL_ITEM_ID => $action->getTrigger()->getPlainId(),
					]
				),
				false
			);
		}

		return parent::getRelationshipRelatedLink($action, $name);
	}

	/**
	 * @param Entities\Actions\IAction $action
	 * @param string $name
	 *
	 * @return JsonApi\Contracts\Schema\LinkInterface
	 *
	 * @phpstan-param T $action
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function getRelationshipSelfLink($action, string $name): JsonApi\Contracts\Schema\LinkInterface
	{
		if ($name === self::RELATIONSHIPS_TRIGGER) {
			return new JsonApi\Schema\Link(
				false,
				$this->router->urlFor(
					'trigger.action.relationship',
					[
						Router\Router::URL_TRIGGER_ID  => $action->getTrigger()->getPlainId(),
						Router\Router::URL_ITEM_ID     => $action->getPlainId(),
						Router\Router::RELATION_ENTITY => $name,
					]
				),
				false
			);
		}

		return parent::getRelationshipSelfLink($action, $name);
	}

}
