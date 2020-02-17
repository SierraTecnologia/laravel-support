<?php

namespace Support\Elements\FormFields;

class DateHandler extends AbstractHandler
{
    protected $codename = 'date';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('facilitador::formfields.date', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
        ]);
    }
}
