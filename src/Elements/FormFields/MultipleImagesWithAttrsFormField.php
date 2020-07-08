<?php

namespace Support\Elements\FormFields;

use Support\Elements\FormFields\AbstractHandler;

class MultipleImagesWithAttrsFormField extends AbstractHandler
{
    protected $codename = 'multiple_images_with_attrs';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view(
            'extended-fields::formfields.multiple_images_with_attrs', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
            ]
        );
    }
}