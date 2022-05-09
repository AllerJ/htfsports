<?php

namespace Cms\Modules\Stats\Models;

use Grafite\Cms\Models\CmsModel;

class Stat extends CmsModel
{
    public $table = "stats";

    public $primaryKey = "id";

    public $timestamps = true;

    public $fillable = [
        'game_id', 'player_id', 'stat_type', 'stat', 'manual'
    ];

    public static $rules = [
        // create rules
    ];

}
