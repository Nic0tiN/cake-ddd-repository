<?php
return [
    'fake_models' => [
        'columns' => [
            'id' => [
                'type' => 'integer'
            ],
            'someColumn' => [
                'type' => 'string',
                'null' => false
            ]
        ],
        'constraints' => [
            'primary' => [
                'type' => 'primary',
                'columns' => [
                    'id',
                ],
            ],
        ],
    ],
    // More tables.
];