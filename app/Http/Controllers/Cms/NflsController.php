<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cms\Modules\Nfls\Services\NflService;

class NflsController extends Controller
{
    public function __construct(NflService $nflService)
    {
        $this->service = $nflService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $nfls = $this->service->paginated();
        return view('cms-frontend::nfls.all')->with('nfls', $nfls);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $nfl = $this->service->find($id);
        return view('cms-frontend::nfls.show')->with('nfl', $nfl);
    }
}
