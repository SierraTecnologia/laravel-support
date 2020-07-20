<?php
/**
 * Recebe parametros e responde com o Entity Correspondente ou Gera um
 */

declare(strict_types=1);

namespace Support\Patterns\Manager;


use Illuminate\Support\Facades\Cache;
use Muleta\Traits\Coder\GetSetTrait;

use Log;

class Manager
{
    /**
     * Atributos
     */
    use GetSetTrait;

    /**
     * Params
     *
     * @var    string
     * @getter true
     * @setter false
     */
    protected $params = false;

    /**
     * Entity Gerado
     *
     * @var    string
     * @getter true
     * @setter false
     */
    protected $entity = false;

    public function __construct($params)
    {
        $this->params = $params;

        $this->run();
    }

    protected function run()
    {
        $this->setEntity(
            Cache::remember(
                'sitec_support_'.static::class.'_'.$this->getMd5ForParams(),
                30,
                function () {
                    return $this->render();
                }
            )
        );
    }

    protected function getMd5ForParams()
    {
        if (is_array($params = $this->getParams())) {
            $params = implode('|', $params);
        }

        return md5($params);
    }
}
