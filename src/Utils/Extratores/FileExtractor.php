<?php

declare(strict_types=1);

namespace Support\Utils\Extratores;


use Illuminate\Support\Collection;

class FileExtractor
{
    
    /**
     * Retorna Nome no Singular caso nao exista, e minusculo
     */
    public static function getFileName($filePath)
    {
        $filePathParts = explode('/', $filePath);
        return array_pop($filePathParts);
    }
    public static function getFolderPathFromFile($filePath)
    {
        $filePathParts = explode('/', $filePath);
        array_pop($filePathParts);
        return implode('/', $filePathParts);
    }

    /**
     * $filePath String localizacao do arquivo
     * 
     * $ignore começo do arquivo a se ignorar
     */
    public static function returnFoldersInarray($filePath, $ignore = false)
    {
        if($ignore) {
            $filePath = \str_replace($ignore, '', $filePath);
        }
        $filePath = \str_replace(self::getFileName($filePath), '', $filePath);

        return (new Collection(
            explode('/', $filePath)
        ))->map(function ($name) {
            return strtolower($name);
        })
        ->reject(function ($name) {
            return empty($name);
        });
    }

}
