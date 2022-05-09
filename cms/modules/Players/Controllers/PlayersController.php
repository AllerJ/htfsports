<?php

namespace Cms\Modules\Players\Controllers;

use Cms;
use CryptoService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cms\Modules\Players\Services\PlayerService;
use Cms\Modules\Players\Models\Player;
use Cms\Modules\Players\Requests\PlayerCreateRequest;
use Cms\Modules\Players\Requests\PlayerUpdateRequest;

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

        


        $players = new Player;
        //$all_players = $players->where('game_id', '=', '210')->get();
        
        $all_players = $players->where('id', '=', '26578')->get();		

        foreach($all_players as $player) {
             
             $ch = curl_init();        
             //curl_setopt($ch, CURLOPT_URL, "https://api.mysportsfeeds.com/".config('app.msf_ver')."/pull/nfl/players.json?season=current&position=qb,wr,rb,te&rosterstatus=assigned-to-roster");
             
             curl_setopt($ch, CURLOPT_URL, 'https://api.mysportsfeeds.com/v2.1/pull/nfl/'.config('app.msf_year')."-".config('app.msf_season').'/player_stats_totals.json?player='.$player->player_id.'&date=today');

             curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
             curl_setopt($ch, CURLOPT_ENCODING, "gzip");
             curl_setopt($ch, CURLOPT_HTTPHEADER, [
                 "Authorization: Basic " . config('app.msf_api')
             ]);
             $resp = curl_exec($ch);

             print_r($resp);
             
             if (!$resp) {
                 die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
             } else {
                 $this_player = json_decode($resp, true);
             }

             curl_close($ch);
             
             print_r($this_player);
             echo "
             
             
             --------
             
             
             ";
             
             
             
             
/*
				$game = $schedule['schedule'];


                $game_at = str_replace("T"," ", $game['startTime']);

				$game_at = explode('.', $game_at);


                $game_at = Carbon::parse($game_at[0]);

				$game_at = $game_at->format('Y-m-d H:i:s');

				

                $payload = array();                
                $payload['schedule_id'] = $game['id'];
                $payload['schedule_at'] = $game_at;
                $payload['venue'] = $game['venue']['name'];
                $payload['home_id'] = $game['homeTeam']['id'];
                $payload['visitor_id'] = $game['awayTeam']['id'];
                $payload['league_id'] = '1';
                print_r($payload);
   
 
*/
				

 //               $this->service->create($payload);
                
            
        }


/*
        $players = $this->service->paginated();
        return view('players::players.index')
            ->with('pagination', $players->render())
            ->with('players', $players);
*/
            

    }

    /**
     * Display a listing of the resource searched.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $players = $this->service->search($request->search);
        return view('players::players.index')
            ->with('term', $request->search)
            ->with('pagination', $players->render())
            ->with('players', $players);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('players::players.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\PlayerCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PlayerCreateRequest $request)
    {
        $result = $this->service->create($request->except('_token'));

        if ($result) {
            Cms::notification('Successfully created', 'success');
            return redirect(config('cms.backend-route-prefix', 'cms').'/players/'.$result->id.'/edit');
        }

        Cms::notification('Failed to create', 'warning');
        return redirect(config('cms.backend-route-prefix', 'cms').'/players');
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
        return view('players::players.show')->with('player', $player);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $player = $this->service->find($id);
        return view('players::players.edit')->with('player', $player);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\PlayerUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PlayerUpdateRequest $request, $id)
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
            return redirect(config('cms.backend-route-prefix', 'cms').'/players');
        }

        Cms::notification('Failed to delete', 'warning');
        return redirect(config('cms.backend-route-prefix', 'cms').'/players');
    }
}
