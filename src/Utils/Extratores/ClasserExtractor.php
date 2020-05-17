<?php

declare(strict_types=1);

namespace Support\Utils\Extratores;

class ClasserExtractor
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

}
