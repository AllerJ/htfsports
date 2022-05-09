<?php

namespace Cms\Modules\Games\Models;

use Grafite\Cms\Models\CmsModel;

class GameMessage extends CmsModel
{
    public $table = "games_messages";

    public $primaryKey = "id";

    public $timestamps = true;
    


    public $fillable = [
        'game_id','owner_id','message'
    ];

    public static $rules = [
        // create rules
    ];

    
    public function game()
    {
        return $this->hasOne('Cms\Modules\Games\Models\Game');
    }
    
    public function owner()
    {
        return $this->hasOne('Cms\Modules\Owners\Models\Owner', 'id', 'owner_id');
    }

}
