<?php

namespace Support\Contracts\Categorizador;

interface InterfaceCategorizador
{
    public static function discoverType(string $name): string;
    public function isValid($name);
    public function getName();
}