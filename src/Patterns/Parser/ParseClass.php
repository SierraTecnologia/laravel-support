<?php

declare(strict_types=1);


namespace Support\Patterns\Parser;

use App;
use Log;
use Support\Models\Code\Classer;
use Muleta\Traits\Debugger\HasErrors;

use Muleta\Contracts\Support\Arrayable;
use Muleta\Contracts\Support\ArrayableTrait;
use Muleta\Traits\Coder\GetSetTrait;
use Muleta\Utils\Extratores\ClasserExtractor;

use Support\Analysator\Information\Group\EloquentGroup;
use Support\Analysator\Information\HistoryType\AbstractHistoryType;
use Support\Analysator\Information\RegisterTypes\AbstractRegisterType;



class ParseClass implements Arrayable
{
    use HasErrors; //, ArrayableTrait;

    /**
     * Atributos
     */
    use GetSetTrait;

    /**
     * Nomenclatura da Class
     * Nome Correspondente
     *
     * @var    string
     * @getter true
     * @setter true
     */
    protected $name;

    /**
     * Nome da Classe
     *
     * @var    string
     * @getter true
     * @setter true
     */
    protected $className;

    /**
     * Tipo da Classe
     *
     * @var    string
     * @getter true
     * @setter true
     */
    protected $type;

    /**
     * Attributes to Array Mapper @todo Nao usado ainda, era bom usar
     */
    public static $mapper = [
        'name',
        'class',
        'filename',
        'parentClass',
        'interfaces',
        'type',

        'groupPackage',
        'groupType',
        'historyType',
        'registerType'
    ];

    public $supportModelCodeClass = false;

    public $reflectionClass = false;
    public $parentClass = false;
    public $fileName = false;
    public $data = false;

    public static $types = [
        'model' => \Illuminate\Database\Eloquent\Model::class,
    ];

    // Tudo em minusculo
    public static $typesIgnoreName = [
        'model' => [
            'model',
            'base',
            'entity',
            'eloquent'
        ],
    ];

    public function __construct($classOrReflectionClass)
    {


        $this->forceExecute(
            function () use ($classOrReflectionClass) {
                $this->className = $classOrReflectionClass;
                $this->type = $this->detectType();
                if (!$this->supportModelCodeClass = Classer::find($this->className)) {
                    $this->supportModelCodeClass = new Classer;
                    $this->supportModelCodeClass->class_name = $this->getClassName();
                    $this->supportModelCodeClass->filename = $this->getFilename();
                    $this->supportModelCodeClass->parent_class = $this->getParentClassName();
                    $this->supportModelCodeClass->type = $this->getType();
                    $this->supportModelCodeClass->data = $this->data = $this->toArray();
                    $this->supportModelCodeClass->save();
                } else {
                    if (is_object($this->supportModelCodeClass->data)) {
                        dd('Debug ParseClass', $this->supportModelCodeClass, get_class($this->supportModelCodeClass->data), $this->supportModelCodeClass->data);
                    }
                    $this->data = $this->supportModelCodeClass->data;
                    $this->fromArray($this->supportModelCodeClass->data);
                }
                // @debug
                // dd($this->reflectionClass->getProperties());
                // dd($this->reflectionClass->getMethods());

                // dd(
                //     $this->reflectionClass->getProperty('fillable'),
                //     $this->reflectionClass->getProperty('primaryKey'),
                //     // $this->reflectionClass->getProperty('cast'),
                //     $this->reflectionClass->getProperty('table')
                // );
                // var_dump($this->reflectionClass->getFileName());
            }
        );
        
    }
    
    public function getReflectionClassForUse()
    {
        if (!$this->reflectionClass) {
            $this->reflectionClass = ClasserExtractor::getReflectionClass($this->className);
        }
        return $this->reflectionClass;
    }

    public function getData($indice = false)
    {
        if (!$this->data) {
            $this->data = $this->toArray();
        }
        if (!$indice) {
            return $this->data;
        }
        return $this->data[$indice];
    }

    public function toArray()
    {
        if ($this->data) {
            return $this->data;
        }

        return [

            'name' => $this->generateName(),
            'class' => $this->getClassName(),
            'filename' => $this->getFilename(),
            'parentClass' => $this->getParentClassName(),
            'interfaces' => $this->getInterfaceNames(),
            'type' => $this->getType(),
            'groupPackage' => ClasserExtractor::getPackageNamespace($this->className),
            'groupType' => EloquentGroup::discoverType($this->className),
            'historyType' => AbstractHistoryType::discoverType($this->className),
            'registerType' => AbstractRegisterType::discoverType($this->className),
        ];

    }

    public function fromArray(Array $array)
    {
        if (isset($array['name'])) {
            $this->setName($array['name']);
        }
        if (isset($array['class'])) {
            $this->setClassName($array['class']);
        }
        if (isset($array['filename'])) {
            $this->setClassFilename($array['filename']);
        }
        if (isset($array['parentClass'])) {
            $this->setParentClassName($array['parentClass']);
        }
        // if (isset($array['interfaces'])) {
        //     $this->setInterfaceNames($array['interfaces']);
        // }
        if (isset($array['type'])) {
            $this->setType($array['type']);
        }
    }

    public function getParentClassName()
    {
        if ($this->parentClass === false) {
            if (!$parentClass = $this->getReflectionClassForUse()->getParentClass()) {
                $this->parentClass = null;
            } else {
                $this->parentClass = $parentClass->getName();
            }
        }
        return $this->parentClass;
    }
    public function setParentClassName($parentClass)
    {
        $this->parentClass = $parentClass;
    }
    public function getFilename()
    {
        if ($this->fileName === false) {
            $this->fileName = $this->getReflectionClassForUse()->getFileName();
        }
        return $this->fileName;
    }
    public function setClassFilename($fileName)
    {
        $this->fileName = $fileName;
    }
    public function getInterfaceNames()
    {
        return $this->getReflectionClassForUse()->getInterfaceNames();
    }


    /**
     * Outras Funcoes
     */
    public function typeIs($type)
    {
        return $this->type == $type;
    }

    protected function detectType()
    {
        // Verify if is Interface
        if ($this->getReflectionClassForUse()->isInterface()) {
            return 'interface';
        }
        // Verify if is Abstract
        if ($this->getReflectionClassForUse()->isAbstract()) {
            return 'abstract';
        }

        foreach (static::$types as $type => $subClassName) {
            // Detected
            if (is_subclass_of($this->className, $subClassName)) {
                if (!isset(static::$typesIgnoreName[$type]) || !in_array(ClasserExtractor::getClassName($this->className), static::$typesIgnoreName[$type])) {
                    // dd(static::$typesIgnoreName, ClasserExtractor::getClassName($this->className), $this->className);
                    return $type;
                }
            }
        }

        return 'other';
    }

    /**
     * Trabalhos Leves
     */
    public function generateName($plural = false)
    {
        $reflection = $this->getReflectionClassForUse();
        $name = $reflection->getShortName();

        if ($plural) {
            return StringModificator::pluralize($name);
        }

        return $name;
    }

    /**
     * Veio da outra classe que eu tinha feito antes
     */
    
    /**
     * @todo tirar daqui
     */
    public static function fastExecute($class, $method, ...$args)
    {
        return (new static($class))->$method(...$args);
    }


}