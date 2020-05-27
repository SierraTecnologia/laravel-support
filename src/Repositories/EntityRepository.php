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
        if (!empty($code)) {
            $type .= '|'.$code;
        }
        $item = $this->model->where('code', $type)->first();

        if ($item) {
            $entity = new $type($code);
            $entity->fromArray($type->data);
            return $entity;
        }

        return null;
    }

    public function save($entity)
    {

        $codeInDatabase = get_class($entity);
        if (!empty($entity->code)) {
            $codeInDatabase .= '|'.$entity->code;
        }

        $item = $this->model->firstOrNew(['code' => $codeInDatabase]);

        return $item->fill(
            [
                'data'         => $entity->toArray(),
                'parameter'         => $entity->toArray(),
                'type'         => $entity->toArray(),
                'md5'         => md5(serialize($entity)),
            ]
        )->save();
    }

}
