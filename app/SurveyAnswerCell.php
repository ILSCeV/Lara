<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;

class SurveyAnswerCell extends Model
{
    protected $table ='survey_answer_cell';
    protected $fillable = array('content');

    /**
     * Get the corresponding Answer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getAnswer()
    {
        return $this->belongsTo('Lara\SurveyAnswer', 'survey_answer_id', 'id');
    }
}
