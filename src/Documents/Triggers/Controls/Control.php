<?php declare(strict_types = 1);

/**
 * Control.php
 *
 * @license        More in LICENSE.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     Documents
 * @since          1.0.0
 *
 * @date           02.06.22
 */

namespace FastyBird\Module\Triggers\Documents\Triggers\Controls;

use FastyBird\Core\Application\Documents as ApplicationDocuments;
use FastyBird\Core\Application\ObjectMapper as ApplicationObjectMapper;
use FastyBird\Core\Exchange\Documents as ExchangeDocuments;
use FastyBird\Module\Triggers;
use FastyBird\Module\Triggers\Entities;
use Orisai\ObjectMapper;
use Ramsey\Uuid;

/**
 * Trigger control document
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Documents
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
#[ApplicationDocuments\Mapping\Document(entity: Entities\Triggers\Controls\Control::class)]
#[ExchangeDocuments\Mapping\RoutingMap([
	Triggers\Constants::MESSAGE_BUS_TRIGGER_CONTROL_DOCUMENT_REPORTED_ROUTING_KEY,
	Triggers\Constants::MESSAGE_BUS_TRIGGER_CONTROL_DOCUMENT_CREATED_ROUTING_KEY,
	Triggers\Constants::MESSAGE_BUS_TRIGGER_CONTROL_DOCUMENT_UPDATED_ROUTING_KEY,
	Triggers\Constants::MESSAGE_BUS_TRIGGER_CONTROL_DOCUMENT_DELETED_ROUTING_KEY,
])]
final class Control implements ApplicationDocuments\Document, ApplicationDocuments\Owner
{

	use ApplicationDocuments\TOwner;

	public function __construct(
		#[ApplicationObjectMapper\Rules\UuidValue()]
		private readonly Uuid\UuidInterface $id,
		#[ApplicationObjectMapper\Rules\UuidValue()]
		private readonly Uuid\UuidInterface $trigger,
		#[ObjectMapper\Rules\StringValue(notEmpty: true)]
		private readonly string $name,
		#[ApplicationObjectMapper\Rules\UuidValue()]
		protected readonly Uuid\UuidInterface|null $owner = null,
	)
	{
	}

	public function getId(): Uuid\UuidInterface
	{
		return $this->id;
	}

	public function getTrigger(): Uuid\UuidInterface
	{
		return $this->trigger;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function toArray(): array
	{
		return [
			'id' => $this->getId()->toString(),
			'trigger' => $this->getTrigger()->toString(),
			'name' => $this->getName(),
			'owner' => $this->getOwner()?->toString(),
		];
	}

}
