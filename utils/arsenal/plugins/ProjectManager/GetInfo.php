<?php

namespace SiPlugins\ProjectManager;

use SiPlugins\SiPlugins as Base;
use SiWeapons\Manipuladores\Locations\Folder;

class GetInfo extends Base
{
    public function __construct(ProjectManager $projectManager)
    {
        $this->projectManager = $projectManager;
    }

    public function extractorDependences()
    {
        $composerInfo = new ComposerLockParser\ComposerInfo($this->projectManager->getLocation().'/composer.lock');
        $packages = $composerInfo->getPackages();
        Log::info('Packages');
        Log::info($packages);
        return [
            $packages[0]->getName(),
            $packages[0]->getVersion(),
            $packages[0]->getNamespace(),
        ];
    }
    
}