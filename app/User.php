<?php

namespace Lara;

use Illuminate\Notifications\Notifiable;
use Lara\Person;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Lara\utilities\RoleUtility;

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


    public function person()
    {
        return $this->belongsTo('Lara\Person');
    }

    public function section()
    {
        return $this->belongsTo('Lara\Section');
    }

    public static function createFromPerson(Person $person)
    {
        if (!$person->club->section()) {
            return NULL;
        }
        $user = User::create([
            'name' => $person->prsn_name,
            'section_id' => $person->club->section()->id,
            'person_id' => $person->id,
            'status' => $person->prsn_status
        ]);
        RoleUtility::assignPrivileges($user,$person->club->section()->first(),
            RoleUtility::PRIVILEGE_MEMBER);

        return $user;
    }

    public function is($permissions)
    {
        if (!is_array($permissions)) {
            $permissions = [$permissions];
        }

        return collect($permissions)
            ->contains($this->group);
    }
}
