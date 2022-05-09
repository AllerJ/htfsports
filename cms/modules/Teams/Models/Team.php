<?php

namespace Cms\Modules\Teams\Models;

use Grafite\Cms\Models\CmsModel;

class Team extends CmsModel
{
    public $table = "teams";

    public $primaryKey = "id";

    public $timestamps = true;

    public $fillable = [
        // Team table data
        'team_id','name','market','alias','division','conference','color','color_second','logo','league_id','season'
    ];

    public static $rules = [
        // create rules
    ];

  
}
