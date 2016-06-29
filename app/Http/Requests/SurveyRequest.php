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
        $today_date = \Carbon\Carbon::now()->toDateString();
        $rules = [
            'title' => 'string|required|max:255',
            'description' => 'string|max:1500',
            'deadlineDate' => "required|date_format:Y-m-d|after:.$today_date.",
            'deadlineTime' => "required|date_format:H:i:s",
            'password' => 'string|confirmed',

            'questions' => 'array|required',
            'questions.*' => 'string|required|min:3',

            'answer_options' => 'array',
            'answer_options.*.*' => 'string|min:3',

            'type' => 'array|required',
            'type.*' => 'in:1,2,3|required',
        ];

        $questions_type = $this->request->get('type');
        foreach($questions_type as $key => $question_type) {
            //todo: unset empty answer_options (actual bug) or this validation fails when question #1 is dropdown
            //todo: write german custom error message below
            if ($question_type == 3) {
                $rules['answer_options.'.$key] = 'required';
            }
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [];

        foreach($this->request->get('questions') as $key => $val) {
            $messages['questions.'.$key.'.string'] = 'Die Frage #'.($key+1).'muss eine Zeichenkette sein.';
            $messages['questions.'.$key.'.min'] = 'Die Frage #'.($key+1).' muss mindestens :min Zeichen enthalten.';
            $messages['questions.'.$key.'.required'] = 'Die Frage #'.($key+1).' wurde leer gelassen. Bitte fülle sie aus oder lösche sie!';
        }

        foreach($this->request->get('type') as $key => $val) {
            $messages['type.'.$key.'.in'] = 'Für die Frage #'.($key+1).' wurde ein fehlerhafter Fragetyp ausgewählt!';
            $messages['type.'.$key.'.required'] = 'Für die Frage #'.($key+1).' wurde kein Fragetyp ausgewählt!';
        }

        return $messages;
    }
}
