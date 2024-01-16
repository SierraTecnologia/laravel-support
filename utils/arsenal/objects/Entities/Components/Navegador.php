<?php
/**
 * Version: 0.1
 */

namespace SiObjects\Entities\Components;

use SiWeapons\Filesystem\Local;
use SiWeapons\Filesystem\Dropbox;
use SiWeapons\Filesystem\S3;
use SiWeapons\Filesystem\Googledrive;


/**
 * User Helper - Provides access to logged in user information in views.
 *
 * @author Ricardo Sierra <ricardo@sierratecnologia.com>
 */
class Navegador
{
    protected $actualLocale = false;
    protected $connection = false;

    public function __construct($connection = false, $view = false)
    {
        $this->getConnection($connection);
        $this->view = $view;
    }

    /**
     * Pode ser uma pasta, arquivo ou um banco de dados
     */
    public function getActualLocale()
    {
        return $this->actualLocale;
    }

    /**
     * Pode ser uma pasta, arquivo ou um banco de dados
     */
    public function setActualLocale($locale)
    {
        $this->actualLocale = $locale;
        return $this;
    }

    private function getConnection($connection = false)
    {
        if ($connection===false) {
            return $this->connection = new Local;
        }
        return $this->connection = new $connection;
    }

    /**
     * @param File $file
     */
    public function get(File $file)
    {
        return $this->connection->get($file);
    }

    /**
     * @param Directory $dir
     */
    public function allFiles(Directory $directory)
    {
        return $this->connection->allFiles($directory);
    }

    /**
     * @param Directory  $dir
     * @param function   $function
     * @param bool|array $ignore
     */
    public function executeAPartirDe($init, Array $dirs, $function, $ignore = false)
    {
        foreach ($dirs as $dir) {
            $this->executeForEachContentForDir($dir, $function, $ignore, $init);
        }
    }

    /**
     * @param Directory  $dir
     * @param function   $function
     * @param bool|array $ignore
     */
    public function executeForEachContentForManyDirs(Array $dirs, $function, $ignore = false)
    {
        foreach ($dirs as $dir) {
            $this->executeForEachContentForDir($dir, $function, $ignore);
        }
    }

    /**
     * @param Directory  $dir
     * @param function   $function
     * @param bool|array $ignore   List with ignore files
     */
    public function executeForEachContentForDir(Directory $dir, $function, $ignore = false, $initFile = false)
    {
        $ativado = false;
        $files = $this->allFiles($dir);
        foreach ($files as $file) {

            if (!$initFile || $this->stayIn($file, $initFile)) {
                $ativado = true;
            }

            if ($ativado && $this->permitIn($file, $ignore)) {
                var_dump('Executando '.$file);
                $function($this->get($file));
            }
        }
    }

    /**
     * @param string $file
     * @param string $initFile List with initFile files or part file names
     */
    public function stayIn($file, $initFile)
    {
        $pattern = '/' . $initFile . '/';//Padrão a ser encontrado na string $file
        if (preg_match($pattern, $file)) {
            return true; // Encontrado
        }
        return false;
    }

    /**
     * @param string     $file
     * @param bool|array $ignore List with ignore files or part file names
     */
    public function permitIn($file, $ignore)
    {
        if (!is_array($ignore)) {
            return true;
        }

        if (in_array($file, $ignore)) {
            return false;
        }

        foreach($ignore as $ignoreString) {
            $pattern = '/' . $ignoreString . '/';//Padrão a ser encontrado na string $file
            if (preg_match($pattern, $file)) {
                return false; // Encontrado
            }
        }

        return true;
    }
}
?>