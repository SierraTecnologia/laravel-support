<?php

namespace Facilitador\Generators;

use Facilitador\Services\RegisterService;

/**
 * Generate the CRUD.
 */
class RegisterGenerator
{
    public function __construct(RegisterService $service)
    {
        $this->service = $service;
    }

    public function optionsButtons()
    {
        $classes = $this->service->getModelService()->getRelationsByGroup();

        $html = '';
        if (isset($classes['MorphMany'])) {
            $html .= $this->optionsButtonsMorphMany($classes['MorphMany']);
        }
        if (isset($classes['MorphToMany'])) {
            $html .= $this->optionsButtonsMorphToMany($classes['MorphToMany']);
        }

        return $html;
    }

    public function optionsButtonsMorphMany($classes)
    {
        $html = '<div class="btn-group">
        <button type="button" class="btn btn-info">Action</button>
        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
          <span class="caret"></span>
          <span class="sr-only">Toggle Dropdown</span>
        </button>';
        $html .= '<ul class="dropdown-menu" role="menu">';

        foreach ($classes as $class) {
            $html .= '<li><a href="#">'.$class->name.'</a></li>';
        }
          $html .= '</ul>';
          $html .= '</div>';
        return $html;
    }

    public function optionsButtonsMorphToMany($classes)
    {
        $html = '<div class="btn-group">
        <button type="button" class="btn btn-info">Action</button>
        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
          <span class="caret"></span>
          <span class="sr-only">Toggle Dropdown</span>
        </button>';
        $html .= '<ul class="dropdown-menu" role="menu">';

        foreach ($classes as $class) {
            $html .= '<li><a href="#">'.$class->name.'</a></li>';
        }
          $html .= '</ul>';
          $html .= '</div>';
        return $html;
    }
}
