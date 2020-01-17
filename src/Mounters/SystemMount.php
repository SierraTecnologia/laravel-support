<?php
/**
 * Serviço referente a linha no banco de dados
 */

namespace Support\Mounters;

/**
 * SystemMount helper to make table and object form mapping easy.
 */
class SystemMount
{

    public function loadMenuForAdminlte($event)
    {
        // $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
            $this->getAllMenus()->getTreeInArray()->map(function ($valor) use ($event) {
                $event->menu->add($valor);
            });
        // });
    }

    protected function getAllMenus($event)
    {
        return MenuRepository::createFromMultiplosArray(collect([
            \Finder\FinderProvider::class,
        ]).map(function($class) {
            return $class::$menuItens;
        }));
    }
}
