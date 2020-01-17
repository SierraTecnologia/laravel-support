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
        // dd($this->getAllMenus()->getTreeInArray());
        // $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
            collect($this->getAllMenus()->getTreeInArray())->map(function ($valor) use ($event) {
                $event->menu->add($valor);
            });
        // });
    }

    protected function getAllMenus()
    {
        return MenuRepository::createFromMultiplosArray(
            collect(
                [
                    \Audit\AuditProvider::class,
                    \Tracking\TrackingProvider::class,

                    \Finder\FinderProvider::class,

                    \Casa\CasaProvider::class,
                    \Trainner\TrainnerProvider::class,

                    \Gamer\GamerProvider::class,
                    
                    \Facilitador\FacilitadorProvider::class,
                    \Siravel\SiravelProvider::class,
                ]
            )->map(
                function($class) {
                    return $class::$menuItens;
                }
            )
        );
    }
}
