<?php

namespace Cms\Modules\Schedules\Models;

use Grafite\Cms\Models\CmsModel;

class Schedule extends CmsModel
{
    public $table = "schedules";

    public $primaryKey = "id";
    

    public $timestamps = true;
    
    protected $dates = ['schedule_at'];

    public $fillable = [
        // Schedule table data
        'schedule_id','schedule_at','venue','home_id','visitor_id','league_id','live_stats', 'nfl_stats', 'live_stats', 'nfl_link'
    ];

    public static $rules = [
        // create rules
    ];

    protected $casts = [
        'live_stats' => 'json',
        'nfl_stats' => 'json',
    ];


    public function homeTeam()
    {
        return $this->hasOne('Cms\Modules\Teams\Models\Team', 'team_id', 'home_id');
    }

    public function visitorTeam()
    {
        return $this->hasOne('Cms\Modules\Teams\Models\Team', 'team_id', 'visitor_id');
    }

}
