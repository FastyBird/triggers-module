<?php declare(strict_types = 1);

/**
 * ActionsManager.php
 *
 * @license        More in LICENSE.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     Models
 * @since          1.0.0
 *
 * @date           08.02.22
 */

namespace FastyBird\Module\Triggers\Models\States;

use FastyBird\Library\Exchange\Publisher as ExchangePublisher;
use FastyBird\Library\Metadata\Documents as MetadataDocuments;
use FastyBird\Library\Metadata\Exceptions as MetadataExceptions;
use FastyBird\Module\Triggers;
use FastyBird\Module\Triggers\Documents;
use FastyBird\Module\Triggers\Entities;
use FastyBird\Module\Triggers\Exceptions;
use FastyBird\Module\Triggers\Models;
use FastyBird\Module\Triggers\States;
use Nette;
use Nette\Utils;
use function array_merge;

/**
 * Action states manager
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Models
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
final class ActionsManager
{

	use Nette\SmartObject;

	public function __construct(
		protected readonly MetadataDocuments\DocumentFactory $documentFactory,
		protected readonly IActionsManager|null $manager = null,
		protected readonly ExchangePublisher\Publisher|null $publisher = null,
	)
	{
	}

	/**
	 * @throws Exceptions\NotImplemented
	 * @throws MetadataExceptions\InvalidArgument
	 * @throws MetadataExceptions\InvalidState
	 * @throws MetadataExceptions\MalformedInput
	 */
	public function create(
		Entities\Actions\Action $action,
		Utils\ArrayHash $values,
	): States\Action
	{
		if ($this->manager === null) {
			throw new Exceptions\NotImplemented('Action state manager is not registered');
		}

		$createdState = $this->manager->create($action->getId(), $values);

		$this->publishEntity($action, $createdState);

		return $createdState;
	}

	/**
	 * @throws Exceptions\NotImplemented
	 * @throws MetadataExceptions\InvalidArgument
	 * @throws MetadataExceptions\InvalidState
	 * @throws MetadataExceptions\MalformedInput
	 */
	public function update(
		Entities\Actions\Action $action,
		States\Action $state,
		Utils\ArrayHash $values,
	): States\Action
	{
		if ($this->manager === null) {
			throw new Exceptions\NotImplemented('Action state manager is not registered');
		}

		$updatedState = $this->manager->update($action->getId(), $values);

		if ($updatedState === false) {
			return $state;
		}

		$this->publishEntity($action, $updatedState);

		return $updatedState;
	}

	/**
	 * @throws Exceptions\NotImplemented
	 * @throws MetadataExceptions\InvalidArgument
	 * @throws MetadataExceptions\InvalidState
	 * @throws MetadataExceptions\MalformedInput
	 */
	public function delete(
		Entities\Actions\Action $action,
		States\Action $state,
	): bool
	{
		if ($this->manager === null) {
			throw new Exceptions\NotImplemented('Action state manager is not registered');
		}

		$result = $this->manager->delete($action->getId());

		if ($result) {
			$this->publishEntity($action, null);
		}

		return $result;
	}

	/**
	 * @throws MetadataExceptions\InvalidArgument
	 * @throws MetadataExceptions\InvalidState
	 * @throws MetadataExceptions\MalformedInput
	 */
	private function publishEntity(
		Entities\Actions\Action $action,
		States\Action|null $state,
	): void
	{
		if ($this->publisher === null) {
			return;
		}

		$this->publisher->publish(
			$action->getSource(),
			Triggers\Constants::MESSAGE_BUS_ACTION_DOCUMENT_UPDATED_ROUTING_KEY,
			$this->documentFactory->create(
				Documents\Actions\Action::class,
				array_merge(
					$action->toArray(),
					[
						'is_triggered' => !($state === null) && $state->isTriggered(),
					],
				),
			),
		);
	}

}
