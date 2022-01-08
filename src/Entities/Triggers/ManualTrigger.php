<?php declare(strict_types = 1);

/**
 * ManualTrigger.php
 *
 * @license        More in LICENSE.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     Entities
 * @since          0.1.0
 *
 * @date           04.04.20
 */

namespace FastyBird\TriggersModule\Entities\Triggers;

use Doctrine\ORM\Mapping as ORM;
use FastyBird\ModulesMetadata\Types as ModulesMetadataTypes;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="fb_triggers_manual",
 *     options={
 *       "collate"="utf8mb4_general_ci",
 *       "charset"="utf8mb4",
 *       "comment"="Manual triggers"
 *     }
 * )
 */
class ManualTrigger extends Trigger implements IManualTrigger
{

	/**
	 * {@inheritDoc}
	 */
	public function getType(): ModulesMetadataTypes\TriggerTypeType
	{
		return ModulesMetadataTypes\TriggerTypeType::get(ModulesMetadataTypes\TriggerTypeType::TYPE_MANUAL);
	}

}
