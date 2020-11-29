<?php declare(strict_types = 1);

/**
 * IDateCondition.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     Entities
 * @since          0.1.0
 *
 * @date           04.04.20
 */

namespace FastyBird\TriggersModule\Entities\Conditions;

use DateTimeInterface;

/**
 * Date condition entity interface
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Entities
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IDateCondition extends ICondition
{

	/**
	 * @param DateTimeInterface $date
	 *
	 * @return void
	 */
	public function setDate(DateTimeInterface $date): void;

	/**
	 * @return DateTimeInterface
	 */
	public function getDate(): DateTimeInterface;

}
