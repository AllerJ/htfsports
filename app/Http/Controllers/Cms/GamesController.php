<?php

namespace App\Http\Controllers\Cms;


use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cms\Modules\Games\Services\GameService;
use Cms\Modules\Games\Models\GameOwner;
use Cms\Modules\Games\Models\GameMessage;
use Cms\Modules\Rosters\Services\RosterService;
use Cms\Modules\Teams\Services\TeamService;
use Cms\Modules\Players\Models\PlayerImage;
use Cms\Modules\Players\Services\PlayerService;
use Cms\Modules\Codes\Services\CodeService;
use Cms\Modules\Owners\Services\OwnerService;
use DB;
use Carbon\Carbon;

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;


class GamesController extends Controller
{
	// SET TO TRUE WHEN APP UPDATES ARE SENT TO APPLE. THIS CHANGES HEADSHOT IMAGES AND UNLOCKS THE RESTRICTIONS ON PAST GAMES
	public $app_review = false;
	
	
	public $successStatus = 200;
    public function __construct(GameService $gameService, 
                                GameOwner $gameOwners, 
                                RosterService $rosterService, 
                                TeamService $teamService, 
                                PlayerService $playerService,
                                CodeService $codeService,
                                OwnerService $ownerService,
                                GameMessage $gameMessage)
    {
        $this->service = $gameService;
        $this->game_owners = $gameOwners;
        $this->roster = $rosterService;
        $this->team = $teamService;
        $this->player = $playerService;
        $this->code = $codeService;
        $this->owner = $ownerService;
        $this->message = $gameMessage;
    }



// API
	public function api_findGame(Request $request)
	{
		
		$headers = apache_request_headers();
		$owner = Auth::user();
		$game = $this->service->findByCode($headers['Game']);	
		
		if($game) {
			$success['game']['game_id'] = $game->id;	
			return response()->json(['success' => $success], $this-> successStatus);
		}else{
			return response()->json(['error'=>'Unauthorised'], 401);
		}

	}

	public function api_findGameByGeo(Request $request)
	{
		
		$headers = apache_request_headers();

		$game = $this->service->findNearby($headers['Lat'], $headers['Lon']);
		
		if($game) {
			$success['game']['game_id'] = $game->id;	
			$success['game']['venue'] = $game->name;
			$success['game']['game_code'] = $game->game_code;
			return response()->json(['success' => $success], $this-> successStatus);
		}else{
			return response()->json(['error'=>'Unauthorised'], 401);
//						return response()->json(['success' => "no game found " . date("Y-m-d 00:00:00")], $this-> successStatus);
		}
		

	}

	public function api_joinGame(Request $request)
    {
	    $headers = apache_request_headers();
		$owner = Auth::user();
		$game = $this->service->find($headers['Game']);		


		if($game) {
			$success['game']['game_code'] = $game->game_code;
			$success['game']['game_id'] = $game->game_id;
			$success['game']['game_code'] = $game->game_code;
			$success['game']['artwork'] = $game->artwork;
			$success['game']['game_at'] = $game->game_at;
			$success['game']['start_at'] = $game->start_at;
			$success['game']['end_at'] = $game->end_at;
			$success['game']['notes'] = $game->notes;
			$success['game']['address'] = $game->venue->address;
			$success['game']['city'] = $game->venue->city;
			$success['game']['zip'] = $game->venue->zip;
			$success['game']['logo'] = $game->venue->logo;
			$success['game']['venue_id'] = $game->venue->id;
			$success['game']['name'] = $game->venue->name;
			$success['status'] = 'good';
        
			date_default_timezone_set('America/New_York');
        
	        $game_date = explode(' ', $game->game_at);
			$date = new Carbon;
			$lock_after = Carbon::parse($game_date[0].' '.$game->end_at);
			

			if($date > $lock_after) {
				$success['status'] = "closed";
				return response()->json(['success' => $success], $this->successStatus);
			} 

       
	     
	        $owner->gameid = $game->id;
	        $owner->save();
	
	        
	        $result = $this->game_owners->findByOG($owner->id, $game->id)->first();
	        
	        if(!$result) {
	            $attach_owner = new GameOwner;
	            $attach_owner->owner_id = $owner->id;
	            $attach_owner->game_id = $game->id;
	            $attach_owner->save();
	            
	            $locked = 0;
				
	        } else {	     
				$locked = $result->locked;
/*
		        $locked = 1;
				$result->locked = 1;
				$result->save();
*/
	        }
	
	
	        if($locked == 0){
		        $success['status'] = "picking";
				return response()->json(['success' => $success], $this->successStatus);
	
	        } else {
				$success['status'] = "locked";
				return response()->json(['success' => $success], $this->successStatus);
	
	        }
        } else {
	        $success['status'] = "no";
	        return response()->json(['success' => $success], $this->successStatus);
        }

	}


	public function api_getMessages() 
	{

		$headers = apache_request_headers();
		$owner = Auth::user();
		$game = $this->service->find($headers['Game']);		

		$game_messages = $this->service->findMessages($game->id);



		foreach($game_messages as $one_message) {
			$owner = $one_message->owner;
			$owner_name = $owner['full_name'];
									
		$messages[] = [
		            "id" => "$one_message->id",
		            "owner_id" => "$one_message->owner_id",
					"full_name" => "$owner_name",
					"message" => "$one_message->message",
					"sent_at" => "$one_message->created_at"
					];			
		}
				
		return response()->json(['success' => $messages], $this->successStatus);
		
		
	}

