<?php
/**
 * Serviço referente a linha no banco de dados
 */

namespace Support\Template\Mounters;

use Translation;

/**
 * SystemMount helper to make table and object form mapping easy.
 */
class SystemMount
{

    public function getProviders()
    {
        return [
            \Support\SupportServiceProvider::class,
            \Audit\AuditProvider::class,
            \Tracking\TrackingProvider::class,

            \Informate\InformateProvider::class,
            \Populate\PopulateProvider::class,
            \Finder\FinderProvider::class,
            \Casa\CasaProvider::class,

            // \Trainner\TrainnerProvider::class,
            // \Gamer\GamerProvider::class,
            
            \Facilitador\FacilitadorProvider::class,
            \Boravel\BoravelProvider::class,
            \Siravel\SiravelProvider::class,
        ];
    }

    public function loadMenuForAdminlte($event)
    {
        // dd($this->getAllMenus()->getTreeInArray());
        // $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
            collect($this->getAllMenus()->getTreeInArray())->map(
                function ($valor) use ($event) {
                    $event->menu->add($valor);
                }
            );
        // });
    }

    public function loadMenuForArray()
    {
        // dd($this->getAllMenus()->getTreeInArray());
        // $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
        return collect($this->getAllMenus()->getTreeInArray())->map(
            function ($valor) {
                return $valor;
            }
        )->values()->all();
        // });
    }

    protected function getAllMenus()
    {
        return MenuRepository::createFromMultiplosArray(
            collect(
                $this->getProviders()
            )->reject(
                function ($class) {
                    return !class_exists($class) || !is_array($class::$menuItens) || empty($class::$menuItens);
                }
            )->map(
                function ($class) {
                    return $class::$menuItens;
                }
            ) //->push(Translation::menuBuilder())
        );
    }
}
