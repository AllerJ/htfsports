<?php

namespace Cms\Modules\Games\Models;

use Grafite\Cms\Models\CmsModel;

class GameTeam extends CmsModel
{
    public $table = "games_teams";

    public $primaryKey = "id";

    public $timestamps = true;
    


    public $fillable = [
        'game_id','team_id','schedule_id','stats','live_stats'
    ];

    public static $rules = [
        // create rules
    ];


    protected $casts = [
        'stats' => 'json',
        'live_stats' => 'json'
    ];

    public function info()
    {
        return $this->hasOne('Cms\Modules\Teams\Models\Team', 'team_id', 'team_id');
    }
    
    public function schedule()
    {
        return $this->hasOne('Cms\Modules\Schedules\Models\Schedule', 'schedule_id', 'schedule_id');
    }
    
    public function players($game_id)
    {
        return $this->hasMany('Cms\Modules\Players\Models\Player', 'team_id', 'team_id')->where('players.game_id', '=', $game_id)->orderBy('position')->orderBy('last_name');
    } 

    public function active_players($game_id)
    {
        return $this->hasMany('Cms\Modules\Players\Models\Player', 'team_id', 'team_id')->where('players.game_id', '=', $game_id)->where('players.active', '=', '1')->orderBy('last_name');
    } 
}
