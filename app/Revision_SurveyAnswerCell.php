<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;

class Revision_SurveyAnswerCell extends BaseRevision_Relation
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'revisions_survey_answer_cells';

    /**
     * Get the corresponding SurveyAnswerCell.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getSurveyAnswerCell()
    {
        return $this->belongsTo('Lara\SurveyAnswerCell', 'object_id', 'id');
    }
}
