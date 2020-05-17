<?php

namespace Support\Contracts\Categorizador;

interface InterfaceCategorizador
{
    public static function discoverType();
    public function isValid();
    public function getName();
}