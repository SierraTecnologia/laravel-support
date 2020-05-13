<?php

namespace Support\Template\Form\Validator;

interface ValidatorInterface
{
    public function __invoke($value);
}
