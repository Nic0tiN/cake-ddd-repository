<?php

namespace CakeDDD\Repository\Test\TestCase\Model\Repository;

use CakeDDD\Repository\Test\Fixture\Domain\Entity\FakeEntity;
use CakeDDD\Repository\Test\Fixture\Model\Repository\FakeCakeRepository;
use Cake\TestSuite\TestCase;

class CakeRepositoryTest extends TestCase
{
    protected array $fixtures = ['app.FakeModels'];

    public function testInitialize()
    {
        $repository = $this->getRepository();
        self::assertInstanceOf(FakeCakeRepository::class, $repository);
    }

    public function testQueryReturnsDomainEntities()
    {
        $repository = $this->getRepository();
        $entities = $repository->list();

        self::assertCount(1, $entities);

        $first = reset($entities);
        self::assertInstanceOf(FakeEntity::class, $first);
    }

    public function testGetEntityById()
    {
        $repository = $this->getRepository();

        self::assertInstanceOf(FakeEntity::class, $repository->getById('1'));
    }

    public function testCreateDomainEntity()
    {
        $repository = $this->getRepository();
        $expected = new FakeEntity('2', 'Some entity');
        $entity = $repository->persist($expected);

        self::assertEquals($expected, $entity);
    }

    public function testUpdateDomainEntity()
    {
        $repository = $this->getRepository();
        $expected = new FakeEntity('1', 'Some entity');
        $entity = $repository->persist($expected);

        self::assertEquals($expected, $entity);
    }

    public function testDeleteEntity()
    {
        $repository = $this->getRepository();
        $entity = new FakeEntity('1', 'Some value');

        self::assertTrue($repository->delete($entity));
    }

    private function getRepository(): FakeCakeRepository
    {
        return new FakeCakeRepository();
    }
}
