<?php

namespace CakeDDD\Repository\Test\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class FakeModelsFixture extends TestFixture
{
    public string $connection = 'test';

    public array $records = [
        [
            'id' => 1,
            'someColumn' => 'Some value'
        ]
    ];
}