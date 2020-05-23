<?php

namespace Facilitador\Generators;

use Facilitador\Services\FacilitadorService;

/**
 * Generate the CRUD.
 */
class FacilitadorGenerator
{
    public function __construct(FacilitadorService $service)
    {
        $this->service = $service;
    }

    public function optionsButtons()
    {
        $classes = $this->service->getModelServices();

        $html = '';
        $html .= $this->optionsButtonsAdd($classes);

        return $html;
    }

    public function optionsButtonsAdd($classes)
    {
        $html = '<div class="btn-group">
        <button type="button" class="btn btn-info">Adicionar</button>
        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
          <span class="caret"></span>
          <span class="sr-only">Toggle Dropdown</span>
        </button>';
        $html .= '<ul class="dropdown-menu" role="menu">';

        foreach ($classes as $class) {
            $html .= '<li><a href="'.$class->getUrl('/create').'">'.$class->getName().'</a></li>';
        }
          $html .= '</ul>';
          $html .= '</div>';
        return $html;
    }
}
