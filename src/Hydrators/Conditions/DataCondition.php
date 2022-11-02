<?php declare(strict_types = 1);

/**
 * DataCondition.php
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

namespace FastyBird\Module\Triggers\Hydrators\Conditions;

use DateTimeInterface;
use FastyBird\JsonApi\Exceptions as JsonApiExceptions;
use FastyBird\Module\Triggers\Entities;
use Fig\Http\Message\StatusCodeInterface;
use IPub\JsonAPIDocument;
use Nette\Utils;
use function is_scalar;

/**
 * Time condition entity hydrator
 *
 * @extends Condition<Entities\Conditions\DateCondition>
 *
 * @package         FastyBird:TriggersModule!
 * @subpackage      Hydrators
 * @author          Adam Kadlec <adam.kadlec@fastybird.com>
 */
final class DataCondition extends Condition
{

	/** @var Array<int|string, string> */
	protected array $attributes = [
		'date',
		'enabled',
	];

	public function getEntityName(): string
	{
		return Entities\Conditions\DateCondition::class;
	}

	/**
	 * @throws JsonApiExceptions\JsonApi
	 */
	protected function hydrateDateAttribute(
		JsonAPIDocument\Objects\IStandardObject $attributes,
	): DateTimeInterface
	{
		// Condition date have to be set
		if (!is_scalar($attributes->get('date')) || !$attributes->has('date')) {
			throw new JsonApiExceptions\JsonApiError(
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				$this->translator->translate('//triggers-module.base.messages.missingAttribute.heading'),
				$this->translator->translate('//triggers-module.base.messages.missingAttribute.message'),
				[
					'pointer' => '/data/attributes/date',
				],
			);
		}

		$date = Utils\DateTime::createFromFormat(DateTimeInterface::ATOM, (string) $attributes->get('date'));

		if (
			!$date instanceof DateTimeInterface
			|| $date->format(DateTimeInterface::ATOM) !== $attributes->get('date')
		) {
			throw new JsonApiExceptions\JsonApiError(
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				$this->translator->translate('//triggers-module.conditions.messages.invalidTime.heading'),
				$this->translator->translate('//triggers-module.conditions.messages.invalidTime.message'),
				[
					'pointer' => '/data/attributes/date',
				],
			);
		}

		return $date;
	}

}
