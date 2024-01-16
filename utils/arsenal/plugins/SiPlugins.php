<?php

namespace SiPlugins;

class SiPlugins
{
    public function __construct()
    {

    }

    public function getMount()
    {
        return $this->mount();
    }
    

    public function getInfo()
    {
        return $this->mountInfo();
    }
    
}