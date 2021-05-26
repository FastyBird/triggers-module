<?php declare(strict_types = 1);

/**
 * ChannelPropertyActionHydrator.php
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

use FastyBird\JsonApi\Exceptions as JsonApiExceptions;
use FastyBird\TriggersModule\Entities;
use Fig\Http\Message\StatusCodeInterface;
use IPub\JsonAPIDocument;

/**
 * Channel property action entity hydrator
 *
 * @package         FastyBird:TriggersModule!
 * @subpackage      Hydrators
 *
 * @author          Adam Kadlec <adam.kadlec@fastybird.com>
 *
 * @phpstan-extends ActionHydrator<Entities\Actions\IChannelPropertyAction>
 */
final class ChannelPropertyActionHydrator extends ActionHydrator
{

	/** @var string[] */
	protected array $attributes = [
		'device',
		'channel',
		'property',
		'value',
		'enabled',
	];

	/**
	 * {@inheritDoc}
	 */
	protected function getEntityName(): string
	{
		return Entities\Actions\ChannelPropertyAction::class;
	}

	/**
	 * @param JsonAPIDocument\Objects\IStandardObject $attributes
	 *
	 * @return string
	 *
	 * @throws JsonApiExceptions\IJsonApiException
	 */
	protected function hydrateDeviceAttribute(
		JsonAPIDocument\Objects\IStandardObject $attributes
	): string {
		if (
			!is_scalar($attributes->get('device'))
			|| !$attributes->has('device')
			|| $attributes->get('device') === ''
		) {
			throw new JsonApiExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				$this->translator->translate('//triggers-module.base.messages.missingAttribute.heading'),
				$this->translator->translate('//triggers-module.base.messages.missingAttribute.message'),
				[
					'pointer' => '/data/attributes/device',
				]
			);
		}

		return (string) $attributes->get('device');
	}

	/**
	 * @param JsonAPIDocument\Objects\IStandardObject $attributes
	 *
	 * @return string
	 *
	 * @throws JsonApiExceptions\IJsonApiException
	 */
	protected function hydrateChannelAttribute(
		JsonAPIDocument\Objects\IStandardObject $attributes
	): string {
		if (
			!is_scalar($attributes->get('channel'))
			|| !$attributes->has('channel')
			|| $attributes->get('channel') === ''
		) {
			throw new JsonApiExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				$this->translator->translate('//triggers-module.base.messages.missingAttribute.heading'),
				$this->translator->translate('//triggers-module.base.messages.missingAttribute.message'),
				[
					'pointer' => '/data/attributes/channel',
				]
			);
		}

		return (string) $attributes->get('channel');
	}

	/**
	 * @param JsonAPIDocument\Objects\IStandardObject $attributes
	 *
	 * @return string
	 *
	 * @throws JsonApiExceptions\IJsonApiException
	 */
	protected function hydratePropertyAttribute(
		JsonAPIDocument\Objects\IStandardObject $attributes
	): string {
		if (
			!is_scalar($attributes->get('property'))
			|| !$attributes->has('property')
			|| $attributes->get('property') === ''
		) {
			throw new JsonApiExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				$this->translator->translate('//triggers-module.base.messages.missingAttribute.heading'),
				$this->translator->translate('//triggers-module.base.messages.missingAttribute.message'),
				[
					'pointer' => '/data/attributes/property',
				]
			);
		}

		return (string) $attributes->get('property');
	}

}
