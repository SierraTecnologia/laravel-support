<?php

namespace Support\Components\Database\Types\Mysql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Support\Components\Database\Types\Type;

class LineStringType extends Type
{
    const NAME = 'linestring';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'linestring';
    }
}
