<?php

namespace Support\Coder\Discovers\Database\Types\Postgresql;

use Support\Coder\Discovers\Database\Types\Common\VarCharType;

class CharacterVaryingType extends VarCharType
{
    const NAME = 'character varying';
    const DBTYPE = 'varchar';
}
