<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;

class Revision_Survey extends Model
{

    public $timestamps = false;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'revisions_surveys';

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
     * Get the corresponding Survey.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getSurvey()
    {
        return $this->belongsTo('Lara\Survey', 'object_id', 'id');
    }
}
