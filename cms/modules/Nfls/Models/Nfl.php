<?php

namespace Cms\Modules\Nfls\Models;

use Grafite\Cms\Models\CmsModel;

class Nfl extends CmsModel
{
    public $table = "nfls";

    public $primaryKey = "id";

    public $timestamps = true;

    public $fillable = [
        // Nfl table data
    ];

    public static $rules = [
        // create rules
    ];

}
