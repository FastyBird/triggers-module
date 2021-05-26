<?php declare(strict_types = 1);

/**
 * PropertyConditionHydrator.php
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

namespace FastyBird\TriggersModule\Hydrators\Conditions;

use FastyBird\JsonApi\Exceptions as JsonApiExceptions;
use FastyBird\ModulesMetadata\Types as ModulesMetadataTypes;
use FastyBird\TriggersModule\Entities;
use Fig\Http\Message\StatusCodeInterface;
use IPub\JsonAPIDocument;

/**
 * Property condition entity hydrator
 *
 * @package         FastyBird:TriggersModule!
 * @subpackage      Hydrators
 *
 * @author          Adam Kadlec <adam.kadlec@fastybird.com>
 *
 * @phpstan-template TEntityClass of Entities\Conditions\PropertyCondition
 * @phpstan-extends  ConditionHydrator<TEntityClass>
 */
abstract class PropertyConditionHydrator extends ConditionHydrator
{

	/** @var string[] */
	protected array $attributes = [
		'device',
		'property',
		'operator',
		'operand',
		'enabled',
	];

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

	/**
	 * @param JsonAPIDocument\Objects\IStandardObject $attributes
	 *
	 * @return ModulesMetadataTypes\TriggerConditionOperatorType
	 *
	 * @throws JsonApiExceptions\IJsonApiException
	 */
	protected function hydrateOperatorAttribute(
		JsonAPIDocument\Objects\IStandardObject $attributes
	): ModulesMetadataTypes\TriggerConditionOperatorType {
		// Condition operator have to be set
		if (
			!is_scalar($attributes->get('operator'))
			|| !$attributes->has('operator')
			|| $attributes->get('operator') === ''
		) {
			throw new JsonApiExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				$this->translator->translate('//triggers-module.base.messages.missingAttribute.heading'),
				$this->translator->translate('//triggers-module.base.messages.missingAttribute.message'),
				[
					'pointer' => '/data/attributes/operator',
				]
			);

			// ...and have to be valid value
		} elseif (!ModulesMetadataTypes\TriggerConditionOperatorType::isValidValue($attributes->get('operator'))) {
			throw new JsonApiExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				$this->translator->translate('messages.invalidOperator.heading'),
				$this->translator->translate('messages.invalidOperator.message'),
				[
					'pointer' => '/data/attributes/operator',
				]
			);
		}

		return ModulesMetadataTypes\TriggerConditionOperatorType::get($attributes->get('operator'));
	}

	/**
	 * @param JsonAPIDocument\Objects\IStandardObject $attributes
	 *
	 * @return string
	 *
	 * @throws JsonApiExceptions\IJsonApiException
	 */
	protected function hydrateOperandAttribute(
		JsonAPIDocument\Objects\IStandardObject $attributes
	): string {
		if (
			!is_scalar($attributes->get('operand'))
			|| !$attributes->has('operand')
			|| $attributes->get('operand') === ''
		) {
			throw new JsonApiExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				$this->translator->translate('//triggers.api.base.messages.missingMandatory.heading'),
				$this->translator->translate('//triggers.api.base.messages.missingMandatory.message'),
				[
					'pointer' => '/data/attributes/operand',
				]
			);
		}

		return (string) $attributes->get('operand');
	}

}
