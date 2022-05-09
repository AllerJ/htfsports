<?php

namespace Cms\Modules\Rosters\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Cms\Modules\Rosters\Models\Roster;

class RosterCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Auth::user()) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return Roster::$rules;
    }
}
