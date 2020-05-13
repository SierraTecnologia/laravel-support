<?php

namespace Support\Components\Database\Types\Postgresql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Support\Components\Database\Types\Type;

class TimeStampType extends Type
{
    const NAME = 'timestamp';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'timestamp(0) without time zone';
    }
}
