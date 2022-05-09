<?php

namespace Cms\Modules\Codes\Models;

use Grafite\Cms\Models\CmsModel;

class Code extends CmsModel
{
    public $table = "codes";

    public $primaryKey = "id";

    public $timestamps = true;

    public $fillable = [
        // Code table data
        'code', 'codegroup', 'description','extra_1','extra_2','extra','alt','order','expire','subcode'
    ];

    public static $rules = [
        // create rules
    ];

}
