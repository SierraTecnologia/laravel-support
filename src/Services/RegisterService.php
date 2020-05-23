<?php
/**
 * Serviço referente a linha no banco de dados
 */

namespace Facilitador\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Facilitador\Support\Result\RelationshipResult;
use SierraTecnologia\Crypto\Services\Crypto;

/**
 * RegisterService helper to make table and object form mapping easy.
 */
class RegisterService
{

    protected $identify;
    protected $instance = false;
    protected $repositoryService = false;

    public function __construct(string $identify)
    {
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
        $results = new Collection;
        // @todo
        dd($this->getDiscoverService()->dataRelactionships);
        $this->getDiscoverService()->getRelations()->map(
            function ($value) use ($results, $returnEmptys) {
                $tmpRelationResults = $this->getInstance()->{$value->name}()->get();
            
                if ($returnEmptys || count($tmpRelationResults)>0) {
                    $results[$value->name] = new RelationshipResult($value, $tmpRelationResults);
                }
            }
        );

        return $results;
    }
}
