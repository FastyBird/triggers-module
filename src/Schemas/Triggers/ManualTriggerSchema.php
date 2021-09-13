<?php declare(strict_types = 1);

/**
 * ManualTriggerSchema.php
 *
 * @license        More in LICENSE.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     Schemas
 * @since          0.1.0
 *
 * @date           04.04.20
 */

namespace FastyBird\TriggersModule\Schemas\Triggers;

use FastyBird\TriggersModule\Entities;
use FastyBird\TriggersModule\Models;
use IPub\SlimRouter\Routing;
use Neomerx\JsonApi;

/**
 * Manual trigger entity schema
 *
 * @package         FastyBird:TriggersModule!
 * @subpackage      Schemas
 *
 * @author          Adam Kadlec <adam.kadlec@fastybird.com>
 *
 * @phpstan-extends TriggerSchema<Entities\Triggers\IManualTrigger>
 */
final class ManualTriggerSchema extends TriggerSchema
{

	/**
	 * Define entity schema type string
	 */
	public const SCHEMA_TYPE = 'triggers-module/trigger-manual';

	/** @var Models\States\ITriggerItemRepository|null */
	private ?Models\States\ITriggerItemRepository $triggerItemRepository;

	public function __construct(
		Routing\IRouter $router,
		?Models\States\ITriggerItemRepository $triggerItemRepository
	) {
		parent::__construct($router);

		$this->triggerItemRepository = $triggerItemRepository;
	}

	/**
	 * @return string
	 */
	public function getType(): string
	{
		return self::SCHEMA_TYPE;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getEntityClass(): string
	{
		return Entities\Triggers\ManualTrigger::class;
	}

	/**
	 * @param Entities\Triggers\IManualTrigger $trigger
	 * @param JsonApi\Contracts\Schema\ContextInterface $context
	 *
	 * @return iterable<string, string|bool|null>
	 *
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
	 */
	public function getAttributes($trigger, JsonApi\Contracts\Schema\ContextInterface $context): iterable
	{
		$isTriggered = null;

		if ($this->triggerItemRepository !== null) {
			$isTriggered = true;

			foreach ($trigger->getActions() as $action) {
				$state = $this->triggerItemRepository->findOne($action->getId());

				if ($state === null || $state->getValidationResult() === false) {
					$isTriggered = false;
				}
			}
		}

		return array_merge((array) parent::getAttributes($trigger, $context), [
			'is_triggered' => $isTriggered,
		]);
	}

}
