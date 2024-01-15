<?php

namespace Muleta\Contracts\Support;

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
        $mapper = static::$mapper;
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

        if(method_exists($this, 'getErrors')) {
            if ($multiDimensional) {
                $dataToReturn['Errors'] = [];
                $dataToReturn['Errors']['errors'] = $this->getErrors();
            } else {
                $dataToReturn['errors'] = $this->getErrors();
            }
        }

        return $dataToReturn;

    }

    public function fromArray($datas)
    {
        return $this->setArray($datas);
    }

    public function setArray($datas)
    {
        $multiDimensional = false;
        $mapper = static::$mapper;
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

        if(method_exists($this, 'mergeErrors')) {
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
            'Displau',
            ...$display
        );
    }

    private function arrayIsMultiDimensional()
    {
        $multiDimensional = false;
        $mapper = static::$mapper;
        foreach ($mapper as $indice=>$mapperValue) {
            if (is_array($mapperValue)) {
                $multiDimensional = true;
            }
        }
        return $multiDimensional;
    }
}
