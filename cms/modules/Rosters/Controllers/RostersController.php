<?php

namespace Cms\Modules\Rosters\Controllers;

use Cms;
use CryptoService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cms\Modules\Rosters\Services\RosterService;
use Cms\Modules\Rosters\Requests\RosterCreateRequest;
use Cms\Modules\Rosters\Requests\RosterUpdateRequest;

class RostersController extends Controller
{
    public function __construct(RosterService $rosterService)
    {
        $this->service = $rosterService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rosters = $this->service->paginated();
        return view('rosters::rosters.index')
            ->with('pagination', $rosters->render())
            ->with('rosters', $rosters);
    }

    /**
     * Display a listing of the resource searched.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $rosters = $this->service->search($request->search);
        return view('rosters::rosters.index')
            ->with('term', $request->search)
            ->with('pagination', $rosters->render())
            ->with('rosters', $rosters);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('rosters::rosters.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\RosterCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RosterCreateRequest $request)
    {
        $result = $this->service->create($request->except('_token'));

        if ($result) {
            Cms::notification('Successfully created', 'success');
            return redirect(config('cms.backend-route-prefix', 'cms').'/rosters/'.$result->id.'/edit');
        }

        Cms::notification('Failed to create', 'warning');
        return redirect(config('cms.backend-route-prefix', 'cms').'/rosters');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $roster = $this->service->find($id);
        return view('rosters::rosters.show')->with('roster', $roster);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $roster = $this->service->find($id);
        return view('rosters::rosters.edit')->with('roster', $roster);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\RosterUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RosterUpdateRequest $request, $id)
    {
        $result = $this->service->update($id, $request->except(['_token', '_method']));

        if ($result) {
            Cms::notification('Successfully updated', 'success');
            return back();
        }

        Cms::notification('Failed to update', 'warning');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = $this->service->destroy($id);

        if ($result) {
            Cms::notification('Successfully deleted', 'success');
            return redirect(config('cms.backend-route-prefix', 'cms').'/rosters');
        }

        Cms::notification('Failed to delete', 'warning');
        return redirect(config('cms.backend-route-prefix', 'cms').'/rosters');
    }
}
