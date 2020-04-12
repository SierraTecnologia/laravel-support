<?php

namespace Support\Render;

use Exception;
use ErrorException;
use LogicException;
use OutOfBoundsException;
use RuntimeException;
use TypeError;
use Watson\Validating\ValidationException;
use Support\ClassesHelpers\Development\HasErrors;
use Illuminate\Database\Eloquent\Relations\Relation;
use ReflectionClass;
use ReflectionMethod;
use Illuminate\Support\Collection;
use Log;
use Support\Elements\Entities\Relationship;
use Support\Parser\ParseClass;

use Symfony\Component\Debug\Exception\FatalThrowableError;
use Symfony\Component\Debug\Exception\FatalErrorException;

class Relationships
{
    use HasErrors;

    private $model;
    private $relationships;

    public function __construct($model) {
        // $this->model = resolve($model);
        $this->model = $model;

        $this->relationships = null;
    }

    public function __invoke($key = false)
    {
        if (!$this->relationships) {
            $this->all();
        }

        if ($key) {
            return $this->byKey($key);
        }
        
        return $this->relationships;
    }

    public function getInstanceModel()
    {
        // @todo consertar repegar as relacoes
        // if (is_string($this->model)) {
        //     // $this->model = new $this->model;
        //     $this->model = ParseClass::returnInstanceForClass($this->model, true);
        // }
        return $this->model;
    }

    public function all()
    {

        $this->relationships = new Collection;

        // $modelClassInstance

        foreach((new ReflectionClass($this->model))->getMethods(ReflectionMethod::IS_PUBLIC) as $method)
        {
            if ($method->class == $this->model
                && empty($method->getParameters())
                && $method->getName() !== __FUNCTION__
                /* && $method->isFinal() */) // Retirado o lance do method ser final
            {
                try {
                    $return = $method->invoke($this->getInstanceModel());
                    // dd($return);


                    if ($return instanceof Relation)
                    {
                        $ownerKey = null;
                        if ((new ReflectionClass($return))->hasMethod('getOwnerKey'))
                            $ownerKey = $return->getOwnerKey();
                        else
                        {
                            $segments = explode('.', $return->getQualifiedParentKeyName());
                            $ownerKey = $segments[count($segments) - 1];
                        }

                        $tmpReturnReflectionClass = (new ReflectionClass($return));

                        $tmpForeignKey = '';
                        if ($tmpReturnReflectionClass->hasMethod('getForeignKey')) {
                            $tmpForeignKey = $return->getForeignKey();
                        } else if ($tmpReturnReflectionClass->hasMethod('getForeignKeyName')) {
                            $tmpForeignKey = $return->getForeignKeyName();
                        } else if ($tmpReturnReflectionClass->hasMethod('getForeignPivotKeyName')) {
                            $tmpForeignKey = $return->getForeignPivotKeyName();
                        } else {
                            dd('[Support] Discover (Não Deveria Cair Aqui) -> Relação de Tabelas sem Chave Privada: ', $tmpReturnReflectionClass, $return, $this->model,
                            $tmpReturnReflectionClass->hasMethod('getForeignPivotKeyName'),
                            $return->getForeignPivotKeyName()
                        );
                        }

                        $rel = new Relationship([
                            'name' => $method->getName(),
                            'type' => $tmpReturnReflectionClass->getShortName(),
                            'model' => (new ReflectionClass($return->getRelated()))->getName(),
                            'foreignKey' => $tmpForeignKey,
                            'ownerKey' => $ownerKey,
                        ]);

                        $this->relationships[$rel->name] = $rel;
                    }
                } catch(LogicException|ErrorException|RuntimeException $e) {
                    // @todo Tratar aqui
                    $this->setError($e->getMessage());
                    // dd($e);
                } catch (OutOfBoundsException|TypeError $e) {
                    //@todo fazer aqui
                    $this->setError($e->getMessage());
                    // dd($e);
                } catch(ValidationException $e) {
                    $this->setError($e->getMessage());
                    // @todo Tratar aqui
                    // dd($e);
                } catch(FatalThrowableError|FatalErrorException $e) {
                    $this->setError($e->getMessage());
                    //@todo fazer aqui
                    // dd($e);
                } catch(\Exception $e) {
                    $this->setError($e->getMessage());
                    // dd($e);
                } catch(\Throwable $e) {
                    $this->setError($e->getMessage());
                    // dd($this->model, $method, $e);
                    // dd($e);
                    // @todo Tratar aqui
                }
            }
        }

        return $this->relationships;
    }

    public function byKey($key)
    {
        $relationships = new Collection;

        foreach ($this->relationships as $name => $relationship)
            if ($relationship->type == 'BelongsTo'
                && $relationship->foreignKey == $key)
                $relationships[$name] = $relationship;

        return $relationships;
    }
}