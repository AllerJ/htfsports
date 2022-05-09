<?php

namespace Cms\Modules\Games\Models;

use Grafite\Cms\Models\CmsModel;

class Game extends CmsModel
{
    public $table = "games";

    public $primaryKey = "id";

    public $timestamps = true;
    
    protected $dates = ['game_at'];

    public $fillable = [
        'game_at','start_at','end_at','notes','artwork','game_code','venue_id'
    ];

    public static $rules = [
        // create rules
    ];

    public function author()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function teams()
    {
        return $this->hasmany('Cms\Modules\Games\Models\GameTeam', 'game_id', 'id');
    }

    public function rosters()
    {
        return $this->hasmany('Cms\Modules\Rosters\Models\Roster', 'game_id', 'id');
    }
    
    public function players()
    {
        return $this->hasmany('Cms\Modules\Players\Models\Player', 'game_id', 'id');
    }
    
    public function active_players()
    {
        return $this->hasMany('Cms\Modules\Players\Models\Player', 'game_id', 'id')->where('players.active', '=', '1')->orderBy('last_name');
    }

    public function venue()
    {
        return $this->hasOne('Cms\Modules\Venues\Models\Venue', 'id', 'venue_id');
    }
    
    public function drafted($owner_id, $player_id)
    {
        return $this->hasOne('Cms\Modules\Rosters\Models\Roster', 'game_id', 'id')->where('owner_id', '=', $owner_id)->where('player_id', '=', $player_id);        
    }

    public function all_drafted($owner_id, $level_id)
    {
        return $this->hasOne('Cms\Modules\Rosters\Models\Roster', 'game_id', 'id')->where('owner_id', '=', $owner_id)->where('level_id', '=', $level_id);        
    }
}


