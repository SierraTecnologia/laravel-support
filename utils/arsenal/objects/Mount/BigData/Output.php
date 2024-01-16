<?php

namespace SiObjects\Mount\BigData;

use SiObjects\Mount\BigData\Contracts\Output as OutputContract;
use SiObjects\Mount\BigData\Contracts\Item as ItemContract;

/**
 * Class BigDataOutput.
 *
 * @package SiObjects\Mount\BigData
 */
class Output implements OutputContract
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * @inheritdoc
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @inheritdoc
     */
    public function setItems(array $items)
    {
        $this->items = array_map(
            function (ItemContract $item) {
                return $item;
            }, $items
        );

        return $this;
    }
}
