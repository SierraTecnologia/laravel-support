<?php

namespace Support\Coder\Discovers\Database\Types\Postgresql;

use Support\Coder\Discovers\Database\Types\Common\DoubleType;

class DoublePrecisionType extends DoubleType
{
    const NAME = 'double precision';
    const DBTYPE = 'float8';
}
