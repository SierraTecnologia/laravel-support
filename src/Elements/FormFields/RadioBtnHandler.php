<?php

namespace Support\Elements\FormFields;

class RadioBtnHandler extends AbstractHandler
{
    protected $name = 'Radio Button';
    protected $codename = 'radio_btn';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view(
            'support::shared.forms.fields.radio_btn', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
            ]
        );
    }
}
