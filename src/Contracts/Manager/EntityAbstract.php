<?php
namespace Support\Contracts\Manager;

use Support\Contracts\Support\Arrayable;
use Support\Contracts\Support\ArrayableTrait;
use Support\Traits\Debugger\HasErrors;
use Illuminate\Database\Eloquent\Collection;
use Support\Repositories\EntityRepository;

abstract class EntityAbstract implements Arrayable
{
    use ArrayableTrait;

    public $code;
    
    public function __construct($code = '')
    {
        $this->code = $code;
    }
    
    public static function recover($code = '')
    {
        $systemRepository = resolve(EntityRepository::class);

        return $systemRepository->findByType(
            static::class,
            $code,
        );
    }
    
}