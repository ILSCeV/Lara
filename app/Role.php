<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

/**
 * @property string $name
 * @property \Illuminate\Database\Eloquent\Relations\BelongsTo/Section section
 * @property \Illuminate\Database\Eloquent\Relations\belongsToMany/User users
 */
class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = [
        'name',
        'section_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Section
     */
    public function section(){
        return $this->belongsTo(Section::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany|User
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
