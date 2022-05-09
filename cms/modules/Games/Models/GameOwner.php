<?php

namespace Cms\Modules\Games\Models;

use Grafite\Cms\Models\CmsModel;

class GameOwner extends CmsModel
{
    public $table = "games_owners";

    public $primaryKey = "id";

    public $timestamps = true;
    


    public $fillable = [
        'game_id','owner_id','score','locked'
    ];

    public static $rules = [
        // create rules
    ];

    public function findByOG($owner_id, $game_id)
    {
        return $this->where('game_id', '=', $game_id)->where('owner_id', '=', $owner_id);
    }
    
    public function roster($owner_id)
    {
        return $this->hasMany(
                            'Cms\Modules\Rosters\Models\Roster',
                            'game_id',
                            'id');
    }
    
    public function owner()
    {
        return $this->hasOne('Cms\Modules\Owners\Models\Owner', 'id', 'game_id');
    }

}
