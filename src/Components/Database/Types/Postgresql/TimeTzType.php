<?php

namespace Support\Components\Database\Types\Postgresql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Support\Components\Database\Types\Type;

class TimeTzType extends Type
{
    const NAME = 'timetz';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'time(0) with time zone';
    }
}
