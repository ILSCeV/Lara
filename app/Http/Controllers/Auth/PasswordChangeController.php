<?php

namespace Lara\Http\Controllers\Auth;

use Illuminate\Support\Facades\Config;
use Lara\Http\Controllers\Controller;
use Lara\Utilities;

use Auth;
use Input;
use Hash;
use Redirect;
use Validator;
use View;

class PasswordChangeController extends Controller
{
    public function showChangePasswordForm()
    {
        return View::make('auth.passwords.change');
    }

    public function changePassword()
    {
        // Get old password and check that it matches the one stored in the DB
        $validator = $this->validator(Input::all());
        if($validator->fails()){
            return Redirect::back()->withErrors($validator)->withInput();
        }
        $oldPassword = Input::get('old-password');

        $user = Auth::user();

        $isPasswordCorrect = Hash::check($oldPassword, $user->password);

        if (!$isPasswordCorrect) {
            Utilities::error(trans('auth.passwordDoesNotMatch'));
            return Redirect::back();
        }

        $newPassword = Input::get('password');
        $user->password = bcrypt($newPassword);
        $user->save();

        if (!\App::environment('development') && strpos(env('LDAP_SECTIONS',''), $user->section->title ) !== false) {
            try {
                $this->changePasswordInLDAP($user);
            } catch (\Exception $e){
                \Log::error("ldap broken", $e);
            }
        }

        Utilities::success(trans('auth.passwordChanged'));

        return Redirect::to('/');
    }

    public function validator($data)
    {
        return Validator::make($data, [
            'old-password' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);
    }

    /**
     * @param $user
     */
    private function changePasswordInLDAP($user)
    {
// CONNECTING TO LDAP SERVER
        $ldapConn = ldap_connect(Config::get('bcLDAP.server'), Config::get('bcLDAP.port'));

        // Set some ldap options for talking to AD
        // LDAP_OPT_PROTOCOL_VERSION: LDAP protocol version
        ldap_set_option($ldapConn, LDAP_OPT_PROTOCOL_VERSION, 3);
        // LDAP_OPT_REFERRALS: Specifies whether to automatically follow referrals returned by the LDAP server
        ldap_set_option($ldapConn, LDAP_OPT_REFERRALS, 0);

        // Bind as a domain admin
        $ldap_bind = ldap_bind($ldapConn,
            Config::get('bcLDAP.admin-username'),
            Config::get('bcLDAP.admin-password'));

        // Search for a bc-Club user with the uid number entered
        $search = ldap_search($ldapConn,
            Config::get('bcLDAP.bc-club-ou') .
            Config::get('bcLDAP.base-dn'),
            '(uid=' . $user->person->prsn_ldap_id . ')');

        $info = ldap_get_entries($ldapConn, $search);
        $userFound = false;
        // Set default user access group to "bc-Club member"
        if ($info['count'] != 0) {
            $userFound = true;
        } else {
            $search = ldap_search($ldapConn,
                Config::get('bcLDAP.bc-cafe-ou') .
                Config::get('bcLDAP.base-dn'),
                '(uid=' . $user->person->prsn_ldap_id . ')');
            $info = ldap_get_entries($ldapConn, $search);
            if ($info['count'] != 0) {
                $userFound = true;
            }
        }

        if ($userFound) {
            $userEntry = ldap_first_entry($ldapConn, $search);
            $userDn = ldap_get_dn($ldapConn, $userEntry);
            // Hashing password input
            $encoded_newPassword = '{md5}' . base64_encode(mhash(MHnpASH_MD5, Input::get('password')));

            $entry = array();
            $entry["userPassword"] = "$encoded_newPassword";

            if (!ldap_modify($ldapConn, $userDn, $entry)) {
                \Log::error("ldap change not worked");
            }
        }

        ldap_unbind($ldapConn);
    }
}
