<?php
/**
 *  Informacao que progride (DInheiro, skill, contatos, idade, etc)
 */

namespace Support\Analysator\Information\HistoryType;


class HistoryProgressTypeEntity extends AbstractHistoryType
{
    public static $name = 'Progress';

    public $examples = [
        'idade',
        'contato','contact',
        'skill', 'habilidade', 'caracteristica',

        'weapon'
    ];




}
