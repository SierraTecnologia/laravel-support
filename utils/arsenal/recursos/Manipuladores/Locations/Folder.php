<?php

namespace SiWeapons\Manipuladores\Locations;

use Log;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;

class Folder
{
    private $raizLocation = '/';
    private $computer = false;

    private $adapter = false;
    private $fileSystem = false;
    /**
     * Undocumented function
     *
     * @param [type]  $raizLocation
     * @param boolean $computer     Caso falso entao é o
     *                              local
     */
    public function __construct($raizLocation, $computer = false)
    {
        $this->raizLocation = $raizLocation;
        $this->computer = $computer;

    }

    public function getLocation($file = false)
    {
        if ($file) {
            return $this->raizLocation.DIRECTORY_SEPARATOR.$file;
        }
        return $this->raizLocation;
    }

    public function getAdapter()
    {
        if (!$this->adapter) {
            $this->adapter = new Local($this->getLocation());
        }
        return $this->adapter;
    }

    public function getFilesystem()
    {
        if (!$this->filesystem) {
            $this->filesystem = new Filesystem($this->getAdapter());
        }
        return $this->filesystem;
    }

    public function getContent($path)
    {
        return $this->getFilesystem()->read($path);
    }

}
