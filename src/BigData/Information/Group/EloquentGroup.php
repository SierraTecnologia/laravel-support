<?php
/**
 * Trata os Agrupamentos de Modelos
 */

namespace Support\BigData\Informate\Group;


class EloquentGroup
{

    /**
     * Construct
     */
    public function __construct()
    {
        

    }




    /**
     * Agrupando
     */ 
    public function groupByNamespace()
    {
        $namespaces = [];
        $namespaces = [
            'name' => 'Calendar',
            'localeNamespace' => 'App\Models',
            'tables' => []
        ];

        return $namespaces;
    }



}
