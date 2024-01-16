<?php

namespace SiPlugins\ProjectManager;

use SiPlugins\SiPlugins as Base;
use SiWeapons\Manipuladores\Locations\Folder;

class ProjectManager extends Base
{
    public function __construct(Folder $folder)
    {
        $this->locationFolder = $folder;

        parent::__construct();
    }

    protected function mount()
    {
        return [
            'info' => $this->mountInfo(),
            'docs' => $this->mountDocs(),
        ];
    }

    protected function mountInfo()
    {
        $dependences = $this->getInfo()->extractorDependences();
        return [
            'name' => $dependences[0],
            'version' => $dependences[1],
        ];
    }

    protected function mountDocs()
    {
        return [
            'name' => $this->getInfo()->getName(),
            'version' => $this->getInfo()->getName(),
        ];
    }

    public function getInfo()
    {
        if (!$this->getInfoInstance) {
            $this->getInfoInstance = new GetInfo($this);
        }
        return $this->getInfoInstance;
    }
    

    public function mountReleases()
    {
        $input = new \ChangeLog\IO\File(
            [
            'file' => $this->locationFolder->getLocation('CHANGELOG.md'),
            ]
        );
        
        $parser = new \ChangeLog\Parser\KeepAChangeLog();
        
        $cl = new \ChangeLog\ChangeLog;
        $cl->setParser($parser);
        $cl->setInput($input);
        
        $log = $cl->parse();
        
        // Instance of ChangeLog\Log
        return $log->getReleases();
    }

    /**
     * Retorna se o sistema está instalado ou não
     *
     * @return boolean
     */
    public static function isInstall()
    {
        return \Schema::hasTable('persons') || \Schema::hasTable('businesses');
    }
}