<?php

namespace Cms\Modules\Games\Controllers;

use Cms;
use CryptoService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cms\Modules\Games\Services\GameService;
use Carbon\Carbon;
use DB;
use Auth;

use Cms\Modules\Schedules\Services\ScheduleService;
use Cms\Modules\Teams\Services\TeamService;
use Cms\Modules\Players\Services\PlayerService;
use Cms\Modules\Games\Models\GameOwner;
use Cms\Modules\Games\Models\GameMessage;
use Cms\Modules\Codes\Services\CodeService;
use Cms\Modules\Venues\Services\VenueService;
use Cms\Modules\Games\Models\GameTeam;
use Cms\Modules\Players\Models\Player;

use Cms\Modules\Owners\Services\OwnerService;
use Grafite\Cms\Services\FileService;

use Cms\Modules\Games\Requests\GameCreateRequest;
use Cms\Modules\Games\Requests\GameUpdateRequest;


use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;


class GamesController extends Controller
{
	public $app_review = false;
	
	
	public $successStatus = 200;
	
    public function __construct(GameService $gameService, ScheduleService $scheduleService, TeamService $teamService, PlayerService $playerService, CodeService $codeService, VenueService $venueService, GameTeam $gameTeams, OwnerService $ownerService, GameOwner $gameOwners,
                                GameMessage $gameMessage)
    {
        $this->service = $gameService;
        $this->schedule = $scheduleService;
        $this->team = $teamService;
        $this->player = $playerService;
        $this->codes = $codeService;
        $this->venue = $venueService;
        $this->gameteam = $gameTeams;
        $this->game_owners = $gameOwners;
        $this->owner = $ownerService;
                $this->message = $gameMessage;
		}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $games = $this->service->paginated();
        return view('games::games.index')
            ->with('pagination', $games->render())
            ->with('games', $games);
    }

    /**
     * Display a listing of the resource searched.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $games = $this->service->search($request->search);
        return view('games::games.index')
            ->with('term', $request->search)
            ->with('pagination', $games->render())
            ->with('games', $games);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $venues = $this->venue->all();
        return view('games::games.create', compact('venues'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\GameCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GameCreateRequest $request)
    {

        if($request->prevenue == "0") {
            $logo = "";
            if ($request->hasFile('logo')) {    
                $file = request()->file('logo');
                $path = app(FileService::class)->saveFile($file, 'public/uploads', [], false);
                $path['name'] = url(str_replace('public/', 'storage/', $path['name']));
                $logo = $path['name'];
    
            }
            $address=$request->address.','.$request->city.','.$request->state.','.$request->zip;         
            $lat_lng = geocode($address);   
            $payload_venue = [
                'name' => $request->name,
                'logo' => $logo,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'zip' => $request->zip,
                'lat' => $lat_lng[0],
                'lon' => $lat_lng[1]
            ];
            $venue_result = $this->venue->create($payload_venue);
            $venue_id = $venue_result->id;
        } else {
            $venue_id = $request->prevenue;
        }


        $artwork = "";
        if ($request->hasFile('artwork')) {    
            $file = request()->file('artwork');
            $path = app(FileService::class)->saveFile($file, 'public/uploads', [], false);
            $path['name'] = url(str_replace('public/', 'storage/', $path['name']));
            $artwork = $path['name'];
        }

        $payload_game = [
            'venue_id' => $venue_id,
            'game_at' => $request->game_at,
            'start_at' => $request->start_at,
            'end_at' => $request->end_at,
            'game_code' => $request->game_code,
            'notes' => $request->notes,
            'artwork' => $artwork
            
        ];
        
        $game_result = $this->service->create($payload_game);

        if ($game_result) {
            Cms::notification('Successfully created', 'success');
            return redirect(config('cms.backend-route-prefix', 'cms').'/games/'.$game_result->id.'/pickteams');
        }

        Cms::notification('Failed to create', 'warning');
        return redirect(config('cms.backend-route-prefix', 'cms').'/games');



    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pickTeams($game_id)
    {
        $game = $this->service->find($game_id);


        $gameDay = $game->game_at->format('Y-m-d'); 

        $gameStart = $game->start_at;
        $gameEnd = $game->end_at;
        
        $gameDayStart = Carbon::createFromFormat('Y-m-d h:i A', $gameDay.' '.$gameStart, 'America/New_York')->setTimezone('UTC')->toDateTimeString();
        $gameDayEnd = Carbon::createFromFormat('Y-m-d h:i A', $gameDay.' '.$gameEnd, 'America/New_York')->setTimezone('UTC')->toDateTimeString();
        
        $daySchedule = $this->schedule->onDay($gameDayStart, $gameDayEnd)->get();

        return view('games::games.pick', compact('daySchedule', 'gameDay', 'game'));

    }
    
    public function saveTeams($game_id, Request $request)
    {
        $game = $this->service->find($game_id);
        
        foreach($request->schedule_id as $schedule_id) {
            $keep_player = "";
            
            $singleSchedule = $this->schedule->byId($schedule_id)->get();            
			$team_stats = "";          
            
            ## Save Home Team
            $team = New GameTeam;
            $team->schedule_id = $schedule_id;
            $team->game_id = $game_id;
            $team->team_id = $singleSchedule[0]['home_id'];
            $team->stats = $team_stats;
            $team->save();
            
            $home_team_id = $singleSchedule[0]['home_id'];
  			  	
	  		$ch = curl_init();
	  		curl_setopt($ch, CURLOPT_URL, "https://api.mysportsfeeds.com/".config('app.msf_ver')."/pull/nfl/".config('app.msf_year')."-".config('app.msf_season')."/player_stats_totals.json?team=".$singleSchedule[0]['home_id']."&position=qb,wr,rb,te&rosterstatus=assigned-to-roster");
	  		$nostats = false;
	  		
	  		// curl_setopt($ch, CURLOPT_URL, "https://api.mysportsfeeds.com/".config('app.msf_ver')."/pull/nfl/players.json?season=current&team=".$singleSchedule[0]['home_id']."&position=qb,wr,rb,te&rosterstatus=assigned-to-roster");
	  		// $nostats = true;
	  		
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_ENCODING, "gzip");
			curl_setopt($ch, CURLOPT_HTTPHEADER, [
				"Authorization: Basic " . config('app.msf_api')
			]);
			$resp = curl_exec($ch);
			
			if (!$resp) {
				die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
			} else {
				$schedules = json_decode($resp, true);
			}
			curl_close($ch);

			if($nostats == true) {
				$players = $schedules["players"];
			}
			if($nostats == false) {
				$players = $schedules["playerStatsTotals"];
			}
            sleep(6);
               
			foreach($players as $person) {

				if($nostats == true) {
					$player_stats = '{"gamesPlayed":0,"passing":{"passAttempts":0,"passCompletions":0,"passPct":0.0,"passYards":0,"passAvg":0.0,"passYardsPerAtt":0.0,"passTD":0,"passTDPct":0.0,"passInt":0,"passIntPct":0.0,"passLng":0,"pass20Plus":0,"pass40Plus":0,"passSacks":0,"passSackY":0,"qbRating":0.0},"rushing":{"rushAttempts":0,"rushYards":0,"rushAverage":0.0,"rushTD":0,"rushLng":0,"rush1stDowns":0,"rush1stDownsPct":0.0,"rush20Plus":0,"rush40Plus":0,"rushFumbles":0},"receiving":{"targets":0,"receptions":0,"recYards":0,"recAverage":0,"recTD":0,"recLng":0,"rec1stDowns":0,"rec20Plus":0,"rec40Plus":0,"recFumbles":0},"tackles":{"tackleSolo":0,"tackleTotal":0,"tackleAst":0,"sacks":0.0,"sackYds":0,"tacklesForLoss":0},"interceptions":{"interceptions":0,"intTD":0,"intYds":0,"intAverage":0.0,"intLng":0,"passesDefended":0,"stuffs":0,"stuffYds":0,"safeties":0,"kB":0},"fumbles":{"fumbles":0,"fumLost":0,"fumForced":0,"fumOwnRec":0,"fumOppRec":0,"fumRecYds":0,"fumTotalRec":0,"fumTD":0},"kickoffReturns":{"krRet":0,"krYds":0,"krAvg":0.0,"krLng":0,"krTD":0,"kr20Plus":0,"kr40Plus":0,"krFC":0,"krFum":0},"puntReturns":{"prRet":0,"prYds":0,"prAvg":0.0,"prLng":0,"prTD":0,"pr20Plus":0,"pr40Plus":0,"prFC":0,"prFum":0},"miscellaneous":{"gamesStarted":0},"twoPointAttempts":{"twoPtAtt":0,"twoPtMade":0,"twoPtPassAtt":0,"twoPtPassMade":0,"twoPtPassRec":0,"twoPtRushAtt":0,"twoPtRushMade":0}}';
					$player_array = json_decode($player_stats, true);
					$possibleInjury = "";
					$activePerson = 1;
				}
	
				if($nostats == false) {
					$player_stats = json_encode($person['stats']);
					$player_array = json_decode($player_stats, true);
				
					$possibleInjury = "";
					if($person['stats']['gamesPlayed'] != "0") {
						$activePerson = 1;
					} else { $activePerson = 0;}
				}				

                if($person['player']['currentTeam'] != null) {
                    if($person['player']['currentTeam']['id'] == $home_team_id) {
                        $hidePlayer = 0;
                    } else { $hidePlayer = 1;}
                } else {
                    $hidePlayer = 1;
                }
				
				if($person['player']['currentInjury'] != null) {
					if($person['player']['currentInjury']['playingProbability'] == 'OUT' || $person['player']['currentInjury']['playingProbability'] == 'QUESTIONABLE') {
						$activePerson = 0;
						$possibleInjury = $person['player']['currentInjury']['playingProbability']. ' - ' .$person['player']['currentInjury']['description'];
					} else {
						$activePerson = 1;
						$possibleInjury = "";
					}
				}

                $player = New Player;
                $player->active = "$activePerson";
                $player->player_id = $person['player']['id'];
                $player->first_name = $person['player']['firstName'];
                $player->last_name = $person['player']['lastName'];
                $player->abbr_name = $person['player']['firstName'];
                $player->jersey = $person['player']['jerseyNumber'];
                $player->weight = $person['player']['weight'];
                $player->height = $person['player']['height'];
                $player->position = $person['player']['primaryPosition'];
                $player->team_id = $home_team_id;
                $player->schedule_id = $schedule_id;
                $player->stats = $player_array;
                $player->game_id = $game_id;
                
                $player->injury = $possibleInjury;
                
                $player->opponent_id = $singleSchedule[0]['visitor_id'];
                $player->home_visitor = 'h';
                $player->espn_id = $person['player']['officialImageSrc'];                        
                
				if($hidePlayer == 0) {	               
					$player->save();
				}
               
               
                $keep_player = '';
                $player_stats = '';
			}
            
            
			## Save Away Team
            $team = New GameTeam;
            $team->schedule_id = $schedule_id;
            $team->game_id = $game_id;
            $team->team_id = $singleSchedule[0]['visitor_id'];
            $team->stats = $team_stats;
            $team->save();
            
            $home_team_id = $singleSchedule[0]['visitor_id'];
  			  	
	  		$ch = curl_init();
	  		curl_setopt($ch, CURLOPT_URL, "https://api.mysportsfeeds.com/".config('app.msf_ver')."/pull/nfl/".config('app.msf_year')."-".config('app.msf_season')."/player_stats_totals.json?team=".$singleSchedule[0]['visitor_id']."&position=qb,wr,rb,te&rosterstatus=assigned-to-roster");
	  		$nostats = false;
	  		
	  		// curl_setopt($ch, CURLOPT_URL, "https://api.mysportsfeeds.com/".config('app.msf_ver')."/pull/nfl/players.json?season=current&team=".$singleSchedule[0]['visitor_id']."&position=qb,wr,rb,te&rosterstatus=assigned-to-roster");
	  		// $nostats = true;
	  		// 
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_ENCODING, "gzip");
			curl_setopt($ch, CURLOPT_HTTPHEADER, [
				"Authorization: Basic " . config('app.msf_api')
			]);
			$resp = curl_exec($ch);
			
			if (!$resp) {
				die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
			} else {
				$schedules = json_decode($resp, true);
			}
			curl_close($ch);

			if($nostats == true) {
				$players = $schedules["players"];
			}
			if($nostats == false) {
				$players = $schedules["playerStatsTotals"];
			}
            sleep(6);
			foreach($players as $person) {

				if($nostats == true) {
					$player_stats = '{"gamesPlayed":0,"passing":{"passAttempts":0,"passCompletions":0,"passPct":0.0,"passYards":0,"passAvg":0.0,"passYardsPerAtt":0.0,"passTD":0,"passTDPct":0.0,"passInt":0,"passIntPct":0.0,"passLng":0,"pass20Plus":0,"pass40Plus":0,"passSacks":0,"passSackY":0,"qbRating":0.0},"rushing":{"rushAttempts":0,"rushYards":0,"rushAverage":0.0,"rushTD":0,"rushLng":0,"rush1stDowns":0,"rush1stDownsPct":0.0,"rush20Plus":0,"rush40Plus":0,"rushFumbles":0},"receiving":{"targets":0,"receptions":0,"recYards":0,"recAverage":0,"recTD":0,"recLng":0,"rec1stDowns":0,"rec20Plus":0,"rec40Plus":0,"recFumbles":0},"tackles":{"tackleSolo":0,"tackleTotal":0,"tackleAst":0,"sacks":0.0,"sackYds":0,"tacklesForLoss":0},"interceptions":{"interceptions":0,"intTD":0,"intYds":0,"intAverage":0.0,"intLng":0,"passesDefended":0,"stuffs":0,"stuffYds":0,"safeties":0,"kB":0},"fumbles":{"fumbles":0,"fumLost":0,"fumForced":0,"fumOwnRec":0,"fumOppRec":0,"fumRecYds":0,"fumTotalRec":0,"fumTD":0},"kickoffReturns":{"krRet":0,"krYds":0,"krAvg":0.0,"krLng":0,"krTD":0,"kr20Plus":0,"kr40Plus":0,"krFC":0,"krFum":0},"puntReturns":{"prRet":0,"prYds":0,"prAvg":0.0,"prLng":0,"prTD":0,"pr20Plus":0,"pr40Plus":0,"prFC":0,"prFum":0},"miscellaneous":{"gamesStarted":0},"twoPointAttempts":{"twoPtAtt":0,"twoPtMade":0,"twoPtPassAtt":0,"twoPtPassMade":0,"twoPtPassRec":0,"twoPtRushAtt":0,"twoPtRushMade":0}}';
					$player_array = json_decode($player_stats, true);
					$possibleInjury = "";
					$activePerson = 1;
				}
	
				if($nostats == false) {
					$player_stats = json_encode($person['stats']);
					$player_array = json_decode($player_stats, true);
				
					$possibleInjury = "";
					if($person['stats']['gamesPlayed'] != "0") {
						$activePerson = 1;
					} else { $activePerson = 0;}
				}				
	
				if($person['player']['currentTeam'] != null) {
    				if($person['player']['currentTeam']['id'] == $home_team_id) {
    					$hidePlayer = 0;
    				} else { $hidePlayer = 1;}
                } else {
                    $hidePlayer = 1;
                }
                				
				if($person['player']['currentInjury'] != null) {
					if($person['player']['currentInjury']['playingProbability'] == 'OUT' || $person['player']['currentInjury']['playingProbability'] == 'QUESTIONABLE') {
						$activePerson = 0;
						$possibleInjury = $person['player']['currentInjury']['playingProbability']. ' - ' .$person['player']['currentInjury']['description'];
					} else {
						$activePerson = 1;
						$possibleInjury = "";
					}
				}
	
	                $player = New Player;
	                $player->active = "$activePerson";
	                $player->player_id = $person['player']['id'];
	                $player->first_name = $person['player']['firstName'];
	                $player->last_name = $person['player']['lastName'];
	                $player->abbr_name = $person['player']['firstName'];
	                $player->jersey = $person['player']['jerseyNumber'];
	                $player->weight = $person['player']['weight'];
	                $player->height = $person['player']['height'];
	                $player->position = $person['player']['primaryPosition'];
	                $player->team_id = $home_team_id;
	                $player->schedule_id = $schedule_id;
	                $player->stats = $player_array;
	                $player->game_id = $game_id;
	                
	                $player->injury = $possibleInjury;
	                
	                $player->opponent_id = $singleSchedule[0]['home_id'];
	                $player->home_visitor = 'h';
	                $player->espn_id = $person['player']['officialImageSrc'];                        
	                
					if($hidePlayer == 0) {	               
						$player->save();
					}
	               
	               
	                $keep_player = '';
	                $player_stats = '';
	
           
	
			}

        }
        return redirect(config('cms.backend-route-prefix', 'cms').'/games/'.$game_id.'/pickplayers');
        
    }
    
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function pickPlayers($game_id)
    {
        $game = $this->service->find($game_id);
        $gameDay = $game->game_at->format('m/d/Y'); 
        $app_review = config('app.msf_app_review');
        $players = $this->player->findByGame($game_id);
        
        
        return view('games::games.players', compact('game', 'players', 'gameDay', 'app_review'));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function savePlayers(Request $request, $game_id)
    {
        
        $game = $this->service->find($game_id);


        $affected = DB::table('players')->where('game_id', '=', $game_id)->update(array('active' => 0));


        foreach($request->player as $activate_player) {
            $player = $this->player->find($activate_player);
            $player->active = "1";
            $player->save();
        }
        
        return redirect('/cms/games');
    }

    public function leaderboard($game_id)
    {
	    $game = $this->service->find($game_id);
        $this_game_owners = $this->game_owners->select('owners.full_name', 'games_owners.score', 'owners.id', 'owners.email')->where('game_id', '=', $game_id)->join('owners', 'owners.id', '=', 'games_owners.owner_id')->orderBy('games_owners.score', 'DESC')->get();
        return view('games::games.leaderboard', compact('game','this_game_owners'));
    }

	public function rosterPage($game_id, $owner_id)
    {
        $game = $this->service->find($game_id);
        $codes_tt = $this->codes->findByGroupSubCode('nfl_game', 'tt')->get();
        $codes_re = $this->codes->findByGroupSubCode('nfl_game', 're')->get();
        $codes_ay = $this->codes->findByGroupSubCode('nfl_game', 'ay')->get();
        
        $owner = $this->owner->find($owner_id);
        return view('games::games.roster', compact('game','codes_tt','codes_re','codes_ay','owner'));
                
    }

	public function trashtalk($game_id)
	{
        $game = $this->service->find($game_id);
        $owner = 'Commissioner';
        return view('games::games.chat', compact('game','owner'));		
	}

	public function trashtalkAdd(Request $request)
	{

	$data = $request->all();
	
	

		$owner = $this->owner->find($data['n']);
		$game = $this->service->find($data['g']);		

		$this_game_owners = $this->game_owners->select('owners.id', 'owners.full_name', 'games_owners.score', 'games_owners.locked', 'owners.firebase')->where('game_id', '=', $game->id)->join('owners', 'owners.id', '=', 'games_owners.owner_id')->orderBy('games_owners.score', 'DESC')->get();
		
		

		$store_message = new GameMessage;
		$store_message->owner_id = $owner->id;
		$store_message->game_id = $game->id;
		$store_message->message = $data['m'];
		$store_message->save();


		$optionBuilder = new OptionsBuilder();
		$optionBuilder->setTimeToLive(60*20);
		$this_message = addslashes($data['m']);
		$notificationBuilder = new PayloadNotificationBuilder('Trash Talk');
		$notificationBuilder->setBody(
$owner->full_name . ' just said: 
'.$this_message
		)->setSound('default');
		
		$dataBuilder = new PayloadDataBuilder();
		$dataBuilder->addData(['action' => 'trashtalk']);
		
		$option = $optionBuilder->build();
		$notification = $notificationBuilder->build();
		$data = $dataBuilder->build();
		
		
		foreach($this_game_owners as $one_owner) {
			if($one_owner->firebase != "" && $one_owner->id != $owner->id && $one_owner->locked == "1") {
				$token = $one_owner->firebase;		
				$downstreamResponse = FCM::sendTo($token, $option, $notification, $data);				
			}
		}

        return response()->json(['success' => $downstreamResponse], $this->successStatus);



	
	}


	public function trashtalkView(Request $request)
	{
	
		$data = $request->all();
		
//		return $data['g'];
		

		$owner = $this->owner->find(2);
		$game = $this->service->find($data['g']);		

		$game_messages = $this->service->findMessages($game->id);
		$message = "";
		foreach($game_messages as $one_message) {
			$owner = $one_message->owner;
			$owner_name = $owner['full_name'];
			
$message .= 	$owner_name . " | " . $one_message->message ." 

";
									
		$messages[] = [
		            "id" => "$one_message->id",
		            "owner_id" => "$one_message->owner_id",
					"full_name" => "$owner_name",
					"message" => "$one_message->message",
					"sent_at" => "$one_message->created_at"
					];			
		}

		return $message;
				
//		return response()->json(['success' => $messages], $this->successStatus);
				
	
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
        return view('games::games.show')->with('game', $game);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $game = $this->service->find($id);
        return view('games::games.edit')->with('game', $game);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\GameUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GameUpdateRequest $request, $id)
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
            return redirect(config('cms.backend-route-prefix', 'cms').'/games');
        }

        Cms::notification('Failed to delete', 'warning');
        return redirect(config('cms.backend-route-prefix', 'cms').'/games');
    }



}
