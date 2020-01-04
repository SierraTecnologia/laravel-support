<?php

declare(strict_types=1);

namespace Support\ClassesHelpers\Traits\Manipulate;

use Support\Coder\Parser\ParseClass;

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
        return ParseClass::getFileName(get_class($class));
    }

    /**
     * Gets the class name.
     * @return string
     */
    public static function getClassName()
    {
        return ParseClass::getClassName(static::class);
    }
}
