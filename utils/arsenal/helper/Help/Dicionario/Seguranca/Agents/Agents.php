<?php

namespace SiHelper\Help\Dicionario\Seguranca\Agents;

class Agents
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