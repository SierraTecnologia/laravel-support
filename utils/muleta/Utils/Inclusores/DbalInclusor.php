<?php

declare(strict_types=1);

namespace Muleta\Utils\Inclusores;

use Log;
use ArgumentCountError;
use Symfony\Component\Inflector\Inflector;

use Muleta\Utils\Modificators\StringModificator;

class DbalInclusor
{
    /**
     * Retorna Nome no Singular caso nao exista, e minusculo
     */
    public static function includeDataFromEloquentEntity($eloquentEntityForModel, $data, $keyName)
    {
        if ((!isset($data['name']) || empty($data['name']))  
            && (isset($data[$keyName]) && $eloquentEntityForModel->hasColumn('name') && $eloquentEntityForModel->columnIsType($keyName, 'string'))
        ) {
            $data['name'] = StringModificator::convertSlugToName($data[$keyName]);
        }
        return $data;
    }
}
