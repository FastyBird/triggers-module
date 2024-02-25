<?php declare(strict_types = 1);

/**
 * ITriggersManager.php
 *
 * @license        More in LICENSE.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:TriggersModule!
 * @subpackage     Models
 * @since          1.0.0
 *
 * @date           04.04.20
 */

namespace FastyBird\Module\Triggers\Models\Entities\Triggers;

use FastyBird\Module\Triggers\Entities;
use FastyBird\Module\Triggers\Models;
use IPub\DoctrineCrud\Crud as DoctrineCrudCrud;
use IPub\DoctrineCrud\Exceptions as DoctrineCrudExceptions;
use Nette;
use Nette\Utils;
use function assert;

/**
 * Triggers entities manager
 *
 * @package        FastyBird:TriggersModule!
 * @subpackage     Models
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
final class TriggersManager
{

	use Nette\SmartObject;

	/** @var DoctrineCrudCrud\IEntityCrud<Entities\Triggers\Trigger>|null */
	private DoctrineCrudCrud\IEntityCrud|null $entityCrud = null;

	/**
	 * @param DoctrineCrudCrud\IEntityCrudFactory<Entities\Triggers\Trigger> $entityCrudFactory
	 */
	public function __construct(
		private readonly DoctrineCrudCrud\IEntityCrudFactory $entityCrudFactory,
	)
	{
	}

	public function create(Utils\ArrayHash $values): Entities\Triggers\Trigger
	{
		$entity = $this->getEntityCrud()->getEntityCreator()->create($values);
		assert($entity instanceof Entities\Triggers\Trigger);

		return $entity;
	}

	/**
	 * @throws DoctrineCrudExceptions\InvalidArgumentException
	 */
	public function update(
		Entities\Triggers\Trigger $entity,
		Utils\ArrayHash $values,
	): Entities\Triggers\Trigger
	{
		$entity = $this->getEntityCrud()->getEntityUpdater()->update($values, $entity);
		assert($entity instanceof Entities\Triggers\Trigger);

		return $entity;
	}

	/**
	 * @throws DoctrineCrudExceptions\InvalidArgumentException
	 */
	public function delete(Entities\Triggers\Trigger $entity): bool
	{
		// Delete entity from database
		return $this->getEntityCrud()->getEntityDeleter()->delete($entity);
	}

	/**
	 * @return DoctrineCrudCrud\IEntityCrud<Entities\Triggers\Trigger>
	 */
	public function getEntityCrud(): DoctrineCrudCrud\IEntityCrud
	{
		if ($this->entityCrud === null) {
			$this->entityCrud = $this->entityCrudFactory->create(Entities\Triggers\Trigger::class);
		}

		return $this->entityCrud;
	}

}
