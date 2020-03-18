<?php

namespace Support\Discovers\Database\Types\Postgresql;

use Support\Discovers\Database\Types\Common\CharType;

class CharacterType extends CharType
{
    const NAME = 'character';
    const DBTYPE = 'bpchar';
}
