<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;

class Revision_SurveyAnswer extends BaseRevision_Relation
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'revisions_survey_answers';

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
