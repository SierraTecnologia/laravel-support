<?php

declare(strict_types=1);

namespace Support\ClassesHelpers\Traits\Manipulate;

trait FileManipulate
{

    /**
     * Load Alias and Providers
     */
    protected function getFileName($filePath)
    {
        $filePathParts = explode('/', $filePath);
        return array_pop($filePathParts);
    }
    protected function getFolderPathFromFile($filePath)
    {
        $filePathParts = explode('/', $filePath);
        array_pop($filePathParts);
        return implode('/', $filePathParts);
    }
    
    protected function getFileFromClass($class)
    {
        return (new \ReflectionClass(get_class($class)))->getFileName();
    }
}
