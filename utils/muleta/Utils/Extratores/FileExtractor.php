<?php

declare(strict_types=1);

namespace Muleta\Utils\Extratores;


use Illuminate\Support\Collection;

class FileExtractor
{
    
    /**
     * Retorna Nome no Singular caso nao exista, e minusculo
     *
     * @return string
     */
    public static function getFileName($filePath): string
    {
        $filePathParts = explode(DIRECTORY_SEPARATOR, $filePath);
        return array_pop($filePathParts);
    }
    public static function getFolderPathFromFile($filePath): string
    {
        $filePathParts = explode(DIRECTORY_SEPARATOR, $filePath);
        array_pop($filePathParts);
        return implode(DIRECTORY_SEPARATOR, $filePathParts);
    }

    /**
     * $filePath String localizacao do arquivo
     *
     * $ignore começo do arquivo a se ignorar
     *
     * @return Collection
     */
    public static function returnFoldersInarray($filePath, $ignore = false): self
    {
        if($ignore) {
            $filePath = \str_replace($ignore, '', $filePath);
        }
        $filePath = \str_replace(self::getFileName($filePath), '', $filePath);

        return (new Collection(
            explode(DIRECTORY_SEPARATOR, $filePath)
        ))->map(
            function ($name) {
                return strtolower($name);
            }
        )
        ->reject(
            function ($name) {
                return empty($name);
            }
        );
    }

}
