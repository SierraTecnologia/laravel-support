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
            \Informate\InformateProvider::class,
            \Translation\TranslationProvider::class,
            \Locaravel\LocaravelProvider::class,
            \Populate\PopulateProvider::class,
            \Telefonica\TelefonicaProvider::class,
            \Stalker\StalkerProvider::class,
            \Audit\AuditProvider::class,
            \Tracking\TrackingProvider::class,

            \Integrations\IntegrationsProvider::class,
            \Transmissor\TransmissorProvider::class,
            \Market\MarketProvider::class,
            \Bancario\BancarioProvider::class,
            \Operador\OperadorProvider::class,
            \Fabrica\FabricaProvider::class,
            \Finder\FinderProvider::class,
            \Casa\CasaProvider::class,

            \Trainner\TrainnerProvider::class,
            \Gamer\GamerProvider::class,
            
            \Facilitador\FacilitadorProvider::class,
            \Boravel\BoravelProvider::class,
            \Siravel\SiravelProvider::class,
            \PrivateJustice\PrivateJusticeProvider::class,
        ];
    }

    public function loadMenuForAdminlte($event)
    {
        if (!config('siravel.packagesMenu', true)) {
            return ;
        }
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
