<?php

namespace Cms\Modules\Venues\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Cms\Modules\Venues\Models\Venue;

class VenueUpdateRequest extends FormRequest
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
        return Venue::$rules;
    }
}
