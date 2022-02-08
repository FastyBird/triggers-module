<?php declare(strict_types = 1);

/**
 * DevicePropertyActionSchema.php
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

namespace FastyBird\TriggersModule\Schemas\Actions;

use FastyBird\Metadata\Types\ModuleSourceType;
use FastyBird\TriggersModule\Entities;
use Neomerx\JsonApi;

/**
 * Trigger device state action entity schema
 *
 * @package         FastyBird:TriggersModule!
 * @subpackage      Schemas
 *
 * @author          Adam Kadlec <adam.kadlec@fastybird.com>
 *
 * @phpstan-extends ActionSchema<Entities\Actions\IDevicePropertyAction>
 */
final class DevicePropertyActionSchema extends ActionSchema
{

	/**
	 * Define entity schema type string
	 */
	public const SCHEMA_TYPE = ModuleSourceType::SOURCE_MODULE_TRIGGERS . '/action/device-property';

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
		return Entities\Actions\DevicePropertyAction::class;
	}

	/**
	 * @param Entities\Actions\IDevicePropertyAction $action
	 * @param JsonApi\Contracts\Schema\ContextInterface $context
	 *
	 * @return iterable<string, string|bool>
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function getAttributes($action, JsonApi\Contracts\Schema\ContextInterface $context): iterable
	{
		return array_merge((array) parent::getAttributes($action, $context), [
			'device'   => $action->getDevice()->toString(),
			'property' => $action->getProperty()->toString(),
			'value'    => (string) $action->getValue(),
		]);
	}

}
