<?php declare(strict_types = 1);

/**
 * ITimeCondition.php
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
use Nette\Utils;

/**
 * Time condition entity interface
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Entities
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface ITimeCondition extends ICondition
{

	/**
	 * @param DateTimeInterface $time
	 *
	 * @return void
	 */
	public function setTime(DateTimeInterface $time): void;

	/**
	 * @return DateTimeInterface
	 */
	public function getTime(): DateTimeInterface;

	/**
	 * @param Utils\ArrayHash<int>|int[]|mixed $days
	 *
	 * @return void
	 */
	public function setDays($days): void;

	/**
	 * @return Utils\ArrayHash<int>
	 */
	public function getDays(): Utils\ArrayHash;

}
