<?php 

namespace Muleta\Packagist\Traits;

use Auth;
use Socialite;
use Illuminate\Http\Request;
use MediaManager\Models\User;
use Flash;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use View;
use Config;
use Session;
use ReflectionClass;
use Crypto;

trait PackageVersionTrait
{
    public $_version = null;
    protected $packageName = null;

    public function __construct()
    {
        $this->_version = $this->getVersion();
    }


    public function getVersion()
    {
        if (empty($this->_version)) {
            $this->findVersion;
        }
        return $this->version;
    }

    /**
     * Tenta capturar um token para o business via SERVER, POST, ou GET
     * Caso não ache ele usa o token padrão da passepague
     */
    protected function findVersion()
    {
        if (!is_null($this->version)) {
            return;
        }

        $filesystem = false;
        if (\property_exists($this, 'filesystem')) {
            $flesystem = $this->filesystem;
        }
        if (!$filesystem) {
            $filesystem = app(Filesystem::class);
        }
        if ($filesystem->exists(base_path('composer.lock'))) {
            // Get the composer.lock file
            $file = json_decode(
                $filesystem->get(base_path('composer.lock'))
            );

            // Loop through all the packages and get the version of transmissor
            foreach ($file->packages as $package) {
                if ($package->name == $this->packageName) {
                    $this->version = $package->version;
                    break;
                }
            }
        }

        if(!empty($_SERVER['HTTP_VERSION'])) {
            Log::info('Usando Version: '.$_SERVER['HTTP_VERSION']);
            return User::where('token', $_SERVER['HTTP_VERSION'])->first();
        }
            
        if(!empty($_POST['version'])) {
            Log::info('Usando Version: '.$_POST['version']);
            return User::where('token', $_POST['version'])->first();
        }
        
        if(!empty($_POST['VERSION'])) {
            Log::info('Usando Version: '.$_POST['VERSION']);
            return User::where('token', $_POST['VERSION'])->first();
        }
        
        if(!empty($_GET['version'])) {
            Log::info('Usando Version: '.$_GET['version']);
            return User::where('token', $_GET['version'])->first();
        }
        
        if(!empty($_GET['VERSION'])) {
            Log::info('Usando Version: '.$_GET['VERSION']);
            return User::where('token', $_GET['VERSION'])->first();
        }

        // @todo verificar arquivo .version ou package.json ou composer.json
        
        return $this->_version = config('app.version');
    }

}
