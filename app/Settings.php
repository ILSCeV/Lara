<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;

/** Table to store user specific settings, e.g languages */
class Settings extends Model
{
    protected $table = 'settings';
    
    protected $fillable = ['language','userId'];
}
