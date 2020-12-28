<?php

namespace Support;

use App\Models\User;
use Arrilot\Widgets\Facade as Widget;
use Bkwld\Library;
use Config;
use Crypto;
use Siravel\Models\Negocios\Menu;
use Siravel\Models\Negocios\MenuItem;
use Facilitador\Models\Permission;
use Porteiro\Models\Role;
use Facilitador\Models\Setting;
use Facilitador\Models\Translation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Pedreiro\Pedreiro;
use Pedreiro\Template\Actions\DeleteAction;
use Pedreiro\Template\Actions\EditAction;
use Pedreiro\Template\Actions\RestoreAction;
use Pedreiro\Template\Actions\ViewAction;
use ReflectionClass;
use Request;
use Session;
use Siravel\Models\Blog\Category;
use Siravel\Models\Blog\Post;
use Siravel\Models\Negocios\Page;
use Support\Events\AlertsCollection;
use Support\Models\Application\DataRelationship;
use Support\Models\Application\DataRow;
use Support\Models\Application\DataType;
use Translation\Traits\HasTranslations;
use View;

class Support extends Pedreiro
{
    protected $models = [
        'Category'          => Category::class,
        'DataRow'           => DataRow::class,
        'DataRelationship'  => DataRelationship::class,
        'DataType'          => DataType::class,
        'Menu'              => Menu::class,
        'MenuItem'          => MenuItem::class,
        'Page'              => Page::class,
        'Permission'        => Permission::class,
        'Post'              => Post::class,
        'Role'              => Role::class,
        'Setting'           => Setting::class,
        'User'              => User::class,
        'Translation'       => Translation::class,
    ];
    public function model($name)
    {
        return app($this->models[Str::studly($name)]);
    }

    public function modelClass($name)
    {
        return $this->models[$name];
    }

    public function useModel($name, $object)
    {
        if (is_string($object)) {
            $object = app($object);
        }

        $class = get_class($object);

        if (isset($this->models[Str::studly($name)]) && !$object instanceof $this->models[Str::studly($name)]) {
            throw new \Exception("[{$class}] must be instance of [{$this->models[Str::studly($name)]}].");
        }

        $this->models[Str::studly($name)] = $class;

        return $this;
    }


    public function addAction($action)
    {
        array_push($this->actions, $action);
    }

    public function replaceAction($actionToReplace, $action)
    {
        $key = array_search($actionToReplace, $this->actions);
        $this->actions[$key] = $action;
    }

    public function actions()
    {
        return $this->actions;
    }

    /**
     * Get a collection of dashboard widgets.
     * Each of our widget groups contain a max of three widgets.
     * After that, we will switch to a new widget group.
     *
     * @return array - Array consisting of \Arrilot\Widget\WidgetGroup objects
     */
    public function dimmers()
    {
        $widgetClasses = config('sitec.rica.dashboard.widgets');
        $dimmerGroups = [];
        $dimmerCount = 0;
        $dimmers = Widget::group("support::dimmers-{$dimmerCount}");

        foreach ($widgetClasses as $widgetClass) {
            $widget = app($widgetClass);

            if ($widget->shouldBeDisplayed()) {

                // Every third dimmer, we consider out WidgetGroup filled.
                // We switch that out with another WidgetGroup.
                if ($dimmerCount % 3 === 0 && $dimmerCount !== 0) {
                    $dimmerGroups[] = $dimmers;
                    $dimmerGroupTag = ceil($dimmerCount / 3);
                    $dimmers = Widget::group("support::dimmers-{$dimmerGroupTag}");
                }

                $dimmers->addWidget($widgetClass);
                $dimmerCount++;
            }
        }

        $dimmerGroups[] = $dimmers;

        return $dimmerGroups;
    }

    public function setting($key, $default = null)
    {
        $globalCache = config('sitec.facilitador.settings.cache', false);

        if ($globalCache && Cache::tags('settings')->has($key)) {
            return Cache::tags('settings')->get($key);
        }

        if ($this->setting_cache === null) {
            if ($globalCache) {
                // A key is requested that is not in the cache
                // this is a good opportunity to update all keys
                // albeit not strictly necessary
                Cache::tags('settings')->flush();
            }

            foreach (self::model('Setting')->orderBy('order')->get() as $setting) {
                $keys = explode('.', $setting->key);
                @$this->setting_cache[$keys[0]][$keys[1]] = $setting->value;

                if ($globalCache) {
                    Cache::tags('settings')->forever($setting->key, $setting->value);
                }
            }
        }

        $parts = explode('.', $key);

        if (count($parts) == 2) {
            return @$this->setting_cache[$parts[0]][$parts[1]] ?: $default;
        } else {
            return @$this->setting_cache[$parts[0]] ?: $default;
        }
    }

    public function image($file, $default = '')
    {
        if (!empty($file)) {
            return str_replace('\\', '/', Storage::disk(config('sitec.facilitador.storage.disk'))->url($file));
        }

        return $default;
    }

    public function routes()
    {
        include __DIR__.'/../routes/facilitador.php';
    }
}
