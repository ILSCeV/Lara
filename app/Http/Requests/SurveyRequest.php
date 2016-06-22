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
            'title' => 'string|required|max:255',
            'description' => 'string|max:1500',
            //'deadline' => 'date|required|after:Carbon::now' ??? deadline is dateTime not Date, maybe use RegEx
            'password' => 'string|confirmed',
            'questions' => 'array|required',
            'answer_options' => 'array',
            'required' => 'array'
        ];
    }
}
