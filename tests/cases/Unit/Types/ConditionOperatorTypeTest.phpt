<?php declare(strict_types = 1);

namespace Tests\Cases;

use FastyBird\TriggersModule\Types;
use Ninjify\Nunjuck\TestCase\BaseTestCase;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

/**
 * @testCase
 */
final class ConditionOperatorTypeTest extends BaseTestCase
{

	public function testCreateOperator(): void
	{
		$operatorType = Types\ConditionOperatorType::get(Types\ConditionOperatorType::OPERATOR_VALUE_EQUAL);

		Assert::type(Types\ConditionOperatorType::class, $operatorType);

		$operatorType = Types\ConditionOperatorType::get(Types\ConditionOperatorType::OPERATOR_VALUE_ABOVE);

		Assert::type(Types\ConditionOperatorType::class, $operatorType);

		$operatorType = Types\ConditionOperatorType::get(Types\ConditionOperatorType::OPERATOR_VALUE_BELOW);

		Assert::type(Types\ConditionOperatorType::class, $operatorType);
	}

	/**
	 * @throws Consistence\Enum\InvalidEnumValueException
	 */
	public function testInvalidOperator(): void
	{
		$operatorType = Types\ConditionOperatorType::get('invalidtype');
	}

}

$test_case = new ConditionOperatorTypeTest();
$test_case->run();
