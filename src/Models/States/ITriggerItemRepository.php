<?php declare(strict_types = 1);

/**
 * ITriggerItemRepository.php
 *
 * @license        More in LICENSE.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     Models
 * @since          0.3.0
 *
 * @date           13.09.20
 */

namespace FastyBird\TriggersModule\Models\States;

use FastyBird\TriggersModule\States;
use Ramsey\Uuid;

/**
 * Trigger item repository interface
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Models
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface ITriggerItemRepository
{

	/**
	 * @param Uuid\UuidInterface $id
	 *
	 * @return States\ITriggerItem|null
	 */
	public function findOne(
		Uuid\UuidInterface $id
	): ?States\ITriggerItem;

}
