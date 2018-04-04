<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string user_id
 * @property string entry_name
 * @property string entry_value
 */
class LdapPlattform extends Model
{
    protected $fillable = ['user_id', 'entry_name', 'entry_value'];
}
