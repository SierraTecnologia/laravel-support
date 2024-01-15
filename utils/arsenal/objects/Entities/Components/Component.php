<?php
/**
 * 
 */

namespace SiObjects\Entities\Components;

use Log;

class Component
{

    protected $allComponents = [];

    public $cod = false;

    public $classAfetada = false;

    public $classAExecutar = false;

    public $type = false;

    /**
     * Ações para Investigar ou Explorar algo
     * Spider, Aranha, ou Explorer, Explorador
     */
    public static $spider = 1;

    /**
     * Ações de Rotinas - Periodicos
     * Ex: Backup, Ping nos Servidores
     */
    public static $routine = 2;

    /**
     * Ações que são emitidas como eventos engatilhados após determinadas ações em cima de Repositórios
     */
    public static $hook = 3;

    /**
     * Ações de importar conteudos dos tokens, jira, repositórios, etc.. 
     * Ou enviar informações do Boss para Esses Produtos. Ex: Workflow do jira, ou novos tickets!
     */
    public static $sync = 4;

    /**
     * Verifica latencia e serviços se estão tudo em Order e Funcionando
     */
    public static $life = 5;

    public function __construct($cod, $classAfetada, $classAExecutar, $type)
    {
        $this->cod = $cod;
        $this->classAfetada = $classAfetada;
        $this->classAExecutar = $classAExecutar;
        $this->type = $type;
    }

    public function getClassWithParams($instance)
    {
        $classAExecutar = '\\'.$this->classAExecutar;
        if (!$instance instanceof $this->classAfetada) {
            Log::notice('Não é instancia de '. $this->classAfetada.'!');
            return abort(500, 'Não é instancia de!');
        }
        return new $classAExecutar($instance);
    }

    /**
     * FUncoes para os Controllers Internos
     */
    public static function getModels()
    {
        $components = self::loadComponents();
        $models = [];
        foreach ($components as $component)
        {
            if (!in_array($component->classAfetada, $models)) {
                $models[] = $component->classAfetada;
            }
        }
        return $models;
    }

    

    /**
     * FUncoes para os Controllers Internos
     */
    public static function getOnlyComponentsForModel($model)
    {
        $components = self::loadComponents();
        $onlyModelComponents = [];
        foreach ($components as $component)
        {
            if ($model == $component->classAfetada) {
                $onlyModelComponents[] = $component;
            }
        }
        return $onlyModelComponents;
    }


    /**
     * Funções GErais
     */
    protected static function loadComponents()
    {
        return self::getSyncs(self::getHooks(self::getRoutines(self::getSpiders())));
    }

    public static function getComponentByCode($cod)
    {
        $components = self::loadComponents();
        foreach($components as $component) {
            if($component->cod == $cod) {
                return $component;
            }
        }
        return false;
    }
    
    protected static function getSpiders($components = [])
    {
        /**
         * Scaneia paginas de determinado Website
         */
        $components[] = self::insertComponent(
            'scanDomain',
            \Fabrica\Models\Infra\Domain::class, // Ou Url
            \SiObjects\Components\Worker\Explorer\Spider::class,
            self::$spider
        );

        /**
         * Scaneia paginas de determinado Website
         */
        $components[] = self::insertComponent(
            'whoisDomain',
            \Fabrica\Models\Infra\Domain::class, // Ou Url
            \SiObjects\Components\Worker\Explorer\Whois::class,
            self::$spider
        );

        return $components;
    }
    
    protected static function getRoutines($components = [])
    {
        /**
         * Backup dos 
         */
        $components[] = self::insertComponent(
            'backupDatabase',
            \Fabrica\Models\Infra\DatabaseCollection::class,
            \SiObjects\Components\Worker\Sync\Keys\BackupCollection::class,
            self::$routine
        );

        /**
         * Procura por arquivos de Log dentro das Maquinas
         */
        $components[] = self::insertComponent(
            'searchLog',
            \Fabrica\Models\Infra\Computer::class,
            \SiObjects\Components\Worker\Logging\Logging::class,
            self::$routine
        );

        return $components;
    }

    protected static function getHooks($components = [])
    {

        /**
         * Analisa a qualidade de código nos Projects Atuais
         */
        $components[] = self::insertComponent(
            'analyseComit',
            \Finder\Models\Code\Commit::class,
            \SiObjects\Components\Worker\Analyser\Analyser::class,
            self::$hook
        );

        /**
         * Atualiza as Maquinas de Staging e Produção
         */
        $components[] = self::insertComponent(
            'deployCommit',
            \Finder\Models\Code\Commit::class,
            \SiObjects\Components\Worker\Deploy\Deploy::class,
            self::$hook
        );

        return $components;
    }


    protected static function getSyncs($components = [])
    {

        $components[] = self::insertComponent(
            'importIntegrationToken',
            \Integrations\Models\Token::class,
            \SiObjects\Components\Worker\Sync\Keys\ImportFromToken::class,
            self::$routine
        );

        /**
         * Analisa a qualidade de código nos Projects Atuais
         */
        $components[] = self::insertComponent(
            'syncProject',
            \Finder\Models\Code\Project::class,
            \SiObjects\Components\Worker\Sync\Project::class,
            self::$hook
        );

        return $components;

    }

    protected static function insertComponent($cod, $classAfetada, $classAExecutar, $type)
    {
        $newComponent = new self($cod, $classAfetada, $classAExecutar, $type);
        return $newComponent;
    }

    /**
     * Se byClass nao for false, retorna todas as ações para qualquer tipo de instancia
     */
    public function getComponents($byClass = false)
    {
        if (empty($this->allComponents)) {
            $this->allComponents = self::loadComponents();
        }
        return $this->allComponents;
    }

}
