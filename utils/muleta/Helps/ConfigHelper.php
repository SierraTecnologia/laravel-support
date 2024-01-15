<?php 

namespace Muleta\Helps;

use Illuminate\Database\Capsule\Manager as Capsule;

class ConfigHelper
{
    /**
     * @return string[]
     *
     * @psalm-return array{0: 'vendor'}
     */
    public static function ignoreFolders(): array
    {
        return [
            'vendor'
        ];
    }
}