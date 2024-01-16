<?php

namespace Muleta\Traits;

use SiUtils\Helper\General;

trait ManipuleTokenTrait
{

    public function generateTokenIfNull()
    {
        if (!$this->haveToken()) {
            $this->token = General::generateToken();
        }
    }

    public function haveToken()
    {
        if ($this->token=='') {
            return false;
        }

        if (empty($this->token)) {
            return false;
        }

        if (is_null($this->token)) {
            return false;
        }

        return true;
    }
}