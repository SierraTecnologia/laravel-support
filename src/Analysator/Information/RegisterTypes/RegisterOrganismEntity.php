<?php
/**
 * Um sistema que evolui. (Religiao, Empresas, Predios, Pessoas)
 */

namespace Support\Analysator\Information\RegisterTypes;


class RegisterOrganismEntity extends AbstractRegisterType
{
    public static $name = 'Organism';
    public $examples = [
        'person', 'pessoa', 'personagem', 'persona',

        'business', 'negocio', 'organismo', 'empreendimento'
    ];




}
