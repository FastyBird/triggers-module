<?php declare(strict_types = 1);

/**
 * IChannelPropertyAction.php
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

namespace FastyBird\TriggersModule\Entities\Actions;

/**
 * Channel state action entity interface
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Entities
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IChannelPropertyAction extends IAction
{

	/**
	 * @return string
	 */
	public function getDevice(): string;

	/**
	 * @return string
	 */
	public function getChannel(): string;

	/**
	 * @return string
	 */
	public function getProperty(): string;

}
