<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;

class RevisionObjectRelation extends Model
{
    
    public $timestamps = false;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'revision_object_relations';

    /**
     * The database columns used by the model.
     * This attributes are mass assignable.
     *
     * @var array
     */
    protected $fillable = array();

    /**
     * Get the corresponding Person.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getRevision()
    {
        return $this->belongsTo('Lara\Revision', 'revision_id', 'id');
    }

    /**
     * Get the corresponding Person.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function revision()
    {
        return $this->belongsTo('Lara\Revision', 'revision_id', 'id');
    }
}
