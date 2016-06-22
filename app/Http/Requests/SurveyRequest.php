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
        //implement later: check if user has permission to create/edit -> has LDAP_Id?
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'string|required|size:255',
            'description' => 'string|size:1500',
            //'deadline' => 'date, after:Carbon::now' ??? deadline is dateTime not Date, maybe use RegEx
            'is_private' => 'boolean',
            'is_anonymous' => 'boolean',
            'show_results_after_voting' => 'boolean',
            'password' => 'string|confirmed',
            'questions' => 'array|required',
            'answer_options' => 'array',
            'required' => 'array'
        ];
    }
}
