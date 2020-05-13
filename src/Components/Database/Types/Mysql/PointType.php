<?php

namespace Support\Components\Database\Types\Mysql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Support\Components\Database\Types\Type;

class PointType extends Type
{
    const NAME = 'point';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'point';
    }
}