	public function api_checkToken()
	{
		$headers = apache_request_headers();
		$owner = Auth::user();
		return response()->json(['success' => $owner], $this->successStatus);
		
	}
	public function api_sendMessages(Request $request) 
	{
		$headers = apache_request_headers();
		$owner = Auth::user();
		$game = $this->service->find($headers['Game']);		

		$this_game_owners = $this->game_owners->select('owners.id', 'owners.full_name', 'games_owners.score', 'games_owners.locked', 'owners.firebase')->where('game_id', '=', $game->id)->join('owners', 'owners.id', '=', 'games_owners.owner_id')->orderBy('games_owners.score', 'DESC')->get();
		
		

		$store_message = new GameMessage;
		$store_message->owner_id = $owner->id;
		$store_message->game_id = $game->id;
		$store_message->message = $request->message;
		$store_message->save();


		$optionBuilder = new OptionsBuilder();
		$optionBuilder->setTimeToLive(60*20);
		$this_message = addslashes($request->message);
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


    public function api_levels($level)
    {

	    $quick_owner = Auth::user();
		$owner = $this->owner->find($quick_owner->id);
        $game = $this->service->find($owner->gameid);
        $codes = $this->code->findByGroupSubCode('nfl_game', $level)->get();
	    $owner['password']="";
	    $roster = $this->roster->findByOwnerLevel($level, $owner->gameid, $owner->id)->get();
	    $rosterCount = $this->roster->findGO($owner->gameid, $owner->id);
	    
        if($game->locked == 1){
			return response()->json(['success' => 'locked in'], $this->successStatus);
        }
        
        $success['game'] = $game;
        $success['codes'] = $codes;
        $success['owner'] = $owner;
        $success['roster'] = $roster;
        $success['rosterCount'] = count($rosterCount);
        
        return response()->json(['success' => $success], $this->successStatus);

        
    }





	public function api_gameDraft($level)
    {
		$headers = apache_request_headers();
	    $quick_owner = Auth::user();
		$owner = $this->owner->find($quick_owner->id);
        $owner['password']="";
        $game = $this->service->find($owner->gameid);
        $roster = $this->roster->findGO($owner->gameid, $owner->id);
        $code = $this->code->findByCode($level)->first();
        $count = 0;
        
        $sortBy = $headers['Sort'];
        
        
        foreach($game->active_players->whereIn('position', explode(',', $code->extra_1)) as $player) {
           

			if($player->stats) {
	
			    $rushing_yds = 0;
			    $receiving_yds = 0;
			    $kick_yds = 0;
			    $punt_yds = 0;
			    
			    if(isset($player->stats['rushing']['rushYards'])) {
			        $rushing_yds = $player->stats['rushing']['rushYards'];
			    } else { $rushing_yds = 0; }
			    if(isset($player->stats['receiving']['recYards'])) {
			        $receiving_yds = $player->stats['receiving']['recYards'];
			    } else { $receiving_yds = 0; }
			    if(isset($player->stats['kickoffReturns']['krYds'])) {
			        $kick_yds = $player->stats['kickoffReturns']['krYds'];
			    } else { $kick_yds = 0; }
			    if(isset($player->stats['puntReturns']['prYds'])) {
			        $punt_yds = $player->stats['puntReturns']['prYds'];
			    } else { $punt_yds = 0; }
			    if(isset($player->stats['receiving']['receptions'])) {
				    $receptions = $player->stats['receiving']['receptions'];
			    } else { $receptions = 0; }
			    if(isset($player->stats['passing']['passTD'])){
				    $touchdowns = $player->stats['passing']['passTD'];
			    } else { $touchdowns = 0; }
			    if(isset($player->stats['gamesPlayed'])) {
				    $games_played = $player->stats['gamesPlayed'];
			    } else { $games_played = 0; }

			    $all_yds = $kick_yds + $receiving_yds + $rushing_yds + $punt_yds;
			    
					if($games_played == 0) {
						$avg_yrds = 0;
						$avg_tds = 0;
						$avg_recp = 0;
						
					} else {
						$played = $games_played;
						$yrds = $all_yds;
						$tds = $touchdowns;
						$recp = $receptions;	
					
						$avg_yrds = round($yrds/$played, 2);
						$avg_tds = round($tds/$played, 2);
						$avg_recp = round($recp/$played, 2);
						
					}
			    
			    

			}
           
           $picked_yet = $player->drafted($quick_owner->id, $game->id);
     
			if(!$picked_yet) {

				$fliter = $player->abbr_name.",".$player->last_name.",".$player->team->name.",".$player->position_name->description;
		             

		             $second_color = $player->team->color_second; 
					 if($player->espn_id != "") {
						 $msf_headshot = $player->espn_id;
					 } else {
						 $msf_headshot = "https://api.htfsports.com/img/player.php?color=$second_color";
					 }

		          
// if($app_review == true ){
// 					 $msf_headshot = "https://api.htfsports.com/img/player.php?color=$second_color&team=".$player->team_id;
// }

	//				 $msf_headshot = "https://api.htfsports.com/img/player.php?color=$second_color";
		             
	            $players[] = [
		            "id" => $count,
		            "player_id" => "$player->id",
					"first_name" => $player->abbr_name,
					"last_name" => $player->last_name,
					"position" => $player->position_name->description,
					"jersey" => $player->jersey,
					"team" => $player->team->name,
					"logo" => $player->team->logo,
					"headshot" =>  $msf_headshot,
					"home_visitor" => "",//$player->home_visitor,
					"opponent" => $player->opponent->name,
					"opponent_logo" => $player->opponent->logo,
					"all_yards" => "$avg_yrds",
					"punt" => "$punt_yds",
					"kick" => "$kick_yds",
					"receiving" => "$receiving_yds",
					"rushing" => "$rushing_yds",
					"receptions" => "$avg_recp",
					"ptd" => "$avg_tds",
					"games_played" => "$games_played",
					"level_code" => "$level",
					"filter" => "$fliter",
					"color" => $player->team->color
				];
		$count++;

			}

        }
		foreach($roster as $onePick) {
/*
			$thing = $this->array_find($onePick->player_id, $players);
			unset($players[$thing]);
*/
		}


		if($sortBy == "receptions") {
			usort($players, function ($a, $b) {
			    return $b['receptions'] <=> $a['receptions'];
			});			
		}
		

		if($sortBy == "all_yards") {
			usort($players, function ($a, $b) {
			    return $b['all_yards'] <=> $a['all_yards'];
			});			
		}
		
		if($sortBy == "ptd") {
			usort($players, function ($a, $b) {
			    return $b['ptd'] <=> $a['ptd'];
			});			
		}
		
		

		$players = array_values($players);
		
		
		$justgame = $this->service->find($owner->gameid);
		$success['game'] = $justgame;
		$success['code'] = $code;
		$success['owner'] = $owner;
		$success['players'] = $players;
		
  	    return response()->json(["success" => $success], $this->successStatus);

	}

	public function array_find($needle, array $haystack)
	{

		foreach($haystack as $index => $car) {
        	if($car['player_id'] == $needle) return $index;
    	}
		return FALSE;
    
   	}

	public function api_gamePostDraft($level, $player_id)
    {
	    
	    $quick_owner = Auth::user();
		$owner = $this->owner->find($quick_owner->id);
        $owner['password']="";
        
        
		$code = $this->code->findByCode($level)->first();
	    $owner_id = $owner->id;
        $game_id = $owner->gameid;    
            
        $result = $this->roster->findLGO($level, $game_id, $owner_id);

        $payload = [
            'owner_id' => $owner_id,
            'game_id' => $game_id,
            'player_id' => $player_id,
            'level_id' => $level,
            'level' => $code->subcode
        ];
        
        if($result) {
            $update = true;
            $roster = $this->roster->update($result->id, $payload);
        } else {
            $update = false;
            $roster = $this->roster->create($payload);            
        }
	    
		return $this->api_levels($code->subcode);
	    
	}



	public function api_gameDraftRest($group)
    {
	    $quick_owner = Auth::user();
		$owner = $this->owner->find($quick_owner->id);
        $owner['password']="";
        
        $code = $this->code->findByGroupSubCode('nfl_game', $group)->first();
        
        DB::table('rosters')->where('game_id', '=', $owner->gameid)->where('owner_id', '=', $owner->id)->where('level', '=', $group)->delete();
        
	    return $this->api_levels($group);
	           
	}



    public function api_leaderboard()
    {
	    
		$quick_owner = Auth::user();
		$owner = $this->owner->find($quick_owner->id);
		$owner['password']="";
		$count = 0;
		$game = $this->service->find($owner->gameid);

        if($owner->locked != 1) {
		    DB::table('games_owners')
		        ->where('game_id', '=', $owner->gameid)
		        ->where('owner_id', '=', $owner->id)
		        ->update(['locked' => 1]);
        } 


		$this_game_owners = $this->game_owners->select('owners.id', 'owners.full_name', 'games_owners.score', 'games_owners.locked')->where('game_id', '=', $owner->gameid)->join('owners', 'owners.id', '=', 'games_owners.owner_id')->orderBy('games_owners.score', 'DESC')->get();
		
		foreach($this_game_owners as $owner) {
			$count++;
			$this_game_all_owners[] = [
				"rank" => "$count",
				"id" => "$owner->id",
				"full_name" => $owner->full_name,
				"score" => "$owner->score",
				"locked" => "$owner->locked"
			];
		}
		
		return response()->json(['game' => $game, 'owner' => $owner, 'this_game_owners' => $this_game_all_owners], $this->successStatus);
					    
    }

	public function api_display_leaderboard()
    {
	    $headers = apache_request_headers();
		$count = 0;
		$game = $this->service->findByCode($headers['Game']);

		$this_game_all_owners[] = [
				"rank" => "",
				"id" => "0",
				"full_name" => "Name",
				"score" => "Score",
				"locked" => "1"
			];

		$this_game_owners = $this->game_owners->select('owners.id', 'owners.full_name', 'games_owners.score', 'games_owners.locked')->where('game_id', '=', $game->id)->join('owners', 'owners.id', '=', 'games_owners.owner_id')->orderBy('games_owners.score', 'DESC')->get();
		
		foreach($this_game_owners as $owner) {
			$count++;
			$this_game_all_owners[] = [
				"rank" => "$count",
				"id" => "$owner->id",
				"full_name" => $owner->full_name,
				"score" => "$owner->score",
				"locked" => "$owner->locked"
			];
		}
		
		return response()->json(['game' => $game, 'this_game_owners' => $this_game_all_owners], $this->successStatus);
					    
    }

    public function api_roster($owner_id)
    {      
	    

	    $headers = apache_request_headers();
       	
       	$quick_owner = Auth::user();
		
		if($owner_id == "null") {
		    $owner = $this->owner->find($quick_owner->id);
	    } else {
		   $owner = $this->owner->find($owner_id);   
	    }
        
        $game_owner = $this->game_owners->findByOG($owner->id, $headers['Game'])->first();
        		
		$owner['password']="";
		$owner['score'] = $game_owner->score;


        $game = $this->service->find($headers['Game']);
        
        $codes_tt = $this->code->findByGroupSubCode('nfl_game', 'tt')->get();
        $codes_re = $this->code->findByGroupSubCode('nfl_game', 're')->get();
        $codes_ay = $this->code->findByGroupSubCode('nfl_game', 'ay')->get();
        
        $roster_owner = $this->roster->findGO($headers['Game'], $owner->id);
        

	        


		$level_names = array();

		$sections = array('codes_tt', 'codes_ay', 'codes_re');


		foreach($sections as $one_level) {

			foreach(${$one_level} as $code) {
			
				$this_roster = array();
				
				foreach($roster_owner as $roster) {
	


					
					 $second_color = $roster->player->team->color_second;
		           
					 if($roster->player->espn_id != "") {
						 $msf_headshot = $roster->player->espn_id;
					 } else {
						 $msf_headshot = "https://api.htfsports.com/img/player.php?color=$second_color";
					 }


					// if($app_review == true ){
					// 	$msf_headshot = "https://api.htfsports.com/img/player.php?color=$second_color&team=".$player->team_id;
					// }


					// $msf_headshot = "https://api.htfsports.com/img/player.php?color=$second_color";

					if($roster->level == $code->subcode){
	
						if($roster->current_stat >= $roster->extra_2) {
							$color = "yes";//"Color(0xFF4EA647)";
						} else {
							$color = "no";//"Color(0xFFDBDBDB)";
						}					
						
						$this_roster[] = [
				            "level_id" => "$roster->level_id",
							"abbr_name" => "$roster->abbr_name",
							"last_name" => "$roster->last_name",
							"player_id" => "$roster->player_id",
							"team_name" => $roster->player->team->name,
							"team_color" => $roster->player->team->color,
							"headshot" => "$msf_headshot",
							"position" => $roster->player->position,
							"current_stat" => "$roster->current_stat",
							"points" => "$roster->more",
							"goal" => "$roster->extra_2",
							"color" => $color
						];
						
					}
				
				}

					
					
				if($code->order == 1) {
				
				
						$abbr = explode(' ', $code->extra);
				
				
					$level_names[] = [
						"name" => $code->description,
						"code" => $code->subcode,
						"roster" => $this_roster,
						"abbr" => $abbr[1]	
					];
				}
				
				
			}
			
		}


/*
        if($owner_id == $owner->id) {
		    DB::table('games_owners')
	            ->where('game_id', '=', $owner->gameid)
	            ->where('owner_id', '=', $owner->id)
	            ->update(['locked' => 1]);
        }   
*/
	
	


		$success['owner'] = $owner;
		$success['game'] = $game;
		$success['level_names'] = $level_names;
		return response()->json(['success' => $success], $this->successStatus);

        
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
        return Socialite::with('Instagram')->redirect();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(isin() == false) { return redirect('/'); }
        
        $game = $this->service->findByCode($id);
        return view('cms-frontend::games.show')->with('game', $game);            
    }
     
     
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function gameCode()
    {

        return view('cms-frontend::pages.gamecode');            
    }   
    
    public function notifications()
    {
        return ['success'];
    }


	public function manual_joingame()
	{
		return view('cms-frontend::pages.gamecode');   	
	}
	
	public function auto_joinGame()
	{
		$headers = apache_request_headers();
		$owner = Auth::user();
		$game = $this->service->find($headers['Game']);		

        $result = $this->game_owners->findByOG(owner('owner_id'), $game->id)->first();
        
        if(!$result) {
            $attach_owner = new GameOwner;
            $attach_owner->owner_id = owner('owner_id');
            $attach_owner->game_id = $game->id;
            $attach_owner->save();

			return view('cms-frontend::games.show')->with('game', $game);
			
        } else {
	        return view('cms-frontend::games.show')->with('game', $game);
        }
		
	}
	
	
	
	
	
	public function html_touchDowns()
    {
	
		$headers = apache_request_headers();
		$owner = Auth::user();
		$game = $this->service->find($headers['Game']);		
        
        $codes = $this->code->findByGroupSubCode('nfl_game', 'tt')->get();
        $owner = $this->owner->find(owner('owner_id'));

        return view('cms-frontend::games.pick', compact('game','codes','owner'));


    }
	
	
	public function html_allYards()
    {
	
		$headers = apache_request_headers();
		$owner = Auth::user();
		$game = $this->service->find($headers['Game']);		
        
        $codes = $this->code->findByGroupSubCode('nfl_game', 'ay')->get();
        $owner = $this->owner->find(owner('owner_id'));

        return view('cms-frontend::games.pick', compact('game','codes','owner'));


    }
    
    public function html_receptions()
    {
	
		$headers = apache_request_headers();
		$owner = Auth::user();
		$game = $this->service->find($headers['Game']);		
        
        $codes = $this->code->findByGroupSubCode('nfl_game', 're')->get();
        $owner = $this->owner->find(owner('owner_id'));

        return view('cms-frontend::games.pick', compact('game','codes','owner'));


    }
	
	
	public function html_gameDraft($level)
    {

		$headers = apache_request_headers();
		$owner = Auth::user();
		$game = $this->service->find($headers['Game']);	
		
		print_r($headers);
		
/*
        
		$game = $this->service->find($headers['Game']);
        $code = $this->code->findByCode($level)->first();
        
        $roster = $this->roster->findGO($headers['Game'], $owner->id);
        
        
        
        foreach($game->active_players->whereIn('position', explode(',', $code->extra_1)) as $player) {
            
            if(isset($player->headshot->headshot)) {
	            $headshot = $player->headshot->headshot;
            } else {
	            $headshot = false;
            }

			if($player->stats) {
	
			    $rushing_yds = 0;
			    $receiving_yds = 0;
			    $kick_yds = 0;
			    $punt_yds = 0;
			    
			    if(isset($player->stats['rushing']['yards'])) {
			        $rushing_yds = $player->stats['rushing']['yards'];
			    } else { $rushing_yds = false; }
			    if(isset($player->stats['receiving']['yards'])) {
			        $receiving_yds = $player->stats['receiving']['yards'];
			    } else { $receiving_yds = false; }
			    if(isset($player->stats['kick_returns']['yards'])) {
			        $kick_yds = $player->stats['kick_returns']['yards'];
			    } else { $kick_yds = false; }
			    if(isset($player->stats['punt_returns']['yards'])) {
			        $punt_yds = $player->stats['punt_returns']['yards'];
			    } else { $punt_yds = false; }
			    if(isset($player->stats['receiving']['receptions'])) {
				    $receptions = $player->stats['receiving']['receptions'];
			    } else { $receptions = false; }
			    if(isset($player->stats['passing']['touchdowns'])){
				    $touchdowns = $player->stats['passing']['touchdowns'];
			    } else { $touchdowns = false; }
			    if(isset($player->stats['games_played'])) {
				    $games_played = $player->stats['games_played'];
			    } else { $games_played = false; }

			    $all_yds = $kick_yds + $receiving_yds + $rushing_yds + $punt_yds;

			}
           
           $picked_yet = $player->drafted($owner->id, $game->id);

			if(!$picked_yet) {
	            $players[] = [
		            "player_id" => $player->id,
					"first_name" => $player->abbr_name,
					"last_name" => $player->last_name,
					"position" => $player->position_name->description,
					"jersey" => $player->jersey,
					"team" => $player->team->name,
					"logo" => "https://static.nfl.com/static/site/img/logos/svg/teams/".$player->team->alias.".svg",
					"headshot" =>  $headshot,
					"opponent" => $player->opponent->name,
					"opponent_logo" => "https://static.nfl.com/static/site/img/logos/svg/teams/".$player->opponent->alias.".svg",
					"all_yards" => $all_yds,
					"punt" => $punt_yds,
					"kick" => $kick_yds,
					"receiving" => $receiving_yds,
					"rushing" => $rushing_yds,
					"receptions" => $receptions,
					"ptd" => $touchdowns,
					"games_played" => $games_played
				];
		
			}

        }
        
        
        
        
      return view('cms-frontend::games.list', compact('game','code','owner', 'players'));
*/

	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function joinGameManual(Request $request)
    {
        $game = $this->service->findByCode($request->game_code);
        
        
        $game_date = explode(' ', $game->game_at);
		$date = new Carbon;
		$lock_after = Carbon::parse($game_date[0].' '.$game->start_at);
			
// 		if($date > $lock_after) {
// 			return view('cms-frontend::games.closed');  
// 		}        
        
        
        
        
        if($game){
            return view('cms-frontend::games.show')->with('game', $game);              
        } else {
           return view('cms-frontend::pages.gamecode');    
        }
        
    }

    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function joinGame(Request $request)
    {
        $game = $this->service->findByCode($request->game_code);
        
        $game_date = explode(' ', $game->game_at);
		$date = new Carbon;
		$lock_after = Carbon::parse($game_date[0].' '.$game->start_at);
			
/*
		if($date > $lock_after) {
			return redirect('/');
		} 
*/       
        
        
        session([   
        'game_id' => $game->id,
        ]); 

        
        $result = $this->game_owners->findByOG(owner('owner_id'), $game->id)->first();
        
        if(!$result) {
            $attach_owner = new GameOwner;
            $attach_owner->owner_id = owner('owner_id');
            $attach_owner->game_id = $game->id;
            $attach_owner->save();
            session([   
                'locked' => 0,
            ]);
        } else {
            session([   
                'locked' => $result->locked,
            ]);
        }





        if(owner('locked') == 0){
            return redirect('/games/td');        
        } else {
            return redirect('/games/roster');
        }
        
    }
    
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function account()
    {
        $owner = $this->owner->find(owner('owner_id'));
        return view('cms-frontend::pages.account', compact('owner'));
        
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function accountSave(Request $request)
    {
        $owner = $this->owner->find(owner('owner_id'));
        return view('cms-frontend::pages.account', compact('owner'));   
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function touchDowns()
    {
	
        if(owner('locked') == 1){
            return redirect('/games/roster');
        }
        
        $game = $this->service->find(owner('game_id'));
        $codes = $this->code->findByGroupSubCode('nfl_game', 'tt')->get();
        $owner = $this->owner->find(owner('owner_id'));
        $next_link = "yards";


        return view('cms-frontend::games.pick', compact('game','codes','owner','next_link'));


    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function allYards()
    {
        if(owner('locked') == 1){
            return redirect('/games/roster');
        }
        
        $game = $this->service->find(owner('game_id'));
        $codes = $this->code->findByGroupSubCode('nfl_game', 'ay')->get();
        $owner = $this->owner->find(owner('owner_id'));
        $next_link = "receptions";
        return view('cms-frontend::games.pick', compact('game','codes','owner','next_link'));
        
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function receptions()
    {
        if(owner('locked') == 1){
            return redirect('/games/roster');
        }
        
        $game = $this->service->find(owner('game_id'));
        $codes = $this->code->findByGroupSubCode('nfl_game', 're')->get();
        $owner = $this->owner->find(owner('owner_id'));
        
        $roster = $this->roster->findGO(owner('game_id'), owner('owner_id'));
        

        if(count($roster) == 9){
            $next_link = "roster";        
        } else {
            $next_link = "td";
        }

//        $next_link = "roster"; 
        return view('cms-frontend::games.pick', compact('game','codes','owner','next_link'));
        
    }

	public function gameDraft($level)
    {
	    
	    if(owner('locked') == 1){
            return redirect('/games/roster');
        }
        
		$game = $this->service->find(owner('game_id'));
        $code = $this->code->findByCode($level)->first();
        $owner = $this->owner->find(owner('owner_id'));
        
        $roster = $this->roster->findGO(owner('game_id'), owner('owner_id'));
        
        
        foreach($game->active_players->whereIn('position', explode(',', $code->extra_1)) as $player) {
            
            if(isset($player->headshot->headshot)) {
	            $headshot = $player->headshot->headshot;
            } else {
	            $headshot = false;
            }

			if($player->stats) {
	
			    $rushing_yds = 0;
			    $receiving_yds = 0;
			    $kick_yds = 0;
			    $punt_yds = 0;
			    
			    if(isset($player->stats['rushing']['yards'])) {
			        $rushing_yds = $player->stats['rushing']['yards'];
			    } else { $rushing_yds = false; }
			    if(isset($player->stats['receiving']['yards'])) {
			        $receiving_yds = $player->stats['receiving']['yards'];
			    } else { $receiving_yds = false; }
			    if(isset($player->stats['kick_returns']['yards'])) {
			        $kick_yds = $player->stats['kick_returns']['yards'];
			    } else { $kick_yds = false; }
			    if(isset($player->stats['punt_returns']['yards'])) {
			        $punt_yds = $player->stats['punt_returns']['yards'];
			    } else { $punt_yds = false; }
			    if(isset($player->stats['receiving']['receptions'])) {
				    $receptions = $player->stats['receiving']['receptions'];
			    } else { $receptions = false; }
			    if(isset($player->stats['passing']['touchdowns'])){
				    $touchdowns = $player->stats['passing']['touchdowns'];
			    } else { $touchdowns = false; }
			    if(isset($player->stats['games_played'])) {
				    $games_played = $player->stats['games_played'];
			    } else { $games_played = false; }

			    $all_yds = $kick_yds + $receiving_yds + $rushing_yds + $punt_yds;

			}
           
           $picked_yet = $player->drafted(owner('owner_id'), $game->id);

			if(!$picked_yet) {
	            $players[] = [
		            "player_id" => $player->id,
					"first_name" => $player->abbr_name,
					"last_name" => $player->last_name,
					"position" => $player->position_name->description,
					"jersey" => $player->jersey,
					"team" => $player->team->name,
					"logo" => $player->team->logo,
					"headshot" =>  $headshot,
					"opponent" => $player->opponent->name,
					"opponent_logo" => $player->opponent->logo,
					"all_yards" => $all_yds,
					"punt" => $punt_yds,
					"kick" => $kick_yds,
					"receiving" => $receiving_yds,
					"rushing" => $rushing_yds,
					"receptions" => $receptions,
					"ptd" => $touchdowns,
					"games_played" => $games_played
				];
		
			}

        }
        
        
        
        
      return view('cms-frontend::games.list', compact('game','code','owner', 'players'));

	}


	public function gamePostDraft($level, $player_id)
    {
		$code = $this->code->findByCode($level)->first();
	    $owner_id = owner('owner_id');
        $game_id = owner('game_id');        
        $result = $this->roster->findLGO($level, $game_id, $owner_id);

        $payload = [
            'owner_id' => $owner_id,
            'game_id' => $game_id,
            'player_id' => $player_id,
            'level_id' => $level,
            'level' => $code->subcode
        ];
        
        if($result) {
            $update = true;
            $roster = $this->roster->update($result->id, $payload);
        } else {
            $update = false;
            $roster = $this->roster->create($payload);            
        }
	    
	    return redirect('/games/'.$code->expire);
	    
	}

	public function gameDraftRest($group)
    {
        $code = $this->code->findByGroupSubCode('nfl_game', $group)->first();
        
        DB::table('rosters')->where('game_id', '=', owner('game_id'))->where('owner_id', '=', owner('owner_id'))->where('level', '=', $group)->delete();
        
	    return redirect('/games/'.$code->expire);        
	}
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function leaderboard()
    {
        $game = $this->service->find(owner('game_id'));
        $this_game_owners = $this->game_owners->select('owners.id', 'owners.full_name', 'games_owners.score', 'games_owners.locked')->where('game_id', '=', owner('game_id'))->join('owners', 'owners.id', '=', 'games_owners.owner_id')->orderBy('games_owners.score', 'DESC')->get();
        return view('cms-frontend::games.leaderboard', compact('game','this_game_owners'));
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function leaderboardPngPage($game_id)
    {
        $game = $this->service->find($game_id);
        $this_game_owners = $this->game_owners->select('owners.full_name', 'games_owners.score')->where('game_id', '=', $game_id)->join('owners', 'owners.id', '=', 'games_owners.owner_id')->orderBy('games_owners.score', 'DESC')->get();

        return view('cms-frontend::games.leaderboardpng', compact('game','this_game_owners'));
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function leaderboardPNG($game_id)
    {
        $options = [
          'width' => 1280,
          'height' => 1280,
          'quality' => 50
        ];
        $conv = new \Anam\PhantomMagick\Converter();
        $conv->source('https://hfs.dashcg.com/games/leaderboardpngpage/'.$game_id)
             ->toPng($options)
             ->download('leaderboard.png');
        
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function rosterPngPage($game_id, $owner_id)
    {
        $game = $this->service->find($game_id);
        $codes_tt = $this->code->findByGroupSubCode('nfl_game', 'tt')->get();
        $codes_re = $this->code->findByGroupSubCode('nfl_game', 're')->get();
        $codes_ay = $this->code->findByGroupSubCode('nfl_game', 'ay')->get();
        
        $owner = $this->owner->find($owner_id);
        return view('cms-frontend::games.rosterpng', compact('game','codes_tt','codes_re','codes_ay','owner'));
                
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ownerRosterPNG()
    {
        $options = [
          'width' => 1280,
          'height' => 1280,
          'quality' => 50
        ];
        $conv = new \Anam\PhantomMagick\Converter();
        $conv->source('https://hfs.dashcg.com/games/rosterpngpage/'.owner('game_id').'/'.owner('owner_id'))
             ->toPng($options)
             ->download('myroster.png');
        
    }
    
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ownerRoster()
    {
        
        $game = $this->service->find(owner('game_id'));
        $codes_tt = $this->code->findByGroupSubCode('nfl_game', 'tt')->get();
        $codes_re = $this->code->findByGroupSubCode('nfl_game', 're')->get();
        $codes_ay = $this->code->findByGroupSubCode('nfl_game', 'ay')->get();
        
        $owner = $this->owner->find(owner('owner_id'));
        $next_link = "yards";
        
        session([   
            'roster' => 'locked', 
            'locked' => 1
        ]); 
        
        
        DB::table('games_owners')
            ->where('game_id', '=', owner('game_id'))
            ->where('owner_id', '=', owner('owner_id'))
            ->update(['locked' => 1]);
        
        return view('cms-frontend::games.roster', compact('game','codes_tt','codes_re','codes_ay','owner','next_link'));
        
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function opponentRoster($opponent_id)
    {
        
        $game = $this->service->find(owner('game_id'));
        
        $codes_tt = $this->code->findByGroupSubCode('nfl_game', 'tt')->get();
        $codes_re = $this->code->findByGroupSubCode('nfl_game', 're')->get();
        $codes_ay = $this->code->findByGroupSubCode('nfl_game', 'ay')->get();
        
        $owner = $this->owner->find($opponent_id);
        $next_link = "yards";
        
/*
        session([   
            'roster' => 'locked', 
            'locked' => 1
        ]); 
        
        
        DB::table('games_owners')
            ->where('game_id', '=', owner('game_id'))
            ->where('owner_id', '=', $opponent_id)
            ->update(['locked' => 1]);
*/
        
        return view('cms-frontend::games.roster', compact('game','codes_tt','codes_re','codes_ay','owner','next_link'));
        
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function draftAjax(Request $request)
    {

/*


        require('/home/dash_vps_hfs/api.htfsports.com/cms/modules/Players/Classes/SimpleHtmlDom.php');
        
        $espn_team_links = [

             'http://www.espn.com/nfl/team/_/name/dal/dallas-cowboys',


            'http://www.espn.com/nfl/team/_/name/nyg/new-york-giants',
            'http://www.espn.com/nfl/team/_/name/phi/philadelphia-eagles',
            'http://www.espn.com/nfl/team/_/name/wsh/washington-redskins',
            'http://www.espn.com/nfl/team/_/name/buf/buffalo-bills',
            'http://www.espn.com/nfl/team/_/name/mia/miami-dolphins',
            'http://www.espn.com/nfl/team/_/name/ne/new-england-patriots',
            'http://www.espn.com/nfl/team/_/name/ne/new-england-patriots',
            'http://www.espn.com/nfl/team/_/name/nyj/new-york-jets',
            'http://www.espn.com/nfl/team/_/name/chi/chicago-bears',
            'http://www.espn.com/nfl/team/_/name/det/detroit-lions',
            'http://www.espn.com/nfl/team/_/name/gb/green-bay-packers',
            'http://www.espn.com/nfl/team/_/name/min/minnesota-vikings',
            'http://www.espn.com/nfl/team/_/name/bal/baltimore-ravens',

            'http://www.espn.com/nfl/team/_/name/cin/cincinnati-bengals',
            'http://www.espn.com/nfl/team/_/name/cle/cleveland-browns',
            'http://www.espn.com/nfl/team/_/name/cle/cleveland-browns',            
            'http://www.espn.com/nfl/team/_/name/pit/pittsburgh-steelers',
            'http://www.espn.com/nfl/team/_/name/atl/atlanta-falcons',
            'http://www.espn.com/nfl/team/_/name/car/carolina-panthers',
            'http://www.espn.com/nfl/team/_/name/no/new-orleans-saints',
            'http://www.espn.com/nfl/team/_/name/tb/tampa-bay-buccaneers',
            'http://www.espn.com/nfl/team/_/name/hou/houston-texans',
            'http://www.espn.com/nfl/team/_/name/ind/indianapolis-colts',


            'http://www.espn.com/nfl/team/_/name/jax/jacksonville-jaguars',
            'http://www.espn.com/nfl/team/_/name/ten/tennessee-titans',
            'http://www.espn.com/nfl/team/_/name/ari/arizona-cardinals',
            'http://www.espn.com/nfl/team/_/name/lar/los-angeles-rams',
            'http://www.espn.com/nfl/team/_/name/sf/san-francisco-49ers',
            'http://www.espn.com/nfl/team/_/name/sea/seattle-seahawks',
            'http://www.espn.com/nfl/team/_/name/den/denver-broncos',
            'http://www.espn.com/nfl/team/_/name/kc/kansas-city-chiefs',
            'http://www.espn.com/nfl/team/_/name/lac/los-angeles-chargers',
            'http://www.espn.com/nfl/team/_/name/oak/oakland-raiders'


        ];
        
        foreach($espn_team_links as $team){
            
            $team_link = str_replace('_', 'roster/_', $team);

            
            $html = file_get_html($team_link);
            
            
            foreach($html->find('tr') as $row) {

	            $count=0;
                $player = array();
                $classes = explode(' ', $row->class);
                
               // print_r($classes);
                if(in_array("Table2__even", $classes)) {
	          
	            
	            foreach($row->find('a') as $cell) 
                
                $player[] = $cell->href;
                $player[] = $cell->plaintext;

                }

				try {
					foreach($row->find('img') as $cell) 
                
						$player[] = $cell->alt;

                

				} catch (Exception $e) {}
	     
				try {
					
					$player_link = $player[0];
					$player_link_parts = explode('/', $player_link);
					
					                
					

				} catch (Exception $e) {}
				
				$link_parts = explode('/', $team_link);
				$team_parts = explode('-', end($link_parts));
				$player[] =  end($team_parts);

	     
				try {
					$link_part_six = $player_link_parts[0];

					if($link_part_six == "http:") {
						$player[] = $player_link_parts[7];

					$image_link = 'https://www.espn.com/i/headshots/nfl/players/full/'.$player[5].'.png';

					$player_name = explode(' ', $player[2]);
					$new_image = new PlayerImage;
                    $new_image->first_name = $player_name[0];
                    $new_image->last_name = $player_name[1];
                    $new_image->espn_id = $player[5];
                    $new_image->team = end($team_parts);
                    $new_image->headshot = $player[3];
                    $new_image->save();

					echo 'Player ID: '.$player[5].'<br>';
					echo 'Name: '.$player[2].'<br>';
                
					echo '<hr>';







					}	

				} catch (Exception $e) {}
	     
				
					

                
            }
           
            sleep(1.5);

        }
*/




        $players = PlayerImage::all();
        

            foreach($players as $player){
                $real_player = $this->player->findByNameTeam($player->first_name, $player->last_name);
                foreach($real_player as $this_one) {
                    
                    if(isset($this_one->player_id)) {
                        $payload = [
                            'espn_id' => $player->espn_id
                        ];
                        $this->player->update($this_one->id, $payload);
                        echo $this_one->first_name;
                    }
                }
            }

        

        
/*



        $owner_id = owner('owner_id');
        $game_id = $request->game_id;
        $player_id = $request->player_id;
        $level_id = $request->level_id;
        
        $result = $this->roster->findLGO($level_id, $game_id, $owner_id);

        $payload = [
            'owner_id' => $owner_id,
            'game_id' => $game_id,
            'player_id' => $player_id,
            'level_id' => $level_id,
            'level' => $request->level
        ];
        
        if($result) {
            $update = true;
            $roster = $this->roster->update($result->id, $payload);
        } else {
            $update = false;
            $roster = $this->roster->create($payload);            
        }
        
        
        return ['success' => true, 'data' => $request->all(), 'owner_id' => $owner_id, 'result'=>$update];
*/




        
    }
    
   
}
