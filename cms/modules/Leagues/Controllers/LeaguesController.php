<?php

namespace Cms\Modules\Leagues\Controllers;

use Cms;
use CryptoService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cms\Modules\Leagues\Services\LeagueService;
use Cms\Modules\Leagues\Requests\LeagueCreateRequest;
use Cms\Modules\Leagues\Requests\LeagueUpdateRequest;

class LeaguesController extends Controller
{
    public function __construct(LeagueService $leagueService)
    {
        $this->service = $leagueService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $leagues = $this->service->paginated();
        return view('leagues::leagues.index')
            ->with('pagination', $leagues->render())
            ->with('leagues', $leagues);
    }

    /**
     * Display a listing of the resource searched.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $leagues = $this->service->search($request->search);
        return view('leagues::leagues.index')
            ->with('term', $request->search)
            ->with('pagination', $leagues->render())
            ->with('leagues', $leagues);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('leagues::leagues.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\LeagueCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LeagueCreateRequest $request)
    {
        $result = $this->service->create($request->except('_token'));

        if ($result) {
            Cms::notification('Successfully created', 'success');
            return redirect(config('cms.backend-route-prefix', 'cms').'/leagues/'.$result->id.'/edit');
        }

        Cms::notification('Failed to create', 'warning');
        return redirect(config('cms.backend-route-prefix', 'cms').'/leagues');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $league = $this->service->find($id);
        return view('leagues::leagues.show')->with('league', $league);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $league = $this->service->find($id);
        return view('leagues::leagues.edit')->with('league', $league);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\LeagueUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LeagueUpdateRequest $request, $id)
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
            return redirect(config('cms.backend-route-prefix', 'cms').'/leagues');
        }

        Cms::notification('Failed to delete', 'warning');
        return redirect(config('cms.backend-route-prefix', 'cms').'/leagues');
    }
}
