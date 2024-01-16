<?php
/**
 * Identifica as Tabelas e as Relaciona
 * 
 * nao usado pra porra nenhuma ainda
 */

namespace Muleta\Analysator\Entities;

use Muleta\Analysator\Information\Group\EloquentGroup;
use Muleta\Analysator\Information\HistoryType\AbstractHistoryType;
use Muleta\Analysator\Information\RegisterTypes\AbstractRegisterType;

class BdModelagemEntity
{
    protected $groupType = false;
    protected $historyType = false;
    protected $registerType = false;

    public function __construct()
    {

    }

    public function render($name): void
    {
        $this->groupType = EloquentGroup::discoverType($name);
        $this->historyType = AbstractHistoryType::discoverType($name);
        $this->registerType = AbstractRegisterType::discoverType($name);
    }
}
