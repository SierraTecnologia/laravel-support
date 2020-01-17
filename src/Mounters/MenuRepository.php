<?php
/**
 */

namespace Support\Mounters;

/**
 * MenuRepository helper to make table and object form mapping easy.
 */
class MenuRepository
{

    protected $menus = [];

    public function __construct($menus = [])
    {
        $this->menus = $menus;
    }

    public function getTreeInArray($parent = 'root')
    {
        $newArrayByGroupMenu = [];

        $byGroup = $this->groupBy('group');
        
        foreach($byGroup[$parent] as $menu) {
            $menuArray = $menu->toArray();

            if (!empty($byGroup[$menu->getAddressSlugGroup()])) {
                $menuArray['subMenu'] = $this->getTreeInArray($menu->getAddressSlugGroup())->map(function ($menu) {
                    return $menu->toArray();
                });
            }
        }

        return $this->getInOrder($newArrayByGroupMenu);
    }

    public function getInOrder($arrayMenu)
    {
        // @todo Ordenar
        $arrayMenu;
    }

    public function groupBy($attribute)
    {
        $byGroup = [];
        $getFunction = 'get'.ucfirst($attribute);
        
        foreach($this->menus as $menu) {
            if (!isset($byGroup[$menu->{$getFunction}()])) {
                $byGroup[$menu->{$getFunction}()] = [];
            }
            $byGroup[$menu->{$getFunction}()][] = $menu;
        }

        return [$byGroup, $byCode];
    }

    public static function createFromArray($array)
    {
        $arrayFromMenuEntitys = [];

        foreach ($array as $value) {
            $arrayFromMenuEntitys[] = Menu::createFromArray($value);
        }

        return new self($arrayFromMenuEntitys);
    }

    public static function createFromMultiplosArray($array)
    {
        $mergeArray = [];

        foreach ($array as $value) {
            $mergeArray = array_merge($mergeArray, self::mergeDinamicGroups($value));
        }

        return self::createFromArray($mergeArray);
    }

    protected static function mergeDinamicGroups($array, $groupParent = '')
    {
        $mergeArray = [];

        foreach ($array as $indice=>$values) {
            $group = $groupParent;
            if (is_string($indice)) {
                $group .= '.'.$indice;
            }

            if (self::isArraysFromMenus($values)) {
                if (!empty($group)) {
                    foreach ($values as $indice => $value) {
                        if (!isset($value['group'])) {
                            $values[$indice]['group'] = $group;
                        }
                    }
                }
            } else {
                $values = self::mergeDinamicGroups($values, $group);
            }

            $mergeArray = array_merge($mergeArray, $value);
        }

        return $mergeArray;
    }
    public static function isArraysFromMenus($arrayMenu)
    {
        if (is_string($arrayMenu)) {
            return false;
        }

        if (!is_array($arrayMenu)) {
            return false;
        }

        foreach ($arrayMenu as $indice=>$values) {
            if (!Menu::isArraysFromMenus($arrayMenu)) {
                return false;
            }
        }

        return true;
    }
}
