<?php declare(strict_types = 1);

/**
 * SmsNotificationHydrator.php
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

namespace FastyBird\TriggersModule\Hydrators\Notifications;

use Contributte\Translation;
use Doctrine\Common;
use FastyBird\JsonApi\Exceptions as JsonApiExceptions;
use FastyBird\TriggersModule\Entities;
use Fig\Http\Message\StatusCodeInterface;
use IPub\JsonAPIDocument;
use IPub\Phone;

/**
 * SMS notification entity hydrator
 *
 * @package         FastyBird:TriggersModule!
 * @subpackage      Hydrators
 *
 * @author          Adam Kadlec <adam.kadlec@fastybird.com>
 */
final class SmsNotificationHydrator extends NotificationHydrator
{

	/** @var string[] */
	protected array $attributes = [
		'phone',
		'enabled',
	];

	/** @var Phone\Phone */
	private Phone\Phone $phone;

	public function __construct(
		Phone\Phone $phone,
		Common\Persistence\ManagerRegistry $managerRegistry,
		Translation\Translator $translator
	) {
		parent::__construct($managerRegistry, $translator);

		$this->phone = $phone;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getEntityName(): string
	{
		return Entities\Notifications\SmsNotification::class;
	}

	/**
	 * @param JsonAPIDocument\Objects\IStandardObject<mixed> $attributes
	 *
	 * @return Phone\Entities\Phone
	 *
	 * @throws JsonApiExceptions\IJsonApiException
	 * @throws Phone\Exceptions\NoValidCountryException
	 * @throws Phone\Exceptions\NoValidPhoneException
	 * @throws Phone\Exceptions\NoValidTypeException
	 */
	protected function hydratePhoneAttribute(
		JsonAPIDocument\Objects\IStandardObject $attributes
	): Phone\Entities\Phone {
		// Condition operator have to be set
		if (!$attributes->has('phone')) {
			throw new JsonApiExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				$this->translator->translate('messages.invalidPhone.heading'),
				$this->translator->translate('messages.invalidPhone.message'),
				[
					'pointer' => '/data/attributes/phone',
				]
			);
		}

		if (!$this->phone->isValid((string) $attributes->get('phone'), 'CZ')) {
			throw new JsonApiExceptions\JsonApiErrorException(
				StatusCodeInterface::STATUS_UNPROCESSABLE_ENTITY,
				$this->translator->translate('messages.invalidPhone.heading'),
				$this->translator->translate('messages.invalidPhone.message'),
				[
					'pointer' => '/data/attributes/phone',
				]
			);
		}

		return $this->phone->parse((string) $attributes->get('phone'), 'CZ');
	}

}
