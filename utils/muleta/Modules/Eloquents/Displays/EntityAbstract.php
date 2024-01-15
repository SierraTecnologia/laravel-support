<?php

namespace Muleta\Modules\Eloquents\Displays;

use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

/**
 * Class EntityAbstract.
 *
 * @package Core\Entities
 */
abstract class EntityAbstract implements Arrayable, JsonSerializable
{

    /**
     * @return static
     */
    public static function init(array $array = []): self
    {
        return new static($array);
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * persist
     *
     * @return void
     */
    public function persist(): void
    {
        $this->model::create($this->toArray());
    }

}
