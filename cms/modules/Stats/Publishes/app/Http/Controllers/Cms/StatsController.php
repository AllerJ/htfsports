<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cms\Modules\Stats\Services\StatService;

class StatsController extends Controller
{
    public function __construct(StatService $statService)
    {
        $this->service = $statService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $stats = $this->service->paginated();
        return view('cms-frontend::stats.all')->with('stats', $stats);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $stat = $this->service->find($id);
        return view('cms-frontend::stats.show')->with('stat', $stat);
    }
}
