<?php declare(strict_types = 1);

/**
 * EmailNotification.php
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

namespace FastyBird\Module\Triggers\Schemas\Notifications;

use FastyBird\Library\Metadata\Types\ModuleSource;
use FastyBird\Module\Triggers\Entities;
use Neomerx\JsonApi;
use function array_merge;

/**
 * Trigger email notification entity schema
 *
 * @extends Notification<Entities\Notifications\EmailNotification>
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Schemas
 */
final class EmailNotification extends Notification
{

	/**
	 * Define entity schema type string
	 */
	public const SCHEMA_TYPE = ModuleSource::SOURCE_MODULE_TRIGGERS . '/notification/email';

	public function getType(): string
	{
		return self::SCHEMA_TYPE;
	}

	public function getEntityClass(): string
	{
		return Entities\Notifications\EmailNotification::class;
	}

	/**
	 * @return iterable<string, string|bool|null>
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function getAttributes(
		$resource,
		JsonApi\Contracts\Schema\ContextInterface $context,
	): iterable
	{
		return array_merge((array) parent::getAttributes($resource, $context), [
			'email' => $resource->getEmail(),
		]);
	}

}
