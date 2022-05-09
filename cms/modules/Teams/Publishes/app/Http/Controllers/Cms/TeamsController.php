<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cms\Modules\Teams\Services\TeamService;

class TeamsController extends Controller
{
    public function __construct(TeamService $teamService)
    {
        $this->service = $teamService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $teams = $this->service->paginated();
        return view('cms-frontend::teams.all')->with('teams', $teams);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $team = $this->service->find($id);
        return view('cms-frontend::teams.show')->with('team', $team);
    }
}
