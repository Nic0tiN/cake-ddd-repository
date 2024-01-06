<?php

namespace CakeDDD\Repository\Test\Fixture\Model\Entity;

use CakeDDD\Repository\Model\Entity\CakeEntity;
use CakeDDD\Repository\Test\Fixture\Domain\Entity\FakeEntity;

/**
 * @extends CakeEntity<FakeEntity>
 */
class FakeModel extends CakeEntity
{
    /**
     * @param FakeEntity $domainEntity
     * @return static
     */
    public static function toRepository(mixed $domainEntity): static
    {
        return new static([
            'id' => $domainEntity->getId(),
            'someColumn' => $domainEntity->getValue()
        ]);
    }

    public function toDomain(): object
    {
        return new FakeEntity(
            $this->get('id'),
            $this->get('someColumn')
        );
    }
}