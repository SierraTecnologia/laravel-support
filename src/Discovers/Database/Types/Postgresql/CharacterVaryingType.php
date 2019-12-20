<?php

namespace Support\Discovers\Database\Types\Postgresql;

use Support\Discovers\Database\Types\Common\VarCharType;

class CharacterVaryingType extends VarCharType
{
    const NAME = 'character varying';
    const DBTYPE = 'varchar';
}
