<?php

namespace SiObjects\Entities\Components;

use League\Flysystem\Vfs\VfsAdapter;
use League\Flysystem\Filesystem;
use VirtualFileSystem\FileSystem as Vfs;

/**
 * User Helper - Provides access to logged in user information in views.
 *
 * @author Ricardo Sierra <ricardo@sierratecnologia.com>
 */
class File
{

    protected $target = false;
    protected $content = false;
    protected $extension = false;
    protected $type = false;

    public function __construct($target, $content = false, $extension = false, $type = false)
    {
        $this->target = $target;
        $this->content = $content;
        $this->extension = $extension;
        $this->type = $type;
    }

    public function getFilesystem()
    {

        $adapter = new VfsAdapter(new Vfs);
        $filesystem = new Filesystem($adapter);
    }
     
    public function __toString()
    {
        return (string) $this->getTarget();
    }
    
    public function getTarget()
    {
        return $this->target;
    }
    
    public function getTmp()
    {
        if ($this->target) {
            return $this->getTarget();
        }


    }
}
?>