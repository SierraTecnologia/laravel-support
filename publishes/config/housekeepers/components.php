<?php

return [




    /**
     * Os comandos do App\Console sao carregados automagicamente
     */
    'commandsFolders' => [
        'vendor/sierratecnologia/tools/siravel/Console/Commands',
        'vendor/sierratecnologia/facilitador/src/Console/Commands',
        'vendor/sierratecnologia/finder/src/Console/Commands',
        'vendor/sierratecnologia/laravel-support/src/Console/Commands'
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Config
    |--------------------------------------------------------------------------
    |
    | Here you can specify facilitador database settings
    |
    */

    'database' => [
        'tables' => [
            'hidden' => ['migrations', 'data_rows', 'data_types', 'menu_items', 'password_resets', 'permission_role', 'settings'],
        ],
    ],
];
