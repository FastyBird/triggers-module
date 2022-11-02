<?php declare(strict_types = 1);

/**
 * Entity.php
 *
 * @license        More in LICENSE.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     Entities
 * @since          0.1.0
 *
 * @date           25.05.20
 */

namespace FastyBird\Module\Triggers\Entities;

use FastyBird\Library\Metadata\Types as MetadataTypes;
use IPub\DoctrineCrud;
use Ramsey\Uuid;

/**
 * Base entity interface
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Entities
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface Entity extends DoctrineCrud\Entities\IEntity
{

	public function getId(): Uuid\UuidInterface;

	public function getPlainId(): string;

	/**
	 * @return Array<mixed>
	 */
	public function toArray(): array;

	public function getSource(): MetadataTypes\ModuleSource|MetadataTypes\ConnectorSource|MetadataTypes\PluginSource;

}
