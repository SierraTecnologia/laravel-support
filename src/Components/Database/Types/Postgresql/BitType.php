<?php

namespace Support\Components\Database\Types\Postgresql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Support\Components\Database\Types\Type;

class BitType extends Type
{
    const NAME = 'bit';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        $length = empty($field['length']) ? 1 : $field['length'];

        return "bit({$length})";
    }
}
