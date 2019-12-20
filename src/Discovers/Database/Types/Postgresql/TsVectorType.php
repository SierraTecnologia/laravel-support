<?php

namespace Support\Discovers\Database\Types\Postgresql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Support\Discovers\Database\Types\Type;

class TsVectorType extends Type
{
    const NAME = 'tsvector';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'tsvector';
    }
}
