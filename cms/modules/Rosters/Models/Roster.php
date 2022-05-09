<?php

namespace Cms\Modules\Rosters\Models;

use Grafite\Cms\Models\CmsModel;

class Roster extends CmsModel
{
    public $table = "rosters";

    public $primaryKey = "id";

    public $timestamps = true;

    public $fillable = [
        'game_id','league_id','owner_id','level_id','player_id','level'
    ];

    public static $rules = [
        // create rules
    ];
    
    public function player()
    {
        return $this->hasOne('Cms\Modules\Players\Models\Player', 'id', 'player_id');
    }
    
    public function drafted()
    {
        return $this->hasMany('Cms\Modules\Rosters\Models\Roster', 'id', 'owner_id');        
    }
    
    public function  codes()
    {
        return $this->hasOne('Cms\Modules\Codes\Models\Code', 'code', 'level_id');
    }

}
