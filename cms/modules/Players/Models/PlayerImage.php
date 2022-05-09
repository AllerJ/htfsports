<?php

namespace Cms\Modules\Players\Models;

use Grafite\Cms\Models\CmsModel;

class PlayerImage extends CmsModel
{
    public $table = "players_images";

    public $primaryKey = "id";

    public $timestamps = true;

    public $fillable = [
        'first_name','last_name','team','headshot','espn_id'
    ];

}