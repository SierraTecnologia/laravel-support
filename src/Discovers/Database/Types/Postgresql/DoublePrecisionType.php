<?php

namespace Support\Discovers\Database\Types\Postgresql;

use Support\Discovers\Database\Types\Common\DoubleType;

class DoublePrecisionType extends DoubleType
{
    const NAME = 'double precision';
    const DBTYPE = 'float8';
}
