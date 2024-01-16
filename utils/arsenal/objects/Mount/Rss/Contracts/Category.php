<?php

namespace SiObjects\Mount\Rss\Contracts;

/**
 * Interface Category.
 *
 * @package SiObjects\Mount\Rss\Contracts
 */
interface Category
{
    /**
     * @return string
     */
    public function getValue(): string;

    /**
     * @param  string $value
     * @return $this
     */
    public function setValue(string $value);
}
