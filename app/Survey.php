<?php

namespace Lara;

use Auth;
use Illuminate\Support\Facades\Session;
use Lara\Http\Requests\SurveyRequest;
use Hash;

class Survey extends BaseSoftDelete
{
    protected $table = 'surveys';
    protected $fillable = array('title', 'description', 'deadline', 'password','is_private');

    /**
     * Get the corresponding person.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getPerson()
    {
        return $this->belongsTo('Lara\Person', 'creator_id', 'prsn_ldap_id');
    }

    /**
     * Get the corresponding person.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Person
     */
    public function creator()
    {
        return $this->belongsTo('Lara\Person', 'creator_id', 'id');
    }

    /**
     * Get the corresponding club.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Club
     */
    public function getClub()
    {
        return $this->club();
    }

    /**
     * Get the corresponding club.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Club
     */
    public function club()
    {
        return $this->creator->club();
    }
    
    public function section()
    {
        return $this->club->section();
    }

    /**
     * Get the corresponding questions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getQuestions()
    {
        return $this->hasMany('Lara\SurveyQuestion');
    }

    /**
     * Get the corresponding questions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function questions()
    {
        return $this->hasMany('Lara\SurveyQuestion');
    }


    /**
     * Get the corresponding Answers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getAnswers()
    {
        return $this->hasMany('Lara\SurveyAnswer');
    }

    /**
     * Get the corresponding Answers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers()
    {
        return $this->hasMany('Lara\SurveyAnswer');
    }

    /**
     * Create a survey model (but don't store it yet) from a SurveyRequest.
     * The request contains all the necessary information for the request.
     * @param SurveyRequest $request
     */
    public function makeFromRequest(SurveyRequest $request)
    {
        $this->creator_id = Auth::user()->person->id;
        $this->title = $request->title;
        $this->description = $request->description;
        $this->deadline = strftime("%Y-%m-%d %H:%M:%S", strtotime($request->deadlineDate . $request->deadlineTime));
        $this->is_anonymous = isset($request->is_anonymous);
        $this->is_private = isset($request->is_private);
        $this->show_results_after_voting = isset($request->show_results_after_voting);

        //if there is a password make a hash of it and save it
        if (!empty($request->password)
            && !empty($request->password_confirmation)
            && $request->password == $request->password_confirmation
        ) {
            $this->password = Hash::make($request->password);
        }
    }
}
