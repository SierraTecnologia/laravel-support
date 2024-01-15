<?php


namespace Muleta\Traits;

use Ramsey\Uuid\Uuid;

trait GeneratesUuid
{
    protected function generateUuid(): string
    {
        return Uuid::uuid4()->toString();
    }
    protected static function staticGenerateUuid(): string
    {
        return Uuid::uuid4()->toString();
    }
}
