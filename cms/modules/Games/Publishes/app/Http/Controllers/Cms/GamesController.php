<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cms\Modules\Games\Services\GameService;

class GamesController extends Controller
{
    public function __construct(GameService $gameService)
    {
        $this->service = $gameService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $games = $this->service->paginated();
        return view('cms-frontend::games.all')->with('games', $games);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $game = $this->service->find($id);
        return view('cms-frontend::games.show')->with('game', $game);
    }
}
