<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;

class Revision_SurveyQuestion extends BaseRevision_Relation
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'revisions_survey_questions';

    /**
     * Get the corresponding SurveyQuestion.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getSurveyQuestion()
    {
        return $this->belongsTo('Lara\SurveyAnswerQuestion', 'object_id', 'id');
    }
}
