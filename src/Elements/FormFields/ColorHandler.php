<?php

namespace Support\Elements\FormFields;

class ColorHandler extends AbstractHandler
{
    protected $codename = 'color';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view(
            'support::components.forms.fields.color', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
            ]
        );
    }
}
