<?php

/*
|--------------------------------------------------------------------------
| CrudMaker Config
|--------------------------------------------------------------------------
|
| WARNING! do not change any thing that starts and ends with _
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Alias Blacklist
    |--------------------------------------------------------------------------
    |
    | Typically, Tinker automatically aliases classes as you require them in
    | Tinker. However, you may wish to never alias certain classes, which
    | you may accomplish by listing the classes in the following array.
    |
    */

    'models_alias' => [
        'App\Models',
        // 'Support\Models',

        'Informate\Models',
        'Translation\Models',
        'Locaravel\Models',
        'Population\Models',
        'Telefonica\Models',
        'Stalker\Models',
        'Audit\Models',
        'Tracking\Models',

        'Integrations\Models',
        'Transmissor\Models',
        'Bancario\Models',
        'Operador\Models',
        'Fabrica\Models',
        'Finder\Models',
        'Casa\Models',

        'Trainner\Models',
        'Gamer\Models',

        'Facilitador\Models',
        'Siravel\Models',
        'Boravel\Models',
    ],
];