<?php

namespace SiObjects\Mount\Rss;

use SiObjects\Mount\Rss\Contracts\Builder as BuilderContract;
use SiObjects\Mount\Rss\Contracts\Channel;
use SiObjects\Mount\Rss\Contracts\Item as ItemContract;

/**
 * Class Builder.
 *
 * @package SiObjects\Mount\Rss
 */
class Builder implements BuilderContract
{
    /**
     * @var Channel
     */
    protected $channel;

    /**
     * @var array
     */
    protected $items;

    /**
     * @inheritdoc
     */
    public function getChannel(): Channel
    {
        return $this->channel;
    }

    /**
     * @inheritdoc
     */
    public function setChannel(Channel $channel)
    {
        $this->channel = $channel;

        return $this;
    }

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
