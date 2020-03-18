<?php

namespace Support\Discovers\Database\Types\Common;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Support\Discovers\Database\Types\Type;

class JsonType extends Type
{
    const NAME = 'json';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'json';
    }
}
