<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;

class Revision_Survey extends BaseRevision_Relation
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'revisions_surveys';
    
    /**
     * Get the corresponding Survey.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getSurvey()
    {
        return $this->belongsTo('Lara\Survey', 'object_id', 'id');
    }
}
