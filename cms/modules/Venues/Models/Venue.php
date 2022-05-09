<?php

namespace Cms\Modules\Venues\Models;

use Grafite\Cms\Models\CmsModel;

class Venue extends CmsModel
{
    public $table = "venues";

    public $primaryKey = "id";

    public $timestamps = true;

    public $fillable = [
        'name','logo','address','city','zip','state','lat','lon'    
    ];

    public static $rules = [
        // create rules
    ];

}
