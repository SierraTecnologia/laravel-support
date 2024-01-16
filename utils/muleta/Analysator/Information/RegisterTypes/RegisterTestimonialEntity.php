<?php
/**
 * Informação Imutavel (Categorias, gostos, etc..)
 */

namespace Muleta\Analysator\Information\RegisterTypes;


class RegisterTestimonialEntity extends AbstractRegisterType
{
    public static $name = 'Testimonial';
    public static $order = 400;
    public $examples = [
        'info', 'comment', 'testemunho'
    ];




}
