<?php
return [
    'welcome' => 'fa-solid fa-house',
    'login' => 'fa-solid fa-right-to-bracket',
    'logout' => 'fa-solid fa-right-from-bracket',
    'quickadmin' => [
        'admin' =>
        [
            'rdr' => 'fa-solid fa-gears',
            'dashboard' =>
            [
                'index' => 'fa-solid fa-gauge'
            ],
            'settings' =>
            [
                'index' => 'fa-solid fa-sliders'
            ],
            "users" => [
                'rdr' => 'fa-solid fa-users',
                "list" => [
                    'index' => 'fa-solid fa-table-list'
                ],
                'roles' => [
                    'index' => 'fa-solid fa-wand-magic-sparkles'
                ],
                'permissions' => [
                    'index' => 'fa-solid fa-shield-halved'
                ],
                'impersonate' => [
                    'index' => 'fa-solid fa-people-arrows'
                ],
            ],
        ],
        'manage' =>
        [
            'rdr' => 'fa-solid fa-screwdriver-wrench',
            'dashboard' =>
            [
                'index' => 'fa-solid fa-gauge'
            ],
        ],
        'user' =>
        [
            'rdr' => 'fa-regular fa-user',
            'dashboard' =>
            [
                'index' => 'fa-solid fa-gauge'
            ],
            'account' => [
                'index' => 'fa-solid fa-key',
            ],
            'profile' => [
                'index' => 'fa-regular fa-address-card',
            ],
            'settings' => [
                'index' => 'fa-solid fa-sliders',
            ],
        ],
        'builder' => [
            'rdr' => 'fa-solid fa-tower-observation',
            'settings' => [
                'rdr' => 'fa-solid fa-sliders',
                'list' =>
                [
                    'index' => 'fa-solid fa-table-list',
                ],
                'type' => [
                    'index' => 'fa-solid fa-hashtag',
                ],
            ],
        ],
    ]
];
