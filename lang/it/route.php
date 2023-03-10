<?php

return [
    'quickadmin' =>
    [
        'authentication' => 'Autenticazione',
        'admin' => [
            'rdr' => 'Admin',
            'dashboard' => [
                'index' => 'Dashboard',
            ],
            'settings' => [
                'index' => 'Impostazioni',
            ],
            'users' => [
                'rdr' => 'Utenti',
                'list' => [
                    'index' => 'Elenco'
                ],
                'roles' => [
                    'index' => 'Ruoli'
                ],
                'permissions' => [
                    'index' => 'Permessi'
                ],
                'impersonate' => [
                    'index' => 'Impersona'
                ],
            ],
        ],
        'manage' => [
            'rdr' => 'Gestione',
            'contacts' => [
                'index' => 'Contatti',
            ],
            'dashboard' => [
                'index' => 'Dashboard',
            ],
            'settings' => [
                'index' => 'Impostazioni',
            ],
        ],
        'user' => [
            'rdr' => 'User',
            'dashboard' => [
                'index' => 'Dashboard',
            ],
            'account' => [
                'index' => 'Account',
            ],
            'profile' => [
                'index' => 'Profilo',
            ],
            'settings' => [
                'index' => 'Impostazioni',
            ],
        ],
        'builder' => [
            'rdr' => 'Costruttore',
            'dashboard' => [
                'index' => 'Dashboard',
            ],
            'settings' => [
                'index' => 'Impostazioni',
                'rdr' => 'Definisci impostazioni',
                'list' =>
                [
                    'index' => 'Elenco impostazioni',
                ],
                'type' => [
                    'index' => 'Tipi di interfaccia',
                ],
            ],
        ],
    ],
    'login' => 'Accesso',
    'logout' => 'Disconnetti',
    'password' => [
        'request' => 'Password dimenticata',
        'reset' => 'Rinnova password',
    ],
    'register'=>'Registrazione',
    'welcome' => 'Home',
];
