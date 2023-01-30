<?php

return [
    'default' => 'local',

    'disks' => [
        'local' => [
            'driver' => 'local',
            'root'   => home_path('storage'),
        ],

        'cache' => [
            'driver' => 'local',
            'root'   => home_path('cache'),
            'throw'  => false,
        ],

        'image' => [
            'driver' => 'local',
            'root'   => home_path('images'),
        ],
    ],
];
