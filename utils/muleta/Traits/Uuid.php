<?php


namespace Muleta\Traits;

use Muleta\Traits\GeneratesUuid;
trait Uuid
{
    use UsesStringId, GeneratesUuid;

    public static function bootUuid()
    {
        static::creating(function ($model) {
            $model->id = static::staticGenerateUuid();
        });
    }
}