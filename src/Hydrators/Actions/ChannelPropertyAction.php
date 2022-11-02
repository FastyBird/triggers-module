<?php declare(strict_types = 1);

/**
 * ChannelPropertyAction.php
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

namespace FastyBird\Module\Triggers\Hydrators\Actions;

use FastyBird\JsonApi\Exceptions as JsonApiExceptions;
use FastyBird\Module\Triggers\Entities;
use Fig\Http\Message\StatusCodeInterface;
use IPub\JsonAPIDocument;
use Ramsey\Uuid;
use function is_scalar;

/**
 * Channel property action entity hydrator
 *
 * @extends PropertyAction<Entities\Actions\ChannelPropertyAction>
 *
 * @package         FastyBird:TriggersModule!
 * @subpackage      Hydrators
 * @author          Adam Kadlec <adam.kadlec@fastybird.com>
 */
final class ChannelPropertyAction extends PropertyAction
{

	/** @var Array<int|string, string> */
	protected array $attributes = [
		'device',
		'channel',
		'property',
		'value',
		'enabled',
	];

	public function getEntityName(): string
	{
		return Entities\Actions\ChannelPropertyAction::class;
	}

	/**
	 * @throws JsonApiExceptions\JsonApi
	 */
	protected function hydrateChannelAttribute(
		JsonAPIDocument\Objects\IStandardObject $attributes,
	): Uuid\UuidInterface
	{
		if (
			!is_scalar($attributes->get('channel'))
			|| !$attributes->has('channel')
			|| $attributes->get('channel') === ''
			|| !Uuid\Uuid::isValid((string) $attributes->get('channel'))
		) {
			throw new JsonApiExceptions\JsonApiError(
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				$this->translator->translate('//triggers-module.base.messages.missingAttribute.heading'),
				$this->translator->translate('//triggers-module.base.messages.missingAttribute.message'),
				[
					'pointer' => '/data/attributes/channel',
				],
			);
		}

		return Uuid\Uuid::fromString((string) $attributes->get('channel'));
	}

}
