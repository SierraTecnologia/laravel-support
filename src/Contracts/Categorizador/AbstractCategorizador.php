<?php
namespace Support\Contracts\Categorizador;

use Support\Utils\Compare\StringCompare;

abstract class AbstractCategorizador implements InterfaceCategorizador
{
    /**
     * Unico
     */
    public static $name = [
        
    ];
    public static $examples = [
        
    ];

    /**
     * Parametro a Ser observado
     */
    protected $materialDescription;

    /**
     * Todos
     */
    public static $typesByOrder = [
        
    ];


    /**
     * Estaticos
     */
    public static function discoverType($name)
    {
        foreach (static::$typesByOrder as $type) {
            if ($type = (new $type($name))->isValid()) {
                return $type->getName();
            }
        }
        return false;
    }


    /**
     * Construct
     */
    public function __construct($materialDescription)
    {
        $this->materialDescription = $materialDescription;
    }
    public function isValid($name)
    {
        return StringCompare::isSimilar($this->materialDescription, static::$examples);
    }

    public function getName()
    {
        return static::$name;
    }

}
