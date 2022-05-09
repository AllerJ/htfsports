<?php

namespace Cms\Modules\Games\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Cms\Modules\Games\Models\Game;

class GameUpdateRequest extends FormRequest
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
        return Game::$rules;
    }
}
