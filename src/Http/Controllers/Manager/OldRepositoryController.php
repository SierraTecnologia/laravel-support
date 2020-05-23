<?php

namespace Facilitador\Http\Controllers\System\Manager;

use Illuminate\Http\Request;
use Facilitador\Services\FacilitadorService;
use Facilitador\Services\RepositoryService;
use Facilitador\Http\Requests\ModelCreateRequest;
use Facilitador\Http\Requests\ModelSearchRequest;

class OldRepositoryController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $service = $this->repositoryService;
        $registros = $service->getTableData();
        //     $teams = $this->repositoryService->paginated($request->user()->id);

        

        return view(
            'facilitador::components.repositories.index',
            compact('service', 'registros')
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTableJson()
    {
        return $this->repositoryService->getTableJson();
    }


    /**
     * Display a listing of the resource searched.
     *
     * @param  \Facilitador\Http\Requests\ModelSearchRequest $request
     * @return \Illuminate\Http\Response
     */
    public function search(ModelSearchRequest $request)
    {
        $registros = $this->repositoryService->search($request->user()->id, $request->search);

        return view(
            'facilitador::components.repositories.index',
            compact('registros')
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $service = $this->repositoryService;

        return view(
            'facilitador::components.repositories.create',
            compact('service')
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Facilitador\Http\Requests\ModelCreateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ModelCreateRequest $request)
    {
        try {
            $result = $this->repositoryService->create(Auth::id(), $request->except('_token'));

            if ($result) {
                return redirect($this->service->getUrl('edit'))->with('message', 'Successfully created');
            }

            return redirect($this->service->getUrl())->with('message', 'Failed to create');
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    /**
     * Display the specified team.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function showByName($name)
    {
        $team = $this->repositoryService->findByName($name);

        if (Gate::allows('team-member', [$team, Auth::user()])) {
            return view('team.show')->with('team', $team);
        }

        return back();
    }

    // /**
    //  * Invite a team member
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function inviteMember(UserInviteRequest $request, $id)
    // {
    //     try {
    //         $result = $this->repositoryService->invite(Auth::user(), $id, $request->email);

    //         if ($result) {
    //             return back()->with('message', 'Successfully invited member');
    //         }

    //         return back()->with('message', 'Failed to invite member - they may already be a member');
    //     } catch (Exception $e) {
    //         return back()->withErrors($e->getMessage());
    //     }
    // }

    // /**
    //  * Remove a team member
    //  *
    //  * @param  int  $userId
    //  * @return \Illuminate\Http\Response
    //  */
    // public function removeMember($id, $userId)
    // {
    //     try {
    //         $result = $this->repositoryService->remove(Auth::user(), $id, $userId);

    //         if ($result) {
    //             return back()->with('message', 'Successfully removed member');
    //         }

    //         return back()->with('message', 'Failed to remove member');
    //     } catch (Exception $e) {
    //         return back()->withErrors($e->getMessage());
    //     }
    // }
}