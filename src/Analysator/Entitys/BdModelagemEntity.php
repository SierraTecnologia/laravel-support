<?php
/**
 * Identifica as Tabelas e as Relaciona
 * 
 * nao usado pra porra nenhuma ainda
 */

namespace Support\Analysator\Entitys;

use Support\Analysator\Information\Group\EloquentGroup;
use Support\Analysator\Information\HistoryType\AbstractHistoryType;
use Support\Analysator\Information\RegisterTypes\AbstractRegisterType;

class BdModelagemEntity
{
    protected $groupType = false;
    protected $historyType = false;
    protected $registerType = false;

    public function __construct()
    {

    }

    public function render($name)
    {
        $this->groupType = EloquentGroup::discoverType($name);
        $this->historyType = AbstractHistoryType::discoverType($name);
        $this->registerType = AbstractRegisterType::discoverType($name);
    }
}
