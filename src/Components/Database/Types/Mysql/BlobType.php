<?php

namespace Support\Components\Database\Types\Mysql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Support\Components\Database\Types\Type;

class BlobType extends Type
{
    const NAME = 'blob';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'blob';
    }
}
