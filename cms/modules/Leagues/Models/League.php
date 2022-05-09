<?php

namespace Cms\Modules\Leagues\Models;

use Grafite\Cms\Models\CmsModel;

class League extends CmsModel
{
    public $table = "leagues";

    public $primaryKey = "id";

    public $timestamps = true;

    public $fillable = [
        // League table data
    ];

    public static $rules = [
        // create rules
    ];

}
