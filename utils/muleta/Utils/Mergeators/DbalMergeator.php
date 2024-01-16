<?php

declare(strict_types=1);

namespace Muleta\Utils\Mergeators;

use Pedreiro\Models\Base;

class DbalMergeator
{
    public static function mergeWithAttributes(Base $modelFind, array $data): Base
    {
        // @todo Fazer Atualizar Data
        return $modelFind;
    }

}
