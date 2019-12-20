<?php

namespace Support\Discovers\Database\Types\Postgresql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Support\Discovers\Database\Types\Type;

class TimeStampTzType extends Type
{
    const NAME = 'timestamptz';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'timestamp(0) with time zone';
    }
}
