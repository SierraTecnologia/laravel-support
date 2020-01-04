<?php

namespace Support\Coder\Discovers\Database\Types\Sqlite;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Support\Coder\Discovers\Database\Types\Type;

class RealType extends Type
{
    const NAME = 'real';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'real';
    }
}
