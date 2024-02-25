<?php declare(strict_types = 1);

namespace FastyBird\Module\Triggers\Tests\Fixtures\Dummy;

use FastyBird\Library\Application\ObjectMapper as ApplicationObjectMapper;
use FastyBird\Library\Metadata\Documents\Mapping as DOC;
use FastyBird\Module\Triggers\Documents;
use Orisai\ObjectMapper;
use Ramsey\Uuid;
use function array_merge;

#[DOC\Document(entity: DummyActionEntity::class)]
#[DOC\DiscriminatorEntry(name: DummyActionEntity::TYPE)]
final class DummyActionDocument extends Documents\Actions\Action
{

	public function __construct(
		Uuid\UuidInterface $id,
		Uuid\UuidInterface $trigger,
		bool $enabled,
		#[ApplicationObjectMapper\Rules\UuidValue()]
		#[ObjectMapper\Modifiers\FieldName('do_item')]
		private readonly Uuid\UuidInterface $doItem,
		#[ObjectMapper\Rules\AnyOf([
			new ObjectMapper\Rules\BoolValue(),
			new ObjectMapper\Rules\StringValue(notEmpty: true),
		])]
		private readonly string|bool $value,
		bool|null $isTriggered = null,
		Uuid\UuidInterface|null $owner = null,
	)
	{
		parent::__construct($id, $trigger, $enabled, $isTriggered, $owner);
	}

	public static function getType(): string
	{
		return DummyActionEntity::TYPE;
	}

	public function getDoItem(): Uuid\UuidInterface
	{
		return $this->doItem;
	}

	public function getValue(): string|bool
	{
		return $this->value;
	}

	public function toArray(): array
	{
		return array_merge(parent::toArray(), [
			'do_item' => $this->getDoItem()->toString(),
			'value' => $this->getValue(),
		]);
	}

}
