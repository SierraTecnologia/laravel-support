<?php
/**
 * Serviço referente a linha no banco de dados
 */

namespace Support\Mounters;

/**
 * Menu helper to make table and object form mapping easy.
 */
class Menu
{

    protected $slug = null;

    protected $text = null;
    protected $icon = null;
    protected $icon_color = null;
    protected $label_color = null;
    protected $nivel = null;

    protected $url = null;
    protected $route = null;
    /**
     * 
     */
    protected $group = null;
    

    

    /**
     * 
     */
    protected $isDivisory = false;
    

    
    /**
     *  'text'    => 'Finder',
     * 'icon'    => 'cog',
     * 'nivel' => \App\Models\Role::$GOOD,
     * 'submenu' => \Finder\Services\MenuService::getAdminMenu(),
     */
    public static function createFromArray($data)
    {
        $instance = new Menu;

        if (is_string($data)) {
            $instance->isDivisory = true;
            $instance->setText($data);
        }

        if (is_array($data)) {
            foreach ($data as $attribute => $valor) {
                $methodName = 'set'.str_replace(' ', '', ucwords(str_replace('_', ' ', $attribute)));
                $array[$attribute] = $instance->{$methodName}($valor);
            }
        }

        return $instance;
    }

    public static function isArrayMenu($arrayMenu, $indice=false)
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

    public function toArray()
    {
        $array = [];

        if ($this->isDivisory) {
            return $this->getText();
        }

        foreach ($this->getAttributes() as $attribute) {
            if (!$this->attributeIsDefault($attribute)) {
                $methodName = 'get'.str_replace(' ', '', ucwords(str_replace('_', ' ', $attribute)));
                $array[$attribute] = $this->{$methodName}();
            }
        }

        return $array;
    }

    public function getAttributes()
    {
        return [
            'slug',
            'text',
            'icon',
            'nivel',
            'url',
            'route',
        ];
    }


    /**
     * 
     */
    public function getAddressSlugGroup() {
        $group = '';

        if (!$this->attributeIsDefault('group')) {
            $group = $this->getGroup().'.';
        }

        return $group.$this->getSlug();
    }


    public function getSlug() {
        return $this->slug;
    }
    public function setSlug($value) {
        $this->slug = $value;
    }

    public function getText() {
        return $this->text;
    }
    public function setText($value) {
        $this->setSlug($value);
        $this->text = $value;
    }

    public function getRoute() {
        return $this->route;
    }
    public function setRoute($value) {
        $this->route = $value;
    }

    public function getUrl() {
        return $this->url;
    }
    public function setUrl($value) {
        $this->url = $value;
    }

    public function getIcon() {
        return $this->icon;
    }
    public function setIcon($value) {
        $this->icon = $value;
    }

    public function getLabelColor() {
        return $this->label_color;
    }
    public function setLabelColor($value) {
        $this->label_color = $value;
    }

    public function getIconColor() {
        return $this->icon_color;
    }
    public function setIconColor($value) {
        $this->icon_color = $value;
    }

    public function getNivel() {
        return $this->nivel;
    }
    public function setNivel($value) {
        $this->nivel = $value;
    }

    public function getGroup() {
        if ( is_null($this->group) || empty($this->group)) {
            return 'root';
        }

        return $this->group;
    }
    public function setGroup($value) {
        $this->group = $value;
    }
}
