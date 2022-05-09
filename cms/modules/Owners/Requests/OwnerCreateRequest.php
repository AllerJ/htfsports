<?php

namespace Cms\Modules\Owners\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Cms\Modules\Owners\Models\Owner;

class OwnerCreateRequest extends FormRequest
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
        return Owner::$rules;
    }
}
