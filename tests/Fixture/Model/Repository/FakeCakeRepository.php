<?php

namespace CakeDDD\Repository\Test\Fixture\Model\Repository;

use CakeDDD\Repository\Model\Repository\CakeRepository;
use CakeDDD\Repository\Test\Fixture\Domain\Entity\FakeEntity;

/**
 * Implements the domain repository interface
 *
 * @extends CakeRepository<FakeEntity>
 */
class FakeCakeRepository extends CakeRepository implements IFakeRepository
{
    public function __construct()
    {
        parent::__construct('CakeDDD/Repository/Test/Fixture.FakeModels');
    }

    public function list(): iterable
    {
        return $this
            ->query()
            ->toArray();
    }

    public function save(FakeEntity $entity): FakeEntity
    {
        return parent::persist($entity);
    }

    public function getById(string $id): FakeEntity
    {
        return $this
            ->query()
            ->first();
    }

    public function delete(FakeEntity $entity): bool
    {
        return parent::remove($entity);
    }
}