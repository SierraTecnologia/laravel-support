<?php

namespace Support\Components\Database\Types\Mysql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Support\Components\Database\Types\Type;

class YearType extends Type
{
    const NAME = 'year';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'year';
    }
}
