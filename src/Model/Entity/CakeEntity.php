<?php

namespace CakeDDD\Repository\Model\Entity;

use Cake\ORM\Entity;

/**
 * @template TDomainEntity
 *
 * @note It's also possible to turn this abstraction to an interface with two static methods.
 */
abstract class CakeEntity extends Entity
{
    /**
     * Transform the domain entity to a cake entity
     * @param TDomainEntity $domainEntity
     * @return static
     */
    abstract public static function toRepository(mixed $domainEntity): static;

    /**
     * Transform this cake entity to a domain entity
     * @return TDomainEntity
     */
    abstract public function toDomain(): object;
}