<?php

/**
 * Serviço referente a linha no banco de dados
 */

namespace Support\Template\Mounters;

use Route;
use Log;

/**
 * Menu helper to make table and object form mapping easy.
 */
class Menu
{

    protected $key = null;
    protected $code = null;
    protected $slug = null;

    protected $text = null;
    protected $icon = null;
    protected $icon_color = null;
    protected $label_color = null;
    protected $level = null;
    protected $nivel = null;

    protected $url = null;
    protected $route = null;
    /**
     * 
     */
    protected $group = null;
    protected $order = null;




    /**
     * 
     */
    protected $isDivisory = false;

    protected $error = null;

    protected $topnav = false;
    protected $topnav_user = false;
    protected $topnav_right = false;
    protected $data = null;
    protected $active = null;




    /**
     *  'text'    => 'Finder',
     * 'icon'    => 'cog',
     * 'nivel' => \App\Models\Role::$GOOD,
     * 'submenu' => \Finder\Services\MenuService::getAdminMenu(),
     */
    public static function createFromArray($data)
    {
        $instance = new Menu;

        // Caso seja uma divisoria
        if (is_string($data) || (is_array($data) && isset($data['divisory']))) {
            $instance->isDivisory = true;

            if (is_array($data)) {
                $instance->setText($data['text']);
                if (isset($data['order'])) {
                    $instance->setOrder($data['order']);
                }
            } else {
                $data = explode('|', $data);
                $instance->setText($data[0]);
                if (!empty($data[1])) {
                    $instance->setOrder($data[1]);
                }
            }
            
        }else
        // Caso seja um menu
        if (is_array($data)) {
            foreach ($data as $attribute => $valor) {
                $methodName = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $attribute)));
                $array[$attribute] = $instance->{$methodName}($valor);
            }
        }

        return $instance->validateAndReturn();
    }

    public static function isArrayMenu($arrayMenu, $indice = false)
    {
        if (is_string($arrayMenu) && !is_string($indice)) {
            return true;
        }

        return isset($arrayMenu['text']);
    }

    public function attributeIsDefault($attribute)
    {
        return is_null($this->$attribute);
    }

    public function mergeWithMenu(Menu $menu)
    {
        $divisory = $this->isDivisory;

        foreach ($this->getAttributes() as $attribute) {
            if ($this->attributeIsDefault($attribute) && !$menu->attributeIsDefault($attribute)) {
                $methodName = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $attribute)));
                $getMethodName = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $attribute)));
                $this->{$methodName}(
                    $menu->{$getMethodName}()
                );
                // $this->isDivisory = false;
            }
        }
        // if () {

        // }

        return $this;
    }

    public function toArray()
    {
        $array = [];

        if ($this->isDivisory) {
            return $this->getText();
        }

        foreach ($this->getAttributes() as $attribute) {
            if (!$this->attributeIsDefault($attribute)) {
                $methodName = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $attribute)));
                $array[$attribute] = $this->{$methodName}();
            }
        }

        return $array;
    }

    public function getAttributes()
    {
        return [
            'key',
            'code',

            'slug',
            'text',

            'url',
            'route',

            'icon',
            'label_color',
            'icon_color',

            'nivel',
            'level',
            'order',

            'topnav',
            'topnav_user',
            'topnav_right',
            'data',
            'active',
        ];
    }


    /**
     * 
     */
    public function getAddressSlugGroup()
    {
        $group = '';

        if (!$this->attributeIsDefault('group')) {
            $group = $this->getGroup() . '.';
        }

        return $group . $this->getSlug();
    }


    public function getKey()
    {
        return $this->key;
    }
    public function setKey($value)
    {
        $this->key = $value;
    }

    public function getCode()
    {
        if ($this->attributeIsDefault('code')) {
            return $this->getAddressSlugGroup();
        }
        return $this->code;
    }
    public function setCode($value)
    {
        $this->code = $value;
    }

    public function getSlug()
    {
        return $this->slug;
    }
    public function setSlug($value)
    {
        $this->slug = $value;
    }

    public function getText()
    {
        return $this->text;
    }
    public function setText($value)
    {
        $this->setSlug($value);
        $this->text = $value;
    }

    public function getRoute()
    {
        return $this->route;
    }
    public function setRoute($value)
    {
        $this->route = $value;
    }

    public function getUrl()
    {
        return $this->url;
    }
    public function setUrl($value)
    {
        $this->url = $value;
    }

    public function getIcon()
    {
        return $this->icon;
    }
    public function setIcon($value)
    {
        $this->icon = $value;
    }

    public function getLabelColor()
    {
        return $this->label_color;
    }
    public function setLabelColor($value)
    {
        $this->label_color = $value;
    }

    public function getIconColor()
    {
        return $this->icon_color;
    }
    public function setIconColor($value)
    {
        $this->icon_color = $value;
    }

    public function getNivel()
    {
        return $this->nivel;
    }
    public function setNivel($value)
    {
        $this->nivel = $value;
    }

    public function getLevel()
    {
        return $this->level;
    }
    public function setLevel($value)
    {
        $this->level = $value;
    }

    public function getOrder()
    {
        if (is_null($this->order) || empty($this->order)) {
            return 100;
        }

        return $this->order;
    }
    public function setOrder($value)
    {
        $this->order = $value;
    }

    public function getGroup()
    {
        if (is_null($this->group) || empty($this->group)) {
            return 'root';
        }

        return $this->group;
    }
    public function setGroup($value)
    {
        $value = explode('|', $value);
        $this->group = $value[0];
    }
    public function getError()
    {
        return $this->error;
    }
    public function setError($value)
    {
        $this->error = $value;
    }
    public function getTopnav()
    {
        return $this->topnav;
    }
    public function setTopnav($value)
    {
        $this->topnav = $value;
    }
    public function getTopnavUser()
    {
        return $this->topnav_user;
    }
    public function setTopnavUser($value)
    {
        $this->topnav_user = $value;
    }
    public function getTopnavRight()
    {
        return $this->topnav_right;
    }
    public function setTopnavRight($value)
    {
        $this->topnav_right = $value;
    }
    public function getData()
    {
        return $this->data;
    }
    public function setData($value)
    {
        $this->data = $value;
    }
    public function getActive()
    {
        return $this->active;
    }
    public function setActive($value)
    {
        $this->active = $value;
    }

    
    /**
     * Caso nao seja pra exibir, cria log e retorna false.
     * 
     * Se nao retorna a propria instancia
     */
    public function validateAndReturn()
    {
        if (!$this->isToDisplay()) {
            Log::info('Menu desabilitado: ' . $this->getError());
            return false;
        }
        return $this;
    }


    /**
     * Protected
     */
    protected function isToDisplay()
    {
        // Verify Route Exist
        if (!empty($this->getRoute()) && !Route::has($this->getRoute())) {
            $this->setError(
                'Rota ' . $this->getRoute() . ' não existe!'
            );
            return false;
        }

        return true;
    }
}
