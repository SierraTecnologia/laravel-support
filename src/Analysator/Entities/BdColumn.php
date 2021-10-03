<?php
/**
 * Identifica as Tabelas e as Relaciona
 */

namespace Support\Analysator\Entities;


class BdColumn
{
    /**
     * Identify
     */
    protected $typesByOrder = [
        Types\BdMoneyEntity::class,
        Types\BdPorcentagemEntity::class,
        Types\BdIntegerEntity::class,
        Types\BdFloatEntity::class,
    ];


    /**
     * Construct
     */
    public function __construct()
    {
        

    }

    /**
     * Tabelas que agem, tipo usuarios, empresas, robos, etc..
     */
    public function isAction()
    {

    }

    /**
     * Algo que aconteceu. Nao pode ser Alteravel, tbm é imutavel
     *
     * @return void
     */
    public function isEvent()
    {
        $type = 'int'; // Inteiro somente para exemplo

        // Calcula se a taxa de repetição é alta (Acima de 10%) Caso sim Trata Como Grupo

        $array['manager'] = $this->managerToArray();


        $array['info'] = $this->infoToArray();

        return $array;
    }



    /**
     * Tabelas Imutaveis, Tipo Categorias, Gostos, etc.. o Nome nao Muda (No maximo, corrige ou acrescenta informacao)
     */ 
    public function registerIsImutavel()
    {

        $allTables = [];

        $allRelations = [];

    }
    /**
     * Tabelas que Evoluem (Empresa por exemplo, Pessoas)
     */ 
    public function registerIsEvolutite()
    {

        $allTables = [];

        $allRelations = [];

    }
    /**
     * Tabelas Dinamicas, que os valores variam muito
     */ 
    public function registerIsDinamic()
    {

        $allTables = [];

        $allRelations = [];

    }







    /**
     * Agrupando
     */ 
    public function groupByNamespace()
    {
        $namespaces = [];
        $namespaces = [
            'name' => 'Calendar',
            'localeNamespace' => 'App\Models',
            'tables' => []
        ];

        return $namespaces;
    }



}
