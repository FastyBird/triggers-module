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
		$operatorType = ModulesMetadataTypes\TriggerConditionOperatorType::get(ModulesMetadataTypes\TriggerConditionOperatorType::OPERATOR_VALUE_EQUAL);

		Assert::type(ModulesMetadataTypes\TriggerConditionOperatorType::class, $operatorType);

		$operatorType = ModulesMetadataTypes\TriggerConditionOperatorType::get(ModulesMetadataTypes\TriggerConditionOperatorType::OPERATOR_VALUE_ABOVE);

		Assert::type(ModulesMetadataTypes\TriggerConditionOperatorType::class, $operatorType);

		$operatorType = ModulesMetadataTypes\TriggerConditionOperatorType::get(ModulesMetadataTypes\TriggerConditionOperatorType::OPERATOR_VALUE_BELOW);

		Assert::type(ModulesMetadataTypes\TriggerConditionOperatorType::class, $operatorType);
	}

	/**
	 * @throws Consistence\Enum\InvalidEnumValueException
	 */
	public function testInvalidOperator(): void
	{
		ModulesMetadataTypes\TriggerConditionOperatorType::get('invalidtype');
	}

}

$test_case = new ConditionOperatorTypeTest();
$test_case->run();
