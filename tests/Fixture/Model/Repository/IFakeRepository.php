<?php

namespace CakeDDD\Repository\Test\Fixture\Model\Repository;

use CakeDDD\Repository\Test\Fixture\Domain\Entity\FakeEntity;

/**
 * Simulate a repository interface define by the domain
 */
interface IFakeRepository
{
    public function list(): iterable;

    public function save(FakeEntity $entity);

    public function getById(string $id): FakeEntity;

    public function delete(FakeEntity $entity): bool;
}