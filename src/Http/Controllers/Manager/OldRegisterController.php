<?php

namespace Facilitador\Http\Controllers\System\Manager;

use Illuminate\Http\Request;
use Facilitador\Services\FacilitadorService;
use Population\Models\Components\Code\Commit;
use Facilitador\Services\RegisterService;
use Facilitador\Services\RepositoryService;
use Facilitador\Http\Requests\ModelUpdateRequest;

class OldRegisterController extends Controller
{
    protected $registerService;

    public function __construct(FacilitadorService $facilitadorService, RepositoryService $repositoryService, RegisterService $registerService)
    {
        $this->registerService = $registerService->load($repositoryService);
        parent::__construct($facilitadorService, $repositoryService);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $service = $this->registerService;
        $modelRelationsResults = $service->getRelationsResults();
        $register = $this->registerService->getInstance();

        $htmlGenerator = new \Facilitador\Generators\RegisterGenerator($service);

        // dd(
        //     $service,
        //     $modelRelationsResults,
        //     $service->getModelService()->getColumns()
        // );

        return view(
            'facilitador::components.registers.index',
            compact('service', 'modelRelationsResults', 'register', 'htmlGenerator')
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $service = $this->registerService;
        $modelRelationsResults = $service->getRelationsResults();
        $register = $this->registerService->getInstance();

        return view(
            'facilitador::components.registers.edit',
            compact('service', 'modelRelationsResults', 'register')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Facilitador\Http\Requests\ModelUpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(ModelUpdateRequest $request)
    {
        $service = $this->registerService;
        $modelRelationsResults = $service->getRelationsResults();
        $register = $this->registerService->getInstance();

        //     $request->validate([
        //         'commit_name'=>'required',
        //         'commit_price'=> 'required|integer',
        //         'commit_qty' => 'required|integer'
        //     ]);

        //     $service = Commit::findOrFail($id);
        //     $service->commit_name = $request->get('commit_name');
        //     $service->commit_price = $request->get('commit_price');
        //     $service->commit_qty = $request->get('commit_qty');
        //     $service->save();
    
        $id = $this->registerService->getId();
        try {
            $result = $this->service->update($id, $request->except('_token'));

            if ($result) {
                return back()->with('message', 'Successfully updated');
            }

            return back()->with('message', 'Failed to update');
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $service = $this->registerService;
        $modelRelationsResults = $service->getRelationsResults();
        $register = $this->registerService->getInstance();

        $id = $this->registerService->getId();
        try {
            $result = $this->service->destroy(Auth::user(), $id);

            if ($result) {
                return redirect($this->service->getUrl())->with('message', 'Successfully deleted');
            }

            return redirect($this->service->getUrl())->with('message', 'Failed to delete');
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }



}