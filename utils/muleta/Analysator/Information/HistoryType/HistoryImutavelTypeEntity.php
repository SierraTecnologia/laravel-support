<?php
/**
 * Informações Fixas que nunca mudam (data de Aniversario, nome, etc)
 */

namespace Muleta\Analysator\Information\HistoryType;


class HistoryImutavelTypeEntity extends AbstractHistoryType
{
    public static $name = 'Imutavel';
    public static $order = 5;

    public $examples = [
        'name',
        'aniversario','nascimento','birthday',
        'email',
        'telefone','phone',
        // 'name',

        'type', 'tipo',


        'mensagem','message', 'envelop', 'carta',





        'field', 'commit',

        'release', 'versão', 'version'
    ];



}
