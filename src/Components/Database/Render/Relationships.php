<?php

namespace Support\Components\Database\Render;

use Support\Traits\Debugger\HasErrors;
use Illuminate\Database\Eloquent\Relations\Relation;
use ReflectionClass;
use ReflectionMethod;
use Illuminate\Support\Collection;
use Log;
use Support\Elements\Entities\Relationship;
use Support\Components\Coders\Parser\ParseClass;

use Symfony\Component\Debug\Exception\FatalThrowableError;
use Symfony\Component\Debug\Exception\FatalErrorException;
use Exception;
use ErrorException;
use LogicException;
use OutOfBoundsException;
use RuntimeException;
use TypeError;
use Throwable;
use Watson\Validating\ValidationException;

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
                $reference = [
                    'type' => 'Relationship Render'
                ];
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
                            'origin_table_class' => $this->model,
                            'origin_foreignKey' => $tmpForeignKey,

                            'related_table_class' => (new ReflectionClass($return->getRelated()))->getName(),

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
                            $dataRelationship['origin_table_name'] = $return->getTable();
                        }
                        if ($tmpReturnReflectionClass->hasMethod('getRelatedKeyName')) {
                            $dataRelationship['related_foreignKey'] = $return->getRelatedKeyName();
                        }
                        if ($tmpReturnReflectionClass->hasMethod('getRelationName')) {
                            $dataRelationship['related_table_name'] = $return->getRelationName();
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

                        if (!isset($dataRelationship['origin_foreignKey']) || empty($dataRelationship['origin_foreignKey'])) {
                            $dataRelationship['origin_foreignKey'] = $tmpForeignKey;
                        }
                        if (!isset($dataRelationship['related_foreignKey']) || empty($dataRelationship['related_foreignKey'])) {
                            $dataRelationship['related_foreignKey'] = $ownerKey;
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

                        $this->relationships[$dataRelationship['name']] = $dataRelationship;
                    }
                } catch(LogicException|ErrorException|RuntimeException|OutOfBoundsException|TypeError|ValidationException|FatalThrowableError|FatalErrorException|Exception|Throwable  $e) {
                    $this->setErrors($e);
                } 
                // Verificar se tem algo importante e depois deletar e tratar no hasError @todo
                // catch(LogicException $e) {
                //     $this->setErrors($e);
                // } catch(ErrorException|RuntimeException $e) {
                //     $this->setErrors($e);
                // } catch (OutOfBoundsException|TypeError $e) {
                //     $this->setErrors($e);
                // } catch(ValidationException $e) {
                //     $this->setErrors($e);
                // } catch(FatalThrowableError|FatalErrorException $e) {
                //     $this->setErrors($e);
                // } catch(\Exception $e) {
                //     $this->setErrors($e);
                // } catch(\Throwable $e) {
                //     $this->setErrors($e);
                // }
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