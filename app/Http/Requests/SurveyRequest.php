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
        $today = \Carbon\Carbon::now()->format('d-m-Y H:i:s');
        return [
            'title' => 'string|required|max:255',
            'description' => 'string|max:1500',
            'deadline' => "required|date_format:d-m-Y H:i:s|after:.$today.",
            'password' => 'string|confirmed',
            'questions' => 'array|required',
            'answer_options' => 'array',
            'required' => 'array'
        ];
    }
}
