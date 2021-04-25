<?php declare(strict_types = 1);

namespace Tests\Cases;

use FastyBird\ModulesMetadata\Types as ModulesMetadataTypes;
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
		$operatorType = ModulesMetadataTypes\TriggersConditionOperatorType::get(ModulesMetadataTypes\TriggersConditionOperatorType::OPERATOR_VALUE_EQUAL);

		Assert::type(ModulesMetadataTypes\TriggersConditionOperatorType::class, $operatorType);

		$operatorType = ModulesMetadataTypes\TriggersConditionOperatorType::get(ModulesMetadataTypes\TriggersConditionOperatorType::OPERATOR_VALUE_ABOVE);

		Assert::type(ModulesMetadataTypes\TriggersConditionOperatorType::class, $operatorType);

		$operatorType = ModulesMetadataTypes\TriggersConditionOperatorType::get(ModulesMetadataTypes\TriggersConditionOperatorType::OPERATOR_VALUE_BELOW);

		Assert::type(ModulesMetadataTypes\TriggersConditionOperatorType::class, $operatorType);
	}

	/**
	 * @throws Consistence\Enum\InvalidEnumValueException
	 */
	public function testInvalidOperator(): void
	{
		$operatorType = ModulesMetadataTypes\TriggersConditionOperatorType::get('invalidtype');
	}

}

$test_case = new ConditionOperatorTypeTest();
$test_case->run();
