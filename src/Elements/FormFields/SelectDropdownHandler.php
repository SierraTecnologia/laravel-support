<?php

namespace Support\Elements\FormFields;

class SelectDropdownHandler extends AbstractHandler
{
    protected $codename = 'select_dropdown';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view(
            'support::components.forms.fields.select_dropdown', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
            ]
        );
    }
}
