<?php

namespace SiObjects\Mount\SiteMap\Contracts;

/**
 * Interface Builder.
 *
 * @package SiObjects\Mount\SiteMap\Contracts
 */
interface Builder
{
    /**
     * @return array
     */
    public function getItems(): array;

    /**
     * @param  array $items
     * @return $this
     */
    public function setItems(array $items);
}
