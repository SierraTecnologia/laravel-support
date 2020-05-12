<?php

namespace Support\Components\Errors;

use Support\Models\Code\Error;
use Support\Traits\Coder\GetSetTrait;

abstract class CodeError
{

    /**
     * Atributos
     */
    use GetSetTrait;

    /**
     * Target do erro
     *
     * @var string
     * @getter true
     * @setter true
     */
    protected $target = false;

    const NAME = 'UNDEFINED_TYPE_NAME';

    // todo: make sure this is not overwrting DoctrineType properties

    // Note: length, precision and scale need default values manually

    public function getName()
    {
        return static::NAME;
    }
    public function getDescription()
    {
        return str_replace('{target}', $this->getTarget(), static::DESCRIPTION);
    }

    /**
     * @todo Criar mensagem de erro
     */
    public function getMessage()
    {
        return $this->getName();
    }

    public function whereFind()
    {
        return [
            'class_type' => static::class,
            'target' => $this->getTarget(),
        ];
    }

    public function __construct($target)
    {
        $this->setTarget($target);
        if (!$this->supportModelCodeClass = Error::where($this->whereFind())->first()) {
            $this->supportModelCodeClass = new Error;
            $this->supportModelCodeClass->class_type = static::class;
            $this->supportModelCodeClass->target = $this->getTarget();
            $this->supportModelCodeClass->name = $this->getDescription();
            // $this->supportModelCodeClass->data = $this->toArray();
            $this->supportModelCodeClass->save();
        }
    }

   
    public static function make($target)
    {
        return new static($target);
    }

   
}
