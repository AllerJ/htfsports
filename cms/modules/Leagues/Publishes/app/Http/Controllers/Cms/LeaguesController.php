<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cms\Modules\Leagues\Services\LeagueService;

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
        return view('cms-frontend::leagues.all')->with('leagues', $leagues);
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
        return view('cms-frontend::leagues.show')->with('league', $league);
    }
}
