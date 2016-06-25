<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;

class Revision_SurveyAnswerOption extends BaseRevision_Relation
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'revisions_survey_answer_options';

    /**
     * Get the corresponding SurveyAnswerOption.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getSurveyAnswerOption()
    {
        return $this->belongsTo('Lara\SurveyAnswerOption', 'object_id', 'id');
    }
}
