<?php

namespace Lara;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Lara\utilities\RoleUtility;
use Lara\Status;

/**
 * @property string name
 * @property string email
 * @property string password
 * @property string status
 * @property \Illuminate\Database\Eloquent\Relations\belongsToMany/Role $roles
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        // authentication related
        'name', 'email', 'password',
        // Lara related
        'section_id', 'person_id', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo/Lara\Person
     */
    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo/Lara\Section
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany/Lara\Role
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public static function createFromPerson(Person $person)
    {
        if (!$person->club) {
            return NULL;
        }

        if (!$person->club->section()) {
            return NULL;
        }
        $oldStatus = $person->prsn_status;

        $newStatus = in_array($oldStatus, Status::ALL) ? $oldStatus : Status::MEMBER;
        $user = User::create([
            'name' => $person->prsn_name,
            'section_id' => $person->club->section()->id,
            'person_id' => $person->id,
            'status' => $newStatus
        ]);
        RoleUtility::assignPrivileges(
            $user, $person->club->section(),
            RoleUtility::PRIVILEGE_MEMBER
        );

        return $user;
    }

    public function is($permissions)
    {
        if (!is_array($permissions)) {
            $permissions = [$permissions];
        }

        return $this->roles()->whereIn('name', $permissions)->exists();
    }

    public function hasPermissionsInSection(Section $section, ...$permission)
    {
        return $this->is(RoleUtility::PRIVILEGE_ADMINISTRATOR) ||
            $this->roles()
                ->whereIn('name', $permission)
                ->where('section_id', '=', $section->id)
                ->exists();
    }

    /**
     * @param Role $role
     * @return boolean user has role
     */
    public function hasPermission(Role $role)
    {
        return $this->roles()->where('id','=',$role->id)->exists();
    }

    /**
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Collection/Role
     */
    public function getRolesOfType($type){
        return $this->roles()->where('name','=',$type)->get();
    }

    /**
     * @param string $type
     * @return Collection/int
     */
    public function getSectionsIdForRoles($type)
    {
        return $this->getRolesOfType($type)->map(function (Role $role){
            return $role->section_id;
        });
    }

    public function settings() {
        return $this->hasOne('Lara\Settings');
    }
}
