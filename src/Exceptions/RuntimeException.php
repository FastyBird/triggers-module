<?php declare(strict_types = 1);

/**
 * RuntimeException.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     Exceptions
 * @since          0.1.0
 *
 * @date           04.04.20
 */

namespace FastyBird\TriggersModule\Exceptions;

use RuntimeException as PHPRuntimeException;

class RuntimeException extends PHPRuntimeException implements IException
{

}
