<?php
/**
 * Informações Fixas que nunca mudam (data de Aniversario, nome, etc)
 */

namespace Support\Analysator\Information\HistoryType;


class HistoryImutavelTypeEntity extends AbstractHistoryType
{
    public static $name = 'Imutavel';

    public $examples = [
        'name',
        'aniversario','nascimento','birthday',
        'email',
        'telefone','phone',
        // 'name',
    ];



}
