<?php
/**
 * Serviço referente a linha no banco de dados
 */

namespace Support\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Facilitador\Support\Result\RelationshipResult;
use SierraTecnologia\Crypto\Services\Crypto;
use Support\Models\Application\DataType;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Support\Contracts\Manager\RelationshipableTrait;

/**
 * RegisterService helper to make table and object form mapping easy.
 */
class RegisterService
{
    use RelationshipableTrait;

    protected $identify;
    protected $instance = false;
    protected $repositoryService = false;

    public function __construct(string $identify)
    {
        // if (empty($identify)) {
        //     throw new \Exception('Nao era aqui');
        //     dd(
        //         'Nao era aqui',
        //         $identify
        //     );
        // }
        $this->identify = $identify;
    }

    public function load(RepositoryService $repository)
    {
        if ($this->repositoryService) {
            return false;
        }

        $this->repositoryService = $repository;
        return $this;
    }

    public function getInstance()
    {
        if (!$this->instance) {
            $modelClass = $this->getModelService()->getModelClass();
            // dd($modelClass, $this->identify,  $modelClass::find($this->identify));
            $this->instance = $modelClass::findOrFail($this->identify);
        }
        return $this->instance;
    }

    public function getModelService()
    {
        return $this->repositoryService->getModelService();
    }

    public function getModelDataType()
    {
        return $this->getModelService()->getModelDataType();
    }

    public function getPrimaryKey()
    {
        return $this->getModelService()->getPrimaryKey();
    }

    public function getDiscoverService()
    {
        return $this->getModelService()->getDiscoverService();
    }

    /**
     * Retorna identificador do registro
     *
     * @return void
     */
    public function getId()
    {
        $register = $this->getInstance();
        return $register->{$this->getPrimaryKey()};
    }

    /**
     * Identificador criptografado para serem usadas nas urls
     *
     * @return void
     */
    public function getCryptName()
    {
        return Crypto::shareableEncrypt($this->getId());
    }

    // /**
    //  * Set the form maker user.
    //  *
    //  * @param string $user
    //  */
    // public function viewEdit($user)
    // {
    //     $this->user = $user;

    //     return $this;
    // }


    // /**
    //  * Set the form maker.
    //  *
    //  */
    // public function viewShow()
    // {
    //     $results = $this->getRelationsResults();
    //     return $results;
    // }

    /**
     * Trabalhos Pesados
     */

    public function getRelationsResults($returnEmptys = false)
    {
        $dataType = $this->getModelDataType();
        
        $relationsFromModel = new Collection(
            // $dataType->allDataRelactionships()->get()
            $dataType->dataRelactionships()->get()
        );

        // $results = new Collection(
        //     $this->getDiscoverService()->getRelations()
        // );

        // // @todo nao funfando fazer aqui
        // dd(
        //     $this->getDiscoverService()->getRelations(),
        //     $this->getDiscoverService()->dataRelactionships,
        //     'RegisterService Relations'
        // );

        return $relationsFromModel->mapWithKeys(
            function ($value) use ($returnEmptys) {
                $tempName = $value->name;
                $tmpRelationResults = $this->getInstance()->{$tempName}()->get();
            
                if ($returnEmptys || count($tmpRelationResults)>0) {
                    return [
                        $tempName => new RelationshipResult($this->getModelDataType(), $value, $tmpRelationResults)
                    ];
                }

                return [
                    $tempName => false
                ];
            }
        )->reject(
            function ($result) use ($returnEmptys) {
                if (!$result && !$returnEmptys) {
                    return true;
                }
                return false;
            }
        );;
    }

    public function registerShowIndex($request)
    {
        $dataType = $this->getModelDataType();
        $id = $this->getId();
        $isSoftDeleted = false;

        if (strlen($dataType->model_name) != 0) {
            $model = app($dataType->model_name);

            // Use withTrashed() if model uses SoftDeletes and if toggle is selected
            if ($model && in_array(SoftDeletes::class, class_uses($model))) {
                $model = $model->withTrashed();
            }
            if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
                $model = $model->{$dataType->scope}();
            }
            $dataTypeContent = call_user_func([$model, 'findOrFail'], $id);
            if ($dataTypeContent->deleted_at) {
                $isSoftDeleted = true;
            }
        } else {
            // If Model doest exist, get data from table name
            $dataTypeContent = DB::table($dataType->name)->where($this->getPrimaryKey(), $id)->first();
        }

        // Replace relationships' keys for labels and create READ links if a slug is provided.
        $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType, true);

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($dataType, 'read');

        // Check permission
        // $this->authorize('read', $dataTypeContent);

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($dataTypeContent);


        return [
            $dataType,
            $dataTypeContent,
            $modelRelationsResults = $this->getRelationsResults(),
            $isModelTranslatable,
            $isSoftDeleted
        ];
    }
}
