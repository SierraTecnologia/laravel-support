<?php
/**
 * Recebe parametros e responde com o Entity Correspondente ou Gera um
 */

declare(strict_types=1);



namespace Support\Patterns\Manager;


use Log;
use Support\Components\Database\Render\Database;

class DatabaseManager
{
    protected $params;


    protected function render()
    {
        Log::debug(
            'Mount Database -> Renderizando'
        );
        $renderDatabase = (new Database($eloquentClasses));
        return $renderDatabase->toArray();
    }
}
