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

    public static $linkable = [
        // \Casa\Models\Economic\Gasto::class => [
        //     'extrato', 'transferencia'
        // ]
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
    public static function discoverType(string $name, $returnClass = false): string
    {
        foreach (static::$typesByOrder as $type) {
            $typeInstance = (new $type($name));
            if ($typeInstance->isValid()) {
                if ($returnClass) {
                    return $type;
                }
                return $typeInstance->getName();
            }
        }

        if ($returnClass) {
            return false;
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





    /**
     * Estaticos
     */
    public static function discoverModel(string $name)
    {
        foreach (static::$linkable as $className => $links) {
            if (StringCompare::isSimilar($name, $links)) {
                return $className;
            }
        }
        return false;
    }

}
