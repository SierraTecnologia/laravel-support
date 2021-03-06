<?php

namespace Support\Repositories;

use Carbon\Carbon;
use Support\Models\Code\SupportEntity;

class EntityRepository
{
    public $model;

    public function __construct(SupportEntity $supportEntity)
    {
        $this->model = $supportEntity;
    }

    /**
     * Find by URL
     *
     * @param string $url
     * @param string $type
     *
     * @return Object|null
     */
    public function findByType($type, $code = '')
    {
        try {
            if (!empty($code)) {
                $type .= '|'.$code;
            }
            $item = $this->model->where('code', $type)->first();

            if ($item) {
                $entity = new $type($code);
                $entity->fromArray($item->data);
                return $entity;
            }
        } catch (\Throwable $th) {
            $model = false;
            \Log::info('Erro ao cadastrar SupportEntity FindType no banco de dados: '.$th->getMessage());
        }

        return null;
    }

    public function save($entity)
    {
        $type = get_class($entity);
        $codeInDatabase = $type;
        $parameter = '';
        if (!empty($entity->code)) {
            if (is_array($entity->code)) {
                if (isset($entity->code['name'])) {
                    $codeInDatabase .= '|'.$entity->code['name'];
                } else {
                    $codeInDatabase .= '|'.serialize($entity->code);
                }
            } else {
                $codeInDatabase .= '|'.$entity->code;
            }
            $parameter = explode('|', $codeInDatabase)[1];
        }

        try {
            $item = $this->model->firstOrNew(['code' => $codeInDatabase]);

            return $item->fill(
                [
                    'parameter'    => $parameter,
                    'type'         => $type,
                    'md5'         => md5(serialize($entity->toArray())),
                    'data'         => $entity->toArray(),
                ]
            )->save();
            //code...
        } catch (\Throwable $th) {
            \Log::info('Erro ao cadastrar SupportEntity Save no banco de dados: '.$th->getMessage());
        }
    }

}
