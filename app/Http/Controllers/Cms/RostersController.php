<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cms\Modules\Rosters\Services\RosterService;

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
        return view('cms-frontend::rosters.all')->with('rosters', $rosters);
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
        return view('cms-frontend::rosters.show')->with('roster', $roster);
    }
}
