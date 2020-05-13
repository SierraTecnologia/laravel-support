<?php

namespace Support\Components\Database\Types\Postgresql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Support\Components\Database\Types\Type;

class TsQueryType extends Type
{
    const NAME = 'tsquery';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'tsquery';
    }
}
