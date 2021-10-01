<?php declare(strict_types = 1);

/**
 * IControlManager.php
 *
 * @license        More in LICENSE.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     Models
 * @since          0.4.0
 *
 * @date           01.10.21
 */

namespace FastyBird\TriggersModule\Models\Triggers\Controls;

use FastyBird\TriggersModule\Entities;
use Nette\Utils;

/**
 * Triggers controls entities manager interface
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Models
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IControlsManager
{

	/**
	 * @param Utils\ArrayHash $values
	 *
	 * @return Entities\Triggers\Controls\IControl
	 */
	public function create(
		Utils\ArrayHash $values
	): Entities\Triggers\Controls\IControl;

	/**
	 * @param Entities\Triggers\Controls\IControl $entity
	 * @param Utils\ArrayHash $values
	 *
	 * @return Entities\Triggers\Controls\IControl
	 */
	public function update(
		Entities\Triggers\Controls\IControl $entity,
		Utils\ArrayHash $values
	): Entities\Triggers\Controls\IControl;

	/**
	 * @param Entities\Triggers\Controls\IControl $entity
	 *
	 * @return bool
	 */
	public function delete(
		Entities\Triggers\Controls\IControl $entity
	): bool;

}
