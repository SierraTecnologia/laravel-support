<?php

namespace Support\Components\Database\Types\Postgresql;

use Support\Components\Database\Types\Common\CharType;

class CharacterType extends CharType
{
    const NAME = 'character';
    const DBTYPE = 'bpchar';
}
