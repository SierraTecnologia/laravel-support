<?php
/**
 * Identifica as Tabelas e as Relaciona
 */

namespace Muleta\Analysator\Entities;


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
     *
     * @return void
     */
    public function isAction(): void
    {

    }

    /**
     * Algo que aconteceu. Nao pode ser Alteravel, tbm é imutavel
     *
     * @return array
     */
    public function isEvent(): array
    {
        $type = 'int'; // Inteiro somente para exemplo

        // Calcula se a taxa de repetição é alta (Acima de 10%) Caso sim Trata Como Grupo

        $array['manager'] = $this->managerToArray();


        $array['info'] = $this->infoToArray();

        return $array;
    }



    /**
     * Tabelas Imutaveis, Tipo Categorias, Gostos, etc.. o Nome nao Muda (No maximo, corrige ou acrescenta informacao)
     *
     * @return void
     */
    public function registerIsImutavel(): void
    {

        $allTables = [];

        $allRelations = [];

    }
    /**
     * Tabelas que Evoluem (Empresa por exemplo, Pessoas)
     *
     * @return void
     */
    public function registerIsEvolutite(): void
    {

        $allTables = [];

        $allRelations = [];

    }
    /**
     * Tabelas Dinamicas, que os valores variam muito
     *
     * @return void
     */
    public function registerIsDinamic(): void
    {

        $allTables = [];

        $allRelations = [];

    }







    /**
     * Agrupando
     *
     * @return (array|string)[]
     *
     * @psalm-return array{name: 'Calendar', localeNamespace: 'MediaManager\Models', tables: array<empty, empty>}
     */
    public function groupByNamespace(): array
    {
        $namespaces = [];
        $namespaces = [
            'name' => 'Calendar',
            'localeNamespace' => 'MediaManager\Models',
            'tables' => []
        ];

        return $namespaces;
    }



}
