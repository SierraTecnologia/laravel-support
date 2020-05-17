<?php

namespace Support\Contracts\Support;

/**
 * Allows the registering of transforming callbacks that get applied when the
 * class is serialized with toArray() or toJson().
 */
trait ArrayableTrait
{
    /**
     * Attributes to Array Mapper
     */
    // public static $mapper = [
    //     // 'Dicionario' => [
    //     //     'dicionarioTablesRelations',
    //     //     'dicionarioPrimaryKeys',
    //     // ],
    //     // 'Mapper' => [
    //     //     'mapperTableToClasses',
    //     //     'mapperParentClasses',
    //     //     'mapperClasserProcuracao',
    //     // ],
    //     // 'Leitoras' => [
    //     //     'displayTables',
    //     //     'displayClasses',
    //     // ],
    //     // 'AplicationTemp' => [
    //     //     'tempAppTablesWithNotPrimaryKey',
    //     //     'tempErrorClasses',
    //     //     'tempIgnoreClasses'
    //     // ],

    //     // // Esse eh manual pq pera da funcao
    //     // // 'Errors' => [
    //     // //     'errors',
    //     // // ]
    // ];



    public function toArray()
    {
        $multiDimensional = false;

        $dataToReturn = [];
        $mapper = self::$mapper;
        foreach ($mapper as $indice=>$dataArray) {
            if (is_array($dataArray)) {
                $multiDimensional = true;
                $dataToReturn[$indice] = [];
                foreach ($dataArray as $atributeNameVariable) {
                    $dataToReturn[$indice][$atributeNameVariable] = $this->$atributeNameVariable;
                }
            } else {
                $dataToReturn[$dataArray] = $this->$dataArray;
            }
        }

        if ($multiDimensional) {
            $dataToReturn['Errors'] = [];
            $dataToReturn['Errors']['errors'] = $this->getErrors();
        } else {
            $dataToReturn['errors'] = $this->getErrors();
        }

        return $dataToReturn;

        // return [
            
        //     'Dicionario' => [
        //         // Dados GErados
        //         'dicionarioTablesRelations' => $this->dicionarioTablesRelations,
        //         'dicionarioPrimaryKeys' => $this->dicionarioPrimaryKeys,
        //     ],

        //     'Mapper' => [
        //         /**
        //          * Mapper
        //          */
        //         'mapperTableToClasses' => $this->mapperTableToClasses,
        //         'mapperParentClasses' => $this->mapperParentClasses,
        //     ],
            
        //     'Leitoras' => [
        //         // Leitoras
        //         'displayTables' => $this->displayTables,
        //         'displayClasses' => $this->displayClasses,
        //     ],
    

        //     /**
        //      * Sistema
        //      */
        //     // Ok
            
        //     'AplicationTemp' => [
        //         // Nao ok
        //         'tempAppTablesWithNotPrimaryKey' => $this->tempAppTablesWithNotPrimaryKey,
        //         // 'classes' => [],

        //     ],
        //     'Errors' => [
        //         /**
        //          * Errors 
        //          **/
        //         'errors' => $this->getError(),

        //     ],
        // ];
    }

    public function fromArray($datas)
    {
        return $this->setArray($datas);
    }

    public function setArray($datas)
    {
        $multiDimensional = false;
        $mapper = self::$mapper;
        foreach ($mapper as $indice=>$mapperValue) {
            if (is_array($mapperValue)) {
                $multiDimensional = true;
                if (isset($datas[$indice])) {
                    foreach ($mapperValue as $atributeNameVariable) {
                        $this->$atributeNameVariable = $datas[$indice][$atributeNameVariable];
                    }
                }
            } else {
                if (isset($datas[$mapperValue])) {
                        $this->$mapperValue = $datas[$mapperValue];
                }
            }
        }

        if ($multiDimensional) {
            if (isset($datas['Errors'])) {
                if (isset($datas['Errors']['errors'])) {
                    $this->mergeErrors($datas['Errors']['errors']);
                }
            }
        } else {
            if (isset($datas['errors'])) {
                $this->mergeErrors($datas['errors']);
            }
        }
    }

    public function display()
    {
        $display = [];
        $array = $this->toArray();
        foreach ($array as $category => $infos) {
            if ($this->arrayIsMultiDimensional()) {
                foreach ($infos as $title => $value) {
                    $display[] = $category.' > '.$title;
                    $display[] = $value;
                }
            } else {
                $display[] = $category;
                $display[] = $infos;
            }
        }
        dd(
            ...$display
        );
    }

    private function arrayIsMultiDimensional()
    {
        $multiDimensional = false;
        $mapper = self::$mapper;
        foreach ($mapper as $indice=>$mapperValue) {
            if (is_array($mapperValue)) {
                $multiDimensional = true;
            }
        }
        return $multiDimensional;
    }
}
