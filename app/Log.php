<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = [
        'user_id',
        'loggable_id',
        'loggable_type',
        'old_value',
        'new_value',
        'action',
        'ip',
        'created_at',
        'updated_at'
    ];

    public function loggable() 
    {
        return $this->morphTo();
    }
}
