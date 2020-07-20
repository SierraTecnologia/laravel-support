<?php

/**
 */

namespace Support\Template\Mounters;

use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Collection;

/**
 * MenuRepository helper to make table and object form mapping easy.
 */
class MenuRepository
{

    protected $menus = [];


    public function __construct($menus = [])
    {
        $mergeByCode = [];
        foreach ($menus as $menu) {
            if ($menu) {
                if (!isset($mergeByCode[$menu->getCode()])) {
                    $mergeByCode[$menu->getCode()] = $menu;
                } else {
                    $mergeByCode[$menu->getCode()]->mergeWithMenu($menu);
                }
            }
        }

        $this->menus = $this->getInOrder(
            array_values($mergeByCode)
        
        );
        // dd(

        //     $this->menus
        // // return $this->getInOrder($menuArrayList);
        // );
    }

    public function getTreeInArray($parent = 'root')
    {
        $menuArrayList = [];

        $byGroup = $this->groupBy('group');

        if (isset($byGroup[$parent])) {
            foreach ($byGroup[$parent] as $menu) {
                $menuArray = $menu->toArray();
                if (!empty($byGroup[$menu->getAddressSlugGroup()])) {
                    if (is_string($menuArray)) {
                        $menuArrayList[] = $menuArray;
                        $menuArray = $this->getTreeInArray($menu->getAddressSlugGroup());
                    } else {
                        $menuArray['submenu'] = $this->getTreeInArray($menu->getAddressSlugGroup());
                    }
                }
                if (Menu::isArrayMenu($menuArray)) {
                    $menuArrayList[] = $menuArray;
                } else {
                    $menuArrayList = array_merge($menuArrayList, $menuArray);
                }
            }
        }

        return $this->getInOrder($menuArrayList);
    }

    public function getInOrder($arrayMenu)
    {
        if (is_object($arrayMenu[0])) {
            usort(
                $arrayMenu,
                function($a, $b) {
                    return $a->getOrder() > $b->getOrder();
                }
            );
            return $arrayMenu;
        }


        $columns = array_column($arrayMenu, 'order');
        if (count($columns)==count($arrayMenu)) {
            array_multisort($columns, SORT_ASC, $arrayMenu);
        }
        return $arrayMenu;
    }

    public function groupBy($attribute)
    {
        $byGroup = [];
        $getFunction = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $attribute)));

        foreach ($this->menus as $menu) {
            if (!isset($byGroup[$menu->{$getFunction}()])) {
                $byGroup[$menu->{$getFunction}()] = [];
            }
            $byGroup[$menu->{$getFunction}()][] = $menu;
        }

        // dd($byGroup, $this->menus);
        return $byGroup;
    }


    public static function createFromArray(array $array): MenuRepository
    {
        $arrayFromMenuEntitys = [];
        foreach ($array as $value) {
            if ($createMenuArray = Menu::createFromArray($value)) {
                $arrayFromMenuEntitys[] = $createMenuArray;
            }
        }

        return new self($arrayFromMenuEntitys);
    }

    public static function createFromMultiplosArray(Collection $array): MenuRepository
    {
        $mergeArray = [];

        if (!self::isArraysFromMenus($array) && !empty($array)) {
            foreach ($array as $value) {
                $mergeArray = array_merge($mergeArray, self::mergeDinamicGroups($value));
            }
        }

        return self::createFromArray($mergeArray);
    }

    protected static function mergeDinamicGroups($array, $groupParent = '')
    {
        $mergeArray = [];

        if (self::isArraysFromMenus($array)) {
            return $array;
        }
        
        if (!is_array($array)) {
            throw new Exception('Deveria ser um array aqui no mergeDinamicGroups do MenuRepository');
        };


        foreach ($array as $indice => $values) {
            $group = $groupParent;
            if (is_string($indice)) {
                if (!empty($group)) {
                    $tempArrayToMerge = [
                        'text' => explode('|', $indice)[0],
                        'group' => $group
                    ];
                    if (isset(explode('|', $indice)[1])) {
                        $tempArrayToMerge['order'] = explode('|', $indice)[1];
                    }
                    $mergeArray = array_merge(
                        $mergeArray, [
                            $tempArrayToMerge
                        ]
                    );
                    $group .= '.';
                } else {
                    $mergeArray = array_merge($mergeArray, [$indice]);
                }

                $group .= Str::slug(explode('|', $indice)[0], '-');
            }
            if (Menu::isArrayMenu($values, $indice)) {
                if (!empty($group)) {
                    if (!isset($values['group'])) {
                        $values['group'] = $group;
                    } else {
                        $values['group'] = $group . '.' . $values[$indice]['group'];
                    }
                }
                $values = [$values];
            } else if (self::isArraysFromMenus($values)) {
                if (!empty($group)) {
                    foreach ($values as $indice => $value) {
                        if (!isset($value['group'])) {
                            $values[$indice]['group'] = $group;
                        } else {
                            $values[$indice]['group'] = $group . '.' . $values[$indice]['group'];
                        }
                    }
                }
            } else {
                $values = self::mergeDinamicGroups($values, $group);
            }

            $mergeArray = array_merge($mergeArray, $values);
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

        foreach ($arrayMenu as $indice => $values) {
            if (!Menu::isArrayMenu($values, $indice)) {
                return false;
            }
        }

        return true;
    }

    
}
