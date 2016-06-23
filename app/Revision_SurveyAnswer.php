<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;

class Revision_SurveyAnswer extends Model
{
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
