<?php

namespace CakeDDD\Repository\Test\Fixture\Domain\Entity;

/**
 * Example of a domain entity
 */
class FakeEntity
{
    public function __construct(
        private string $id,
        private string $value
    ) {}

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}