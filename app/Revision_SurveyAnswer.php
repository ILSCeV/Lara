<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;

class Revision_SurveyAnswer extends Model
{

    public $timestamps = false;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'revisions_survey_answers';

    /**
     * The database columns used by the model.
     * This attributes are mass assignable.
     *
     * @var array
     */
    protected $fillable = array();


    /**
     * Get the corresponding Revision.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getRevision()
    {
        return $this->belongsTo('Lara\Revision', 'revision_id', 'id');
    }

    /**
     * Get the corresponding SurveyAnswer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getSurveyAnswer()
    {
        return $this->belongsTo('Lara\SurveyAnswer', 'object_id', 'id');
    }
}
