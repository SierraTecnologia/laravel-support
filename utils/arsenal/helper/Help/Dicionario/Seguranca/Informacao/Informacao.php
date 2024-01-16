<?php

namespace SiHelper\Help\Dicionario\Seguranca\Informacao;

class Informacao
{
    public function __construct()
    {
        
    }

    /**
     * Quem pode ser agent (causador) dessa ação
     *
     * @return void
     */
    public function getAgents()
    {
        return [
            Human::class,
            Bot::class,
        ];
    }
}