<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cms\Modules\Players\Services\PlayerService;

class PlayersController extends Controller
{
    public function __construct(PlayerService $playerService)
    {
        $this->service = $playerService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $players = $this->service->paginated();
        return view('cms-frontend::players.all')->with('players', $players);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $player = $this->service->find($id);
        return view('cms-frontend::players.show')->with('player', $player);
    }
}
