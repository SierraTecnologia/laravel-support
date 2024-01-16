<?php

namespace SiHelper\Help\Dicionario\Seguranca\Tipo;

class Tipo
{
    public function __construct()
    {
        
    }

    /**
     * Quem pode ser agent (causador) dessa ação
     *
     * @return void
     */
    public function getAll()
    {
        return [
            Secreta::class,
            Confidencial::class,
            Reservada::class,
            Corporativa::class,
            Publica::class,
        ];
    }
}