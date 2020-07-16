<?php

namespace Support\Events;

use Illuminate\Queue\SerializesModels;
use Facilitador\Models\Setting;

class SettingUpdated
{
    use SerializesModels;

    public $setting;

    public function __construct(Setting $setting)
    {
        $this->setting = $setting;
    }
}
