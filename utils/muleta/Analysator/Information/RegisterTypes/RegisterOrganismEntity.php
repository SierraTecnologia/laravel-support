<?php
/**
 * Um sistema que evolui. (Religiao, Empresas, Predios, Pessoas)
 */

namespace Muleta\Analysator\Information\RegisterTypes;


class RegisterOrganismEntity extends AbstractRegisterType
{
    public static $name = 'Organism';
    public static $order = 100;
    public $examples = [
        'person', 'pessoa', 'personagem', 'persona',

        'business', 'negocio', 'organismo', 'empreendimento',

        'bank'
    ];




}
