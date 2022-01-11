<?php declare(strict_types = 1);

/**
 * IPublisher.php
 *
 * @license        More in LICENSE.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     Exchange
 * @since          0.6.0
 *
 * @date           09.01.22
 */

namespace FastyBird\TriggersModule\Exchange;

use FastyBird\Metadata\Types as MetadataTypes;
use Nette\Utils;

/**
 * Module exchange publisher interface
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Publishers
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IPublisher
{

	/**
	 * @param MetadataTypes\ModuleOriginType $origin
	 * @param MetadataTypes\RoutingKeyType $routingKey
	 * @param Utils\ArrayHash|null $data
	 *
	 * @return void
	 */
	public function publish(
		MetadataTypes\ModuleOriginType $origin,
		MetadataTypes\RoutingKeyType $routingKey,
		?Utils\ArrayHash $data
	): void;

}
