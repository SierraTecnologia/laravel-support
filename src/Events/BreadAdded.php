<?php

namespace Support\Events;

use Illuminate\Queue\SerializesModels;
use Support\Models\Application\DataType;

class BreadAdded
{
    use SerializesModels;

    public $dataType;

    public $data;

    public function __construct(DataType $dataType, $data)
    {
        $this->dataType = $dataType;

        $this->data = $data;

        event(new BreadChanged($dataType, $data, 'Added'));
    }
}
