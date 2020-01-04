<?php

namespace Support\Coder\Discovers\Database\Types\Postgresql;

use Support\Coder\Discovers\Database\Types\Common\CharType;

class CharacterType extends CharType
{
    const NAME = 'character';
    const DBTYPE = 'bpchar';
}
