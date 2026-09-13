<?php

namespace SiHelper\ChangeAmbiente;

use SiHelper\Help\Agents\Bot;
use SiHelper\Help\Agents\Human;

class Actions
{
    public static $description = 'São ações executadas por agentes externos';

    /**
     * Quem pode ser agent (causador) dessa ação
     *
     * @return void
     */
    public function whereCanBeAgent()
    {
        return [
            Human::class,
            Bot::class,
        ];
    }
    
    public function typeAccesses()
    {
        return [
            'human' => [

            ],
            'bot' => [

            ],
        ];
    }
    
}
