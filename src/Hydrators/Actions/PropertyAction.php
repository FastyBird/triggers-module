<?php declare(strict_types = 1);

/**
 * PropertyAction.php
 *
 * @license        More in LICENSE.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     Hydrators
 * @since          0.6.0
 *
 * @date           08.01.22
 */

namespace FastyBird\Module\Triggers\Hydrators\Actions;

use FastyBird\JsonApi\Exceptions as JsonApiExceptions;
use FastyBird\Module\Triggers\Entities;
use Fig\Http\Message\StatusCodeInterface;
use IPub\JsonAPIDocument;
use Ramsey\Uuid;
use function is_bool;
use function is_scalar;
use function strtolower;

/**
 * Property action entity hydrator
 *
 * @template T of Entities\Actions\PropertyAction
 * @extends  Action<T>
 *
 * @package         FastyBird:TriggersModule!
 * @subpackage      Hydrators
 * @author          Adam Kadlec <adam.kadlec@fastybird.com>
 */
abstract class PropertyAction extends Action
{

	/** @var Array<int|string, string> */
	protected array $attributes = [
		'device',
		'property',
		'value',
		'enabled',
	];

	/**
	 * @throws JsonApiExceptions\JsonApi
	 */
	protected function hydrateDeviceAttribute(
		JsonAPIDocument\Objects\IStandardObject $attributes,
	): Uuid\UuidInterface
	{
		if (
			!is_scalar($attributes->get('device'))
			|| !$attributes->has('device')
			|| $attributes->get('device') === ''
			|| !Uuid\Uuid::isValid((string) $attributes->get('device'))
		) {
			throw new JsonApiExceptions\JsonApiError(
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				$this->translator->translate('//triggers-module.base.messages.missingAttribute.heading'),
				$this->translator->translate('//triggers-module.base.messages.missingAttribute.message'),
				[
					'pointer' => '/data/attributes/device',
				],
			);
		}

		return Uuid\Uuid::fromString((string) $attributes->get('device'));
	}

	/**
	 * @throws JsonApiExceptions\JsonApi
	 */
	protected function hydratePropertyAttribute(
		JsonAPIDocument\Objects\IStandardObject $attributes,
	): Uuid\UuidInterface
	{
		if (
			!is_scalar($attributes->get('property'))
			|| !$attributes->has('property')
			|| $attributes->get('property') === ''
			|| !Uuid\Uuid::isValid((string) $attributes->get('property'))
		) {
			throw new JsonApiExceptions\JsonApiError(
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				$this->translator->translate('//triggers-module.base.messages.missingAttribute.heading'),
				$this->translator->translate('//triggers-module.base.messages.missingAttribute.message'),
				[
					'pointer' => '/data/attributes/property',
				],
			);
		}

		return Uuid\Uuid::fromString((string) $attributes->get('property'));
	}

	/**
	 * @throws JsonApiExceptions\JsonApi
	 */
	protected function hydrateValueAttribute(
		JsonAPIDocument\Objects\IStandardObject $attributes,
	): string
	{
		if (
			!is_scalar($attributes->get('value'))
			|| !$attributes->has('value')
			|| $attributes->get('value') === ''
		) {
			throw new JsonApiExceptions\JsonApiError(
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				$this->translator->translate('//triggers-module.base.messages.missingAttribute.heading'),
				$this->translator->translate('//triggers-module.base.messages.missingAttribute.message'),
				[
					'pointer' => '/data/attributes/value',
				],
			);
		}

		$value = $attributes->get('value');

		return is_bool($value) ? ($value ? 'true' : 'false') : strtolower((string) $value);
	}

}
