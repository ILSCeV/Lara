<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;

class LdapPlattform extends Model
{
    protected $fillable = ['user_id', 'entry_name', 'entry_value'];
}
