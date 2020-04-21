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
                    $return = $method->invoke(new $this->model);


                    if ($return instanceof Relation)
                    {
                        // dd($return);
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

                        $dataRelationship = [
                            'origin_table_name' => $this->model,
                            'origin_foreignKey' => $tmpForeignKey,

                            'related_table_name' => (new ReflectionClass($return->getRelated()))->getName(),

                            // Others Values
                            'is_inverse' => false,
                            'pivot' => false,

                            // Old
                            'name' => $method->getName(),
                            'type' => $tmpReturnReflectionClass->getShortName(),
                            'model' => (new ReflectionClass($return->getRelated()))->getName(),
                            'ownerKey' => $ownerKey,
                            'foreignKey' => $tmpForeignKey,
                        ];

                        if ($tmpReturnReflectionClass->hasMethod('getTable')) {
                            $dataRelationship['origin_table_class'] = $return->getTable();
                        }
                        if ($tmpReturnReflectionClass->hasMethod('getRelatedKeyName')) {
                            $dataRelationship['related_foreignKey'] = $return->getRelatedKeyName();
                        }
                        if ($tmpReturnReflectionClass->hasMethod('getRelationName')) {
                            $dataRelationship['related_table_class'] = $return->getRelationName();
                        }

                        if ($tmpReturnReflectionClass->hasMethod('getMorphType')) {
                            $dataRelationship['morph_type'] = $return->getMorphType();
                        }

                        if ($tmpReturnReflectionClass->hasMethod('getMorphType')) {
                            $dataRelationship['morph_type'] = $return->getMorphType();
                        }

                        /**
                         * @todo Fazer pivo
                         */
                        if ($tmpReturnReflectionClass->hasMethod('getPivotClass')) {
                            $dataRelationship['pivot'] = true;
                            $dataRelationship['pivotClass'] = $return->getPivotClass();
                        }
                        if ($tmpReturnReflectionClass->hasMethod('getPivotColumns')) {
                            $dataRelationship['pivot'] = true;
                            $dataRelationship['pivotColumns'] = $return->getPivotColumns();
                            dd(
                                $dataRelationship,
                                $return->getPivotAccessor(),
                                $return->getPivotColumns(),
                                $return->getPivotClass()
                                
                            );
                        }



                        // if (!in_array($tmpReturnReflectionClass->getShortName(), [
                        //     'HasMany', 'BelongsTo',
                        //     // 'MorphTo',
                        //     ]))
                        // dd(
                        //     $dataRelationship,
                            
                        //     // $return->getParentKeyName(),
                        //     // $return->getQualifiedParentKeyName()
                        //     // $return->getTable(),
                        //     // $return->getRelationName(),
                        //     $return->getPivotAccessor(),
                        //     $return->getPivotColumns(),
                        //     $return->getPivotClass()
                            
                        // );

                        $rel = new Relationship($dataRelationship);

                        $this->relationships[$rel->name] = $rel;
                    }
                } catch(LogicException $e) {
                    $this->setError($e);
                } catch(ErrorException|RuntimeException $e) {
                    $this->setError($e);
                } catch (OutOfBoundsException|TypeError $e) {
                    $this->setError($e);
                } catch(ValidationException $e) {
                    $this->setError($e);
                } catch(FatalThrowableError|FatalErrorException $e) {
                    $this->setError($e);
                } catch(\Exception $e) {
                    $this->setError($e);
                } catch(\Throwable $e) {
                    $this->setError($e);
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