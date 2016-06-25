<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseRevision_Relation
 * @package Lara
 */
abstract class BaseRevision_Relation extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

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
}
