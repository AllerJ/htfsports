<?php

namespace Cms\Modules\Owners\Models;

use Grafite\Cms\Models\CmsModel;

class Owner extends CmsModel
{
    public $table = "owners";

    public $primaryKey = "id";

    public $timestamps = true;

    public $fillable = [
        'avatar','email','phone','username','password','insta_id','insta_token','full_name','firebase','lastlat','lastlon','gameid'
    ];

    public static $rules = [
        // create rules
    ];


    public function roster()
    {
        return $this->hasMany(
            'Cms\Modules\Rosters\Models\Roster',
                        'owner_id', // Foreign key on users table...
            'id' // Local key on countries table...
            
        );
    }
    public function game_owner_info()
    {
        return $this->hasMany(
            'Cms\Modules\Games\Models\GameOwner',
                        'owner_id', // Foreign key on users table...
            'id' // Local key on countries table...
            
        );
    }
}
