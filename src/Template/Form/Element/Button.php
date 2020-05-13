<?php

namespace Support\Template\Form\Element;

use Support\Template\Form\Input;
use PHPCensor\View;

class Button extends Input
{
    /**
     * @return bool
     */
    public function validate()
    {
        return true;
    }

    /**
     * @param View $view
     */
    protected function onPreRender(View &$view)
    {
        parent::onPreRender($view);

        $view->type = 'button';
    }
}
