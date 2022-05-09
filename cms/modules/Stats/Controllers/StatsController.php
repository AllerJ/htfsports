<?php

namespace Cms\Modules\Stats\Controllers;

use DB;
use Cms;
use CryptoService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cms\Modules\Games\Services\GameService;
use Cms\Modules\Players\Services\PlayerService;
use Cms\Modules\Codes\Services\CodeService;
use Cms\Modules\Stats\Services\StatService;
use Cms\Modules\Rosters\Services\RosterService;
use Cms\Modules\Rosters\Models\Roster;
use Cms\Modules\Owners\Models\Owner;
use Cms\Modules\Schedules\Services\ScheduleService;
use Cms\Modules\Games\Models\GameOwner;
use Cms\Modules\Stats\Requests\StatCreateRequest;
use Cms\Modules\Stats\Requests\StatUpdateRequest;

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use Carbon\Carbon;

class StatsController extends Controller
{
    public function __construct(StatService $statService, GameService $gameService, PlayerService $playerService, CodeService $codeService, RosterService $rosterService, Roster $roster, Owner $owners, GameOwner $gameowners, ScheduleService $scheduleService)
    {
        $this->service = $statService;
        $this->games = $gameService;
        $this->players = $playerService;
        $this->codes = $codeService;
        $this->roster_service = $rosterService;
        $this->roster = $roster;
        $this->owner = $owners;
        $this->gameowner = $gameowners;
        $this->schedule = $scheduleService;
    }


public function scrape($season) {
	echo "hi";
}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

	    
/*
	    
	    
	    $optionBuilder = new OptionsBuilder();
		$optionBuilder->setTimeToLive(60*20);
		
		$dataBuilder = new PayloadDataBuilder();
		$dataBuilder->addData(['action' => 'score']);
		
		$option = $optionBuilder->build();
		$notification = $notificationBuilder->build();
		$data = $dataBuilder->build();
		
		$token = ?;
		
		print_r($downstreamResponse = FCM::sendTo($token, $option, null, $data));
		
		$downstreamResponse->numberSuccess();
		$downstreamResponse->numberFailure();
		$downstreamResponse->numberModification();
		
		//return Array - you must remove all this tokens in your database
		$delete = $downstreamResponse->tokensToDelete();
		
		//return Array (key : oldToken, value : new token - you must change the token in your database )
		$modify = $downstreamResponse->tokensToModify();
		
		//return Array - you should try to resend the message to the tokens in the array
		$response = $downstreamResponse->tokensToRetry();
*/



/*

	    
	    $stats = $this->service->paginated();
        return view('stats::stats.index')
            ->with('pagination', $stats->render())
            ->with('stats', $stats);
      
*/      

    }

    /**
     * Display a listing of the resource searched.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $stats = $this->service->search($request->search);
        return view('stats::stats.index')
            ->with('term', $request->search)
            ->with('pagination', $stats->render())
            ->with('stats', $stats);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('stats::stats.create');
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function enter($game_id)
    {
        $codes = $this->codes->findByCodeGroup('stat')->get();
        $game = $this->games->find($game_id);
        
        $players = $this->roster_service->findGameNoDupe($game_id);
        
        return view('stats::stats.create', compact('game', 'codes', 'players'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function autocomplete(Request $request, $game_id)
    {
    
        $search = $this->players->autocomplete($request->q, $game_id);
        $players = array();
        foreach($search as $result) {
            $players[] = [
                $result->full_name,
                $result->id
            ];
        }
    
        //return $players;
        
        return response()->json($players);

     
     
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\StatCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StatCreateRequest $request)
    {
	    $newStat = false;
	    $newScore = false;
        if($request->stat != "") {
            
            $all_game_owners = $this->owner->where('gameid', '=', $request->game_id)->get();
            
            $player_stat = $this->service->findByPGT($request->player_id, $request->game_id, $request->stat_type);


            if(count($player_stat) > 0) {
                $player_stat->stat = $request->stat;
                $player_stat->manual = "1";
                $player_stat->save();
                $newStat = true;
                $stat_message = "Statistic Updated!";
            } else {
	            
	            $payload = $request->except('_token');
	            $payload['manual'] = "1";
                $result = $this->service->create($payload);
                $newStat = true;
                $stat_message = "Statistic Added!";

            }

            // UPDATE OWNERS ROSTER WITH COMBINED STATISTIC
            $this->roster->where('game_id', '=', $request->game_id)
                          ->where('player_id', '=', $request->player_id)
                          ->where('level_id', 'LIKE', $request->stat_type.'%')
                          ->update(['current_stat' => $request->stat, 'manual' => '1']);
    
            $owners = $this->gameowner->where('game_id', '=', $request->game_id)->get();
            
            foreach($owners as $owner) {
                $total_score = 0;
                $owner_roster = $this->roster->where('game_id', '=', $request->game_id)
                                            ->where('owner_id', '=', $owner->owner_id)
                                            ->get();
                                            
                            
                foreach($owner_roster as $player){
                    $needs = $player->codes->extra_2;
                    
                    $code_info = $this->codes->findByCode($player->level_id)->first();
                    
                    
                    if($player->current_stat >= $needs) {
                        $total_score = $total_score + $code_info->more;
                    }
                    
                    if($total_score != $owner->score){
	                    $owner->score = $total_score;
	                    $owner->save();
	                    $newScore = true;	                    
                    }
                    
                }
                
            }


            if($newStat == true && $newScore == false) {
				$optionBuilder = new OptionsBuilder();
				$optionBuilder->setTimeToLive(60*20);
				
				$dataBuilder = new PayloadDataBuilder();
				$dataBuilder->addData(['action' => 'score']);
				
				$notificationBuilder = new PayloadNotificationBuilder('Leaderboard Update');
				$notificationBuilder->setBody(
				'Stats have changed. Check out your roster to see what changed!'
				)->setSound('default');
				
				$option = $optionBuilder->build();
				$notification = $notificationBuilder->build();
				$data = $dataBuilder->build();
				
				foreach($all_game_owners as $one_owner) {
					if($one_owner->firebase != "") {
						$token = $one_owner->firebase;				
// 						$downstreamResponse = FCM::sendTo($token, $option, null, $data);						
					}
				}	
			}


			if($newScore == true) {
				$optionBuilder = new OptionsBuilder();
				$optionBuilder->setTimeToLive(60*20);
				
				$dataBuilder = new PayloadDataBuilder();
				$dataBuilder->addData(['action' => 'score']);
				
				$notificationBuilder = new PayloadNotificationBuilder('Leaderboard Update');
				$notificationBuilder->setBody(
				'Scores have changed. Check out the Leaderboard to see your rank!'
				)->setSound('default');
				
				$option = $optionBuilder->build();
				$notification = $notificationBuilder->build();
				$data = $dataBuilder->build();
				
				foreach($all_game_owners as $one_owner) {
					if($one_owner->firebase != "") {
						$token = $one_owner->firebase;				
				//		 						$downstreamResponse = FCM::sendTo($token, $option, $notification, $data);						
					}
				}	
			}




            return ['success' => true, 'message' => $stat_message];

        }

    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getStats($game_id = 84)
    {
		echo "hi MSF Players";
		$game_id = 84;

        $newScore = false;
        $newStat = false;
        $newAllStat = 0;
		$statUpdate = [];
        
        date_default_timezone_set('America/New_York');
                                                                     
        $time = date('H:i:s');
		$minute = date('i');
        $hour = date('H');
		$search_today = date("Y-m-d 00:00:00");        
        $today = date("Y-m-d H:i:s");
		
        $games = $this->games->findByDate($search_today);
		
		foreach($games as $game){
			
			$game_id = $game->id;
			$game_at = explode(' ', $game->game_at);
			$game_start = $game->start_at;
			$game_end = $game->end_at;
			
			$start_now = \DateTime::createFromFormat('Y-m-d g:i A', $game_at[0].' '.$game_start);
			$end_now = \DateTime::createFromFormat('Y-m-d g:i A', $game_at[0].' '.$game_end);
			
			$start = $start_now->format('Y-m-d H:i:s');
			$end = $end_now->format('Y-m-d H:i:s');
		
	        if($today >= $start && $today < $end && $minute % 5 == 0) {
	
				$file =  dirname(__FILE__) . '/msf.txt';        
		        file_put_contents($file, $time.' - '. $game_id . ' => ' . $game->game_code . "\n", FILE_APPEND | LOCK_EX);
			   	
		        $all_game_owners = $this->owner->where('gameid', '=',$game_id)->get();
		
		        foreach($game->teams as $teams){
		            $scheduleArray[] = $teams->schedule_id;
		        }
				
		        foreach(array_unique($scheduleArray) as $unique_schedule_id){
		
					$ch = curl_init();
			  		curl_setopt($ch, CURLOPT_URL, "https://api.mysportsfeeds.com/".config('app.msf_ver')."/pull/nfl/".config('app.msf_year')."-".config('app.msf_season')."/games/".$unique_schedule_id."/boxscore.json");
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_ENCODING, "gzip");
					curl_setopt($ch, CURLOPT_HTTPHEADER, [
						"Authorization: Basic " . config('app.msf_api')
					]);
					$resp = curl_exec($ch);
					if (!$resp) {
						echo 'Error: No Stats For Game Yet';
					} else {
						$schedules = json_decode($resp);
						$schedule = $this->schedule->byId($unique_schedule_id)->first();            
			            $schedule->live_stats = $schedules;          
			            $schedule->save();
					}
					curl_close($ch);
		            sleep(6);
		        }
					
		        $players = $this->roster_service->findByGame($game_id);
					        
		        foreach($players as $player) {
		
		            $team_data = $this->games->findByGameTeam($game_id, $player->player->team_id);
		            $schedule = $this->schedule->byId($team_data->schedule_id)->first(); 
		            $team_stats = $schedule->live_stats; 
		            
		            if($schedule->live_stats != "") {
			           	$yards = 0;
			            $tds = 0;
			            $recs = 0;            
			            
			            $home_away = ['away', 'home'];
			
			            foreach($home_away as $team){
			            
				            foreach($team_stats['stats'][$team]['players'] as $player_live_stat){
					            
					            if(isset($player_live_stat['playerStats'][0]['rushing'])) {		    
			                        switch($player_live_stat['player']['id'])
			                        {
			                            case $player->player->player_id:
			                                $yards = $yards + $player_live_stat['playerStats'][0]['rushing']['rushYards'];
		                                break;
			                        } 
				                }
				                
				                if(isset($player_live_stat['playerStats'][0]['receiving'])) {	
			                        switch($player_live_stat['player']['id'])
			                        {
			                            case $player->player->player_id:
			                                $yards = $yards + $player_live_stat['playerStats'][0]['receiving']['recYards'];
			                                $recs = $player_live_stat['playerStats'][0]['receiving']['receptions'];
			                                if($player->level == "re") {
			                                    $newAllStat = player_stat_add($player->player_id, $game_id, $player->level, $recs); 
			                                    $statUpdate[] = $newAllStat;
			                                }
			                            break;
			                        }		                     
				                }
				                
				                if(isset($player_live_stat['playerStats'][0]['kickoffReturns'])) {		    
			                        switch($player_live_stat['player']['id'])
			                        {
			                            case $player->player->player_id:
			                                $yards = $yards + $player_live_stat['playerStats'][0]['kickoffReturns']['krYds'];
			                            break;
			                        }
				                }
			 
			 	                if(isset($player_live_stat['playerStats'][0]['puntReturns'])) {		    
			                        switch($player_live_stat['player']['id'])
			                        {
			                            case $player->player->player_id:
			                                $yards = $yards + $player_live_stat['playerStats'][0]['puntReturns']['prYds'];
			                            break;
			                        }
				                }
			 
								if(isset($player_live_stat['playerStats'][0]['passing'])) {		    
			                        switch($player_live_stat['player']['id'])
			                        {
			                            case $player->player->player_id:
			                               $tds = $player_live_stat['playerStats'][0]['passing']['passTD'];
			                                if($player->level == "tt") {
			                                   $newAllStat = player_stat_add($player->player_id, $game_id, $player->level, $tds); 
			                                    $statUpdate[] = $newAllStat;
			                                }
			                            break;
			                        }
				                }
			            
				            }	            
		
			            }
		
			            switch ($player->level) {
			                case 'ay':
			                    $newAllStat = player_stat_add($player->player_id, $game_id, $player->level, $yards);
			                    $statUpdate[] = $newAllStat;
	
			                    break;
			            }
			          
			            
		            }
		            
		        }
					
		        $owners = $this->gameowner->where('game_id', '=', $game_id)->get();
					        
		        foreach($owners as $owner) {
		        	$newScore = false;
		            $total_score = 0;
		            $owner_roster = $this->roster->where('game_id', '=', $game_id)
		                                        ->where('owner_id', '=', $owner->owner_id)
		                                        ->get();
		                                        
		                        
		            foreach($owner_roster as $player){
		                $needs = $player->codes->extra_2;
		                
		                $code_info = $this->codes->findByCode($player->level_id)->first();
		                
		                if($player->current_stat >= $needs) {
		                    $total_score = $total_score + $code_info->more;
		                }
		                
		                if($total_score > $owner->score){
		                    $newScore = true;
		                    $owner->score = $total_score;
		                    $owner->save();
		                }   
		            }   
		        }
					        
				$optionBuilder = new OptionsBuilder();
				$optionBuilder->setTimeToLive(60*20);
				
				$dataBuilder = new PayloadDataBuilder();
				$dataBuilder->addData(['action' => 'score']);
				
				$notificationBuilder = new PayloadNotificationBuilder('Leaderboard Update');
				$notificationBuilder->setBody(
				'Scores have changed. Check out the Leaderboard to see your rank!'
				)->setSound('default');
				
				$notificationStatBuilder = new PayloadNotificationBuilder('Stats Update');
				$notificationStatBuilder->setBody(
				'Stats have updated, check your roster to see how your team is doing!'
				)->setSound('default');
				
				
				$option = $optionBuilder->build();
				$notification = $notificationBuilder->build();
				$statNotification = $notificationStatBuilder->build();
				
				$data = $dataBuilder->build();
				
			
				foreach($all_game_owners as $one_owner) {
					if($one_owner->firebase != ""  && $one_owner->game_owner_info[0]->locked == "1") {
						
						$token = $one_owner->firebase;				
						if($newScore == true) {
							$downstreamResponse = FCM::sendTo($token, $option, $notification, $data);	
						}
						if($today >= $start && $today < $end && $minute % 10 == 0) {
							if(array_sum($statUpdate) > 0) {
								$downstreamResponse = FCM::sendTo($token, $option, $statNotification, $data);
							}	
						}					
					}
				}
	/*
					        foreach($owners as $owner) {
					            $total_score = 0;
					            $owner_roster = $this->roster->where('game_id', '=', $game_id)
					                                        ->where('owner_id', '=', $owner->owner_id)
					                                        ->get();
					            
								$owner_original = $owner->score;
					            
					            
												            
					            foreach($owner_roster as $player){
					                $needs = $player->codes->extra_2;
					                
					                $code_info = $this->codes->findByCode($player->level_id)->first();
					                
					                if($player->current_stat >= $needs) {
					                    $total_score = $total_score + $code_info->more;
					                }
					                
					                if($total_score != $owner->score){
	 
					                    $owner->score = $total_score;
					                    $owner->save();
						               
					                }   
					                
					            }   
	
					            if ($owner_original != $total_score) {
						                $newScore = true;
					            }
					        }
					        
							$optionBuilder = new OptionsBuilder();
							$optionBuilder->setTimeToLive(60*20);
							
							$dataBuilder = new PayloadDataBuilder();
							$dataBuilder->addData(['action' => 'score']);
							
							$notificationBuilder = new PayloadNotificationBuilder('Leaderboard Update');
							$notificationBuilder->setBody(
							'Scores have changed. Check out the Leaderboard to see your rank!'
							)->setSound('default');
							
							$notificationStatBuilder = new PayloadNotificationBuilder('Stats Update');
							$notificationStatBuilder->setBody(
							'Stats have updated, check your roster to see how your team is doing!'
							)->setSound('default');
							
							
							$option = $optionBuilder->build();
							$notification = $notificationBuilder->build();
							$statNotification = $notificationStatBuilder->build();
							
							$data = $dataBuilder->build();
							
						
							foreach($all_game_owners as $one_owner) {
								if($one_owner->firebase != ""  && $one_owner->game_owner_info[0]->locked == "1") {
									
									$token = $one_owner->firebase;				
									if($newScore == true) {
	 									$downstreamResponse = FCM::sendTo($token, $option, $notification, $data);	
									}
									if($today >= $start && $today < $end && $minute % 9 == 0) {
										if(array_sum($statUpdate) > 0) {
	 										$downstreamResponse = FCM::sendTo($token, $option, $statNotification, $data);
										}	
									}					
								}
							}	
				
	*/
							
							
			}
		}			
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
        return view('stats::stats.show')->with('stat', $stat);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $stat = $this->service->find($id);
        return view('stats::stats.edit')->with('stat', $stat);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\StatUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StatUpdateRequest $request, $id)
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
            return redirect(config('cms.backend-route-prefix', 'cms').'/stats');
        }

        Cms::notification('Failed to delete', 'warning');
        return redirect(config('cms.backend-route-prefix', 'cms').'/stats');
    }
}
