<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read User user
 * @property-read Section section
 */
class UserSectionsRoleView extends Model
{
    protected $table = 'user_sections_role_view';
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo/User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo/Section
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
    }
    
}
