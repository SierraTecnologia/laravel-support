<?php

namespace SiObjects\Mount\SiteMap;

use SiObjects\Mount\SiteMap\Contracts\Builder as BuilderContract;
use SiObjects\Mount\SiteMap\Contracts\Item as ItemContract;

/**
 * Class SiteMapBuilder.
 *
 * @package SiObjects\Mount\SiteMap
 */
class Builder implements BuilderContract
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
