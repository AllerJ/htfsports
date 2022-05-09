<?php

namespace Cms\Modules\Players\Models;

use Grafite\Cms\Models\CmsModel;

class Player extends CmsModel
{
    public $table = "players";

    public $primaryKey = "id";

    public $timestamps = true;

    public $fillable = [
        'active','player_id','first_name','last_name','abbr_name','jersey','weight','height','position','team_id','schedule_id','home_visitor','game_id','stats','espn_id','id', 'opponent', 'injury'
    ];

    public static $rules = [
        // create rules
    ];
    
    protected $casts = [
        'stats' => 'json',
    ];
    
    public function team()
    {
        return $this->hasOne('Cms\Modules\Teams\Models\Team', 'team_id', 'team_id');
    }

    public function schedule()
    {
        return $this->hasOne('Cms\Modules\Schedules\Models\Schedule', 'schedule_id', 'schedule_id');
    }
    
        public function opponent()
    {
        return $this->hasOne('Cms\Modules\Teams\Models\Team', 'team_id', 'opponent_id');
    }
    
    public function position_name()
    {
        return $this->hasOne('Cms\Modules\Codes\Models\Code', 'code', 'position')->where('codegroup', '=', 'position');
    }
    
    public function drafted($owner_id, $game_id)
    {
        return $this->hasOne('Cms\Modules\Rosters\Models\Roster', 'player_id', 'id')->where('rosters.owner_id', '=', $owner_id)->where('rosters.game_id', '=', $game_id)->first();        
    }
    
    public function headshot()
    {
        return $this->hasOne('Cms\Modules\Players\Models\PlayerImage', 'espn_id', 'espn_id');
    }
   
}