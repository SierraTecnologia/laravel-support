<?php

namespace Support\Components\Database\Types\Mysql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Support\Components\Database\Types\Type;

class GeometryCollectionType extends Type
{
    const NAME = 'geometrycollection';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'geometrycollection';
    }
}
