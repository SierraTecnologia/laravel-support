<?php

namespace Support\Services\System;

use Illuminate\Support\Facades\Config;
use SiPlugins\ProjectManager\ProjectManager;
use SiWeapons\Manipuladores\Locations\Folder;

class VersionService
{
    protected $projectManager = false;

    public function __construct()
    {
        $this->projectManager = new ProjectManager(new Folder(base_path()));
    }

    public function getProjectManager()
    {
        return $this->projectManager;
    }

    public function getInfos()
    {
        return $this->getProjectManager()->mountInfo();
    }

    public function getReleases()
    {
        return $this->getProjectManager()->mountReleases();
    }

    /**
     * Retorna se o sistema está instalado ou não
     *
     * @return boolean
     */
    public static function isInstall()
    {
        return ProjectManager::isInstall();
    }
}
