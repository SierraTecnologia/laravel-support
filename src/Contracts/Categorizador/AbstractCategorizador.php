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
    public $examples = [
        
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
    public static function discoverType(string $name): string
    {
        foreach (static::$typesByOrder as $type) {
            // dd(
            //     new $type($name)
            // );
            if ($type = (new $type($name))->isValid()) {
                return $type->getName();
            }
        }
        return 'Outro';
    }


    /**
     * Construct
     */
    public function __construct($materialDescription)
    {
        $this->materialDescription = $materialDescription;
    }
    public function isValid(): bool
    {
        $material = explode('\\', $this->materialDescription);
        return StringCompare::isSimilar($material, $this->examples);
    }

    public function getName(): string
    {
        return static::$name;
    }

}
