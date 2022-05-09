<?php
    
if (! function_exists('isin')) {
    function isin()
    {
        
        if (session()->has('logged_in')) {
            return true;
        } else {
            return false;
        }
        
    }
}

if (! function_exists('owner')) {
    function owner($key)
    {
		   $owner = Auth::user();
    
		   session([   
                'logged_in' => true, 
                'owner_id' => $owner->id, 
                'full_name' => $owner->name, 
                'avatar' => $owner->avatar, 
                'username' => $owner->nickname,
                'roster' => 'open',
                'locked' => '0'
            ]);    
        
        if (session()->has($key)) {
            return session()->get($key);
        } else {
            return false;
        }
        
    }
}


if (! function_exists('stats')) {
    function stats($player_id, $game_id, $stat_type)
    {
        $stat = Cms\Modules\Stats\Models\Stat::selectRaw('sum(stat) as current_stat')->where('player_id', '=', $player_id)->where('game_id', '=', $game_id)->where('stat_type', '=', $stat_type)->first();

            $stat = $stat->current_stat;
            return $stat;
        
    }
}


if (! function_exists('player_draft')) {
    function player_draft($player_id, $game_id)
    {
        $stat = Cms\Modules\Rosters\Models\Roster::select('level_id')->where('player_id', '=', $player_id)->where('game_id', '=', $game_id)->groupBy('level')->get();


            return $stat;
        
    }
}

if (! function_exists('player_stat')) {
    function player_stat($player_id, $game_id, $stat_type)
    {
        $stat = Cms\Modules\Stats\Models\Stat::select('stat')->where('player_id', '=', $player_id)->where('game_id', '=', $game_id)->where('stat_type', '=', $stat_type)->first();

        if($stat) {
            return $stat->stat;
        }
    }
}

if (! function_exists('player_stat_manual')) {
    function player_stat_manual($player_id, $game_id, $stat_type)
    {
        $stat = Cms\Modules\Stats\Models\Stat::select('manual')->where('player_id', '=', $player_id)->where('game_id', '=', $game_id)->where('stat_type', '=', $stat_type)->first();

        if($stat) {
            return $stat->manual;
        }
    }
}

if (! function_exists('player_stat_add')) {
    function player_stat_add($player_id, $game_id, $stat_type, $the_stat)
    {
                         
        $roster = Cms\Modules\Rosters\Models\Roster::where('player_id', '=', $player_id)->where('game_id', '=', $game_id)->where('level', '=', $stat_type)->get();
        
        foreach($roster as $one_roster) {
        
	        if($one_roster->manual == "1") {
		        
		        if($one_roster->current_stat == $the_stat) {
	
			        $one_roster->current_stat = $the_stat;
			        $one_roster->manual = null;
			        $one_roster->save();
			        
		        }
		        
		        
	        }   else {
		        $one_roster->current_stat = $the_stat;
		        $one_roster->save();	        
	        } 
        
        }
                        
        $stat = Cms\Modules\Stats\Models\Stat::where('player_id', '=', $player_id)->where('game_id', '=', $game_id)->where('stat_type', '=', $stat_type)->first();

        if($stat) {

			if($stat->stat == $the_stat) {
	        	$changed_stat = 0;    
            } else {
	            $changed_stat = 1;
            }
            
            if($stat->manual == "1") {
	        
		        if($stat->stat == $the_stat) {
	
			        $stat->stat = $the_stat;
			        $stat->manual = null;
			        $stat->save();
			        
		        }
		        
	        }   else {
	            $stat->stat = $the_stat;
	            $stat->save();	        
	        } 
            
            return $changed_stat;

        } else {
            
            $new_stat = new Cms\Modules\Stats\Models\Stat;
            $new_stat->player_id = $player_id;
            $new_stat->game_id = $game_id;
            $new_stat->stat_type = $stat_type;
            $new_stat->stat = $the_stat;
            $new_stat->save();
            $changed_stat = 1;
            return $changed_stat;
            
        }
    }
}



if (! function_exists('geocode')) {
    function geocode($address)
    {
        // url encode the address
        $address = urlencode($address);
         
        // google map geocode api url
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=".env('GOOGLEMAPS_API');
     
        // get the json response
        $resp_json = file_get_contents($url);
         
        // decode the json
        $resp = json_decode($resp_json, true);
     
        // response status will be 'OK', if able to geocode given address 
        if($resp['status']=='OK'){
     
            // get the important data
            $lati = isset($resp['results'][0]['geometry']['location']['lat']) ? $resp['results'][0]['geometry']['location']['lat'] : "";
            $longi = isset($resp['results'][0]['geometry']['location']['lng']) ? $resp['results'][0]['geometry']['location']['lng'] : "";
            $formatted_address = isset($resp['results'][0]['formatted_address']) ? $resp['results'][0]['formatted_address'] : "";
             
            // verify if data is complete
            if($lati && $longi && $formatted_address){
             
                // put the data in the array
                $data_arr = array();            
                 
                array_push(
                    $data_arr, 
                        $lati, 
                        $longi, 
                        $formatted_address
                    );
                 
                return $data_arr;
                 
            }else{
                return false;
            }
             
        }
     
        else{
            echo "<strong>ERROR: {$resp['status']}</strong>";
            return false;
        }         
    }
}