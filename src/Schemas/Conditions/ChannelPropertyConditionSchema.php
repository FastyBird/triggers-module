<?php declare(strict_types = 1);

/**
 * ChannelPropertyConditionSchema.php
 *
 * @license        More in LICENSE.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     Schemas
 * @since          0.1.0
 *
 * @date           04.04.20
 */

namespace FastyBird\TriggersModule\Schemas\Conditions;

use FastyBird\TriggersModule\Entities;
use Neomerx\JsonApi;

/**
 * Channel property condition entity schema
 *
 * @package         FastyBird:TriggersModule!
 * @subpackage      Schemas
 *
 * @author          Adam Kadlec <adam.kadlec@fastybird.com>
 *
 * @phpstan-extends ConditionSchema<Entities\Conditions\IChannelPropertyCondition>
 */
final class ChannelPropertyConditionSchema extends ConditionSchema
{

	/**
	 * Define entity schema type string
	 */
	public const SCHEMA_TYPE = ModuleSourceType::SOURCE_MODULE_TRIGGERS . '/condition/channel-property';

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
		return Entities\Conditions\ChannelPropertyCondition::class;
	}

	/**
	 * @param Entities\Conditions\IChannelPropertyCondition $condition
	 * @param JsonApi\Contracts\Schema\ContextInterface $context
	 *
	 * @return iterable<string, string|bool>
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function getAttributes($condition, JsonApi\Contracts\Schema\ContextInterface $context): iterable
	{
		return array_merge((array) parent::getAttributes($condition, $context), [
			'device'   => $condition->getDevice()->toString(),
			'channel'  => $condition->getChannel()->toString(),
			'property' => $condition->getProperty()->toString(),
			'operator' => $condition->getOperator()->getValue(),
			'operand'  => (string) $condition->getOperand(),
		]);
	}

}
