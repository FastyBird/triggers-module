<?php declare(strict_types = 1);

/**
 * ChannelPropertyTriggerSchema.php
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

namespace FastyBird\TriggersModule\Schemas\Triggers;

use FastyBird\TriggersModule\Entities;
use Neomerx\JsonApi;

/**
 * Channel property trigger entity schema
 *
 * @package         FastyBird:TriggersModule!
 * @subpackage      Schemas
 *
 * @author          Adam Kadlec <adam.kadlec@fastybird.com>
 *
 * @phpstan-extends TriggerSchema<Entities\Triggers\IChannelPropertyTrigger>
 */
final class ChannelPropertyTriggerSchema extends TriggerSchema
{

	/**
	 * Define entity schema type string
	 */
	public const SCHEMA_TYPE = 'triggers-module/trigger-channel-property';

	/**
	 * @return string
	 */
	public function getType(): string
	{
		return self::SCHEMA_TYPE;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getEntityClass(): string
	{
		return Entities\Triggers\ChannelPropertyTrigger::class;
	}

	/**
	 * @param Entities\Triggers\IChannelPropertyTrigger $trigger
	 * @param JsonApi\Contracts\Schema\ContextInterface $context
	 *
	 * @return iterable<string, string|bool|null>
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function getAttributes($trigger, JsonApi\Contracts\Schema\ContextInterface $context): iterable
	{
		return array_merge((array) parent::getAttributes($trigger, $context), [
			'device'   => $trigger->getDevice(),
			'channel'  => $trigger->getChannel(),
			'property' => $trigger->getProperty(),
			'operator' => $trigger->getOperator()->getValue(),
			'operand'  => $trigger->getOperand(),
		]);
	}

	/**
	 * @param Entities\Triggers\IChannelPropertyTrigger $trigger
	 * @param JsonApi\Contracts\Schema\ContextInterface $context
	 *
	 * @return iterable<string, mixed>
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function getRelationships($trigger, JsonApi\Contracts\Schema\ContextInterface $context): iterable
	{
		return [
			self::RELATIONSHIPS_ACTIONS => [
				self::RELATIONSHIP_DATA          => $trigger->getActions(),
				self::RELATIONSHIP_LINKS_SELF    => true,
				self::RELATIONSHIP_LINKS_RELATED => true,
			],
		];
	}

}
