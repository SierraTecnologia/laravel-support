<?php

namespace Support\Elements\FormFields;

class PasswordHandler extends AbstractHandler
{
    protected $codename = 'password';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('facilitador::formfields.password', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
        ]);
    }
}
