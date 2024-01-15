<?php
/**
 * Se liga a diversas Outras Tabelas atraves do:
 * atraves do
 * model_id
 * model
 */


namespace Muleta\Traits\Models;

trait ComplexRelationamentTrait
{
    // Acrescentar na tabela
    // protected static $COMPLEX_RELATIONAMENT_MODELS = [
    //     \Population\Models\Features\Qa\AnalyzerResult::class
    // ];

    public static function getTableName()
    {
        return ((new self)->getTable());
    }

}