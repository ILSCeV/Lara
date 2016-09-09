<?php

namespace Lara;

class Survey extends BaseSoftDelete
{
    protected $table ='surveys';
    protected $fillable = array('title', 'description', 'deadline', 'password');

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
     * Get the corresponding club.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getClub()
    {
        return $this->belongsTo('Lara\Club', 'club_id', 'id');
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
}
