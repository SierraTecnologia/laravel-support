<?php
/**
 * 
 */

namespace SiObjects\Entities\Views;

use Log;
use Integrations\Tools\Software\FilePrograms;

class File extends Board
{

    public function editAction()
    {
        
    }

    public function showAction()
    {
        
    }

    /**
     * 
     */
    protected function dashboard()
    {

    }

    protected function getInteresses()
    {
        return [

        ];
    }

    /**
     * 
     */
    public function getPrograms()
    {

        return [
            FilePrograms::class
        ];
    }

}
