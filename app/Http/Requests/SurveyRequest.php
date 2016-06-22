<?php

namespace Lara\Http\Requests;

use Lara\Http\Requests\Request;
use Carbon\Carbon;

class SurveyRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $today = \Carbon\Carbon::now();
        $rules = [
            'title' => 'string|required|max:255',
            'description' => 'string|max:1500',
            'deadline' => "required|date_format:d-m-Y H:i:s|after:.$today.",
            'is_private' => 'in:null,1',
            'is_anonymous' => 'in:null,1',
            'show_results_after_voting' => 'in:null,1',
            'password' => 'string|confirmed',

            'questions' => 'array|required',
            'questions.*' => 'string|min:3',

            'answer_options' => 'array',

            'required' => 'array',
            'required.*' => 'in:1,2,3',
        ];
        return $rules;
    }
}
