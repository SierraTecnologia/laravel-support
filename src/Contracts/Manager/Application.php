<?php

namespace Support\Contracts\Manager;

interface Appplication
{

    public function getRoutes();

    public function getMigrations();

    public function getConfigs();

    /**
     * 
     */
    public function getClasses();
    public function getConsoles();
    public function getControllers();
    public function getModels();
    public function getViews();
}