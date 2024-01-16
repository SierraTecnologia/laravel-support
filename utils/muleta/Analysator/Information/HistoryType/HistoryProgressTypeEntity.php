<?php
/**
 *  Informacao que progride (DInheiro, skill, contatos, idade, etc)
 */

namespace Muleta\Analysator\Information\HistoryType;


class HistoryProgressTypeEntity extends AbstractHistoryType
{
    public static $name = 'Progress';
    public static $order = 2;

    public $examples = [
        'user',
        'idade',
        'contato','contact',
        'skill', 'habilidade', 'caracteristica',

        'weapon',




        'video',

        'page',

        'project', 'projeto', 'trabalho', 'worker', 
        'lib', 'biblioteca',

        'branch', 



        'language', 'linguagem', 'idioma'
    ];




}
