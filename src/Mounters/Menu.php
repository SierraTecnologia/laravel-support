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
    protected $nivel = null;


    protected $group = null;
    
    /**
     *  'text'    => 'Finder',
     * 'icon'    => 'cog',
     * 'nivel' => \App\Models\Role::$GOOD,
     * 'submenu' => \Finder\Services\MenuService::getAdminMenu(),
     */
    public static function createByData($data)
    {
        $instance = new Menu;
        foreach ($data as $atribute => $valor) {
            $methodName = 'set'.ucfirst($atribute);
            $array[$atribute] = $instance->{$methodName}($valor);
        }
    }

    public static function isArrayMenu($arrayMenu)
    {
        if (is_string($arrayMenu)) {
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

        foreach ($this->getAttributes() as $atribute) {
            if (!$this->attributeIsDefault($atribute)) {
                $methodName = 'get'.ucfirst($atribute);
                $array[$atribute] = $this->{$methodName}();
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

        if ($this->attributeIsDefault('group')) {
            $group = $this->getGroup().'.';
        }

        return $this->getSlug();
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
        $this->text = $value;
    }

    public function getIcon() {
        return $this->icon;
    }
    public function setIcon($value) {
        $this->icon = $value;
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
