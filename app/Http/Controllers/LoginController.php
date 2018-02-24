<?php

namespace Lara\Http\Controllers;

use Config;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Input;
use Lara\Person;
use Lara\Settings;
use Log;
use Redirect;
use Session;

use Illuminate\Foundation\Auth\AuthenticatesUsers;

/* 
--------------------------------------------------------------------------
    Copyright (C) 2015  Maxim Drachinskiy
                        Silvi Kaltwasser
                        Nadine Sobisch
                        Robert Utnehmer

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details (app/LICENSE.txt).

    Any questions? Mailto: maxim.drachinskiy@bc-studentenclub.de
--------------------------------------------------------------------------
*/

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Logout current user, delete relevant session data.
     *
     * @return RedirectResponse
     */
    public function doLogout()
    {
        Session::flush();
        if (Auth::user()) {
            return $this->logout();
        }
        return Redirect::to('/');
    }

    /**
     * ############################################
     * #             bc-LDAP Server               #
     * ############################################
     *
     *        ONLY accessible from bc-Club
     *        and with a valid config file.
     *
     */

    /**
     * Authenticates a user and saves credentials in session data.
     *
     * CONFIG is stored in \app\config\bcLDAP.php
     * For the purpose of securing personal data of club members
     * this config will not be shared via SVN. Ask Maxim for a working copy
     * if you absolutely need to use bc-LDAP.
     *
     * Connects to bcLDAP server using data from config.
     *
     * Binds as "replicator" (read-only rights) and searches for a user with uid that matches input.
     * If found, compares that user's password with input.
     * On success returns relevant infos in session data.
     * Informs user about errors or success.
     *
     * ToDo: Transform this into a ServiceProvider + Auth.
     * ToDo: 'No LDAP-Link' message for lost connection.
     *
     * @param  string $userName (as form input)
     * @param  string $userpassword (as form input)
     *
     * @return int $userId           (as session data)
     * @return string $userName      (as session data)
     * @return string $userGroup     (as session data)
     *
     * @return string $message       (as session data)
     * @return string $msgType       (as session data)
     *
     * @return RedirectResponse
     */
    /**
     * WORKAROUND for LDAP-Server down time
     */
    /*  DELETE THIS LINE TO ACTIVATE WORKAROUND AND COMMENT OUT WORKING CONTROLLER BELOW */
    public function doLogin()
    {
        if (App::environment('development')) {
            $result = $this->doDevelopmentLogin();
        } else {
            $result = $this->doProductionLogin();
        }

        $user = Auth::user();
        if (!$user) {
            return $result;
        }

        $userSettings = Settings::where('userId','=', $user->person->prsn_ldap_id)->first();
        if (!$userSettings) {
            return $result;
        }
        Session::put('applocale', $userSettings->language);
        return $result;
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function doDevelopmentLogin()
    {
        $isLDAPLogin = strpos(Input::get('username'), "@") === FALSE;
        if (!$isLDAPLogin) {
            return $this->doLaraLogin();
        }
        $userGroup = Input::get('userGroup');

        $person = Person::where('clb_id', '<' , 4)->inRandomOrder()->first();

        $person->user()->fill(["group" => $userGroup])->save();
        $this->loginPersonAsUser($person->prsn_ldap_id);
        Log::info('Auth success: ' . $person->user()->name . ' (' . $person->prsn_ldap_id . ', ' . $userGroup . ') just logged in.');

        return Redirect::back();
    }


    /**
     * WORKING CONTROLLER BELOW THIS LINE,
     * will only function with a bcLDAP-Config present.
     */

    public function doProductionLogin()
    {
// MASTERPASSWORD for LDAP-Server downtime, stored in hashed form in config/bcLDAP.php
        if (Input::get('username') === "LDAP-OVERRIDE") {

            if (Config::get('bcLDAP.ldap-override') === base64_encode(mhash(MHASH_MD5, Input::get('password')))) {

                Session::put('userId', '9999');
                Session::put('userName', 'LDAP-OVERRIDE');
                Session::put('userGroup', 'clubleitung');
                Session::put('userClub', 'bc-Club');
                Session::put('userStatus', 'member');
                Session::put('clubFilter', 'bc-Club');

                Log::info('LDAP OVERRIDE USED.');

                return Redirect::back();

            } else {

                Session::put('message', Config::get('messages_de.ldap-override-fail'));
                Session::put('msgType', 'danger');

                Log::warning('LDAP OVERRIDE FAIL: wrong password!');

                return Redirect::back();
            }
        }


// Preview login for BD-Club, stored in hashed form in config/bcLDAP.php
        if (Input::get('username') === "bd-berta") {

            if (Config::get('bcLDAP.bd-berta') === base64_encode(mhash(MHASH_MD5, Input::get('password')))) {

                Session::put('userId', '8888');
                Session::put('userName', 'BD Berta Preview');
                Session::put('userGroup', 'clubleitung');
                Session::put('userClub', 'bd-Club');
                Session::put('userStatus', 'member');
                Session::put('clubFilter', 'bd-Club');

                Log::info('BD Preview login used.');

                return Redirect::back();

            } else {

                Session::put('message', Config::get('messages_de.ldap-override-fail'));
                Session::put('msgType', 'danger');

                Log::warning('BD BERTA PREVIEW LOGIN FAIL: wrong password!');

                return Redirect::back();
            }
        }


// BLACKLIST - following IDs will not be able to login
        // 1708 = public account for using bc-wiki
        if (Input::get('username') === "1708") {
            Session::put('message', 'Ne ne ne, nicht mit dieser Clubnummer, sie ist ja nur fur bc-Wiki zu benutzen ;)');
            Session::put('msgType', 'danger');

            Log::warning('bc-Wiki login used (1708), access denied.');

            return Redirect::back();

        }

        $isLDAPLogin = request('loginType') === 'LDAP';
        if ($isLDAPLogin) {
            return $this->doLDAPLogin();
        }
        return $this->doLaraLogin();

    }

    public function doLaraLogin()
    {
        // put email field into the request. Laravel expects login via an email field,
        // we use a username field (for both ldap and email logins), so we have to add this value
        $emailModifiedRequest = request()->merge(['email' => request('username')]);

        if ($this->attemptLogin($emailModifiedRequest)) {
            $user = $this->guard()->user();
            $person = $user->person;
            return Redirect::back();
        }
        return $this->loginFailed();
    }
    public function doLDAPLogin()
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


// INPUT VALIDATION AND ERROR HANDLING


        // Request UID if none entered
        if (Input::get('username') === '') {
            ldap_unbind($ldapConn);

            Session::put('message', Config::get('messages_de.user-id-input-empty'));
            Session::put('msgType', 'danger');

            Log::info('Auth fail: empty userID given.');

            return Redirect::back();
        }

        // Request numeric UID if something else is entered
        if (!is_numeric(Input::get('username'))) {
            ldap_unbind($ldapConn);

            Session::put('message', Config::get('messages_de.user-id-not-numeric'));
            Session::put('msgType', 'danger');

            Log::info('Auth fail: not a number given as userID (username: ' . Input::get('username') . ').');

            return Redirect::back();
        }


// AUTHENTICATING BC-CLUB


        // Search for a bc-Club user with the uid number entered
        $search = ldap_search($ldapConn,
            Config::get('bcLDAP.bc-club-ou') .
            Config::get('bcLDAP.base-dn'),
            '(uid=' . Input::get('username') . ')');

        $info = ldap_get_entries($ldapConn, $search);

        // Set default user access group to "bc-Club member"
        if ($info['count'] != 0) {
            $userGroup = "bc-Club";
        }


// AUTHENTICATING BC-CAFE


        // If no such user found in the Club - check Café next.
        if ($info['count'] === 0) {

            // Search for a Café-user with the uid number entered
            $search = ldap_search($ldapConn,
                Config::get('bcLDAP.bc-cafe-ou') .
                Config::get('bcLDAP.base-dn'),
                '(uid=' . Input::get('username') . ')');

            $info = ldap_get_entries($ldapConn, $search);

            // If found - set user access group to "bc-Café member"
            if ($info['count'] != 0) {
                $userGroup = "bc-Café";
            }
        }


// HANDLING ERRORS


        // If no match found in all clubs - throw an error and quit
        if ($info['count'] === 0) {
            ldap_unbind($ldapConn);

            Session::put('message', Config::get('messages_de.uid-not-found'));
            Session::put('msgType', 'danger');

            Log::info('Auth fail: wrong userID given (username: ' . Input::get('username') . ').');

            return Redirect::back();
        }


// SETTING ACCESS GROUP


        // get full user DN
        $userDn = $info[0]['dn'];

        if ($userGroup === "bc-Club") {
            // Check if user has MARKETING rights in bc-CLub
            $searchGroup = ldap_search($ldapConn,
                Config::get('bcLDAP.bc-club-group-ou') .
                Config::get('bcLDAP.base-dn'),
                Config::get('bcLDAP.bc-club-marketing-group'));

            $infoGroup = ldap_get_entries($ldapConn, $searchGroup);

            for ($i = 0; $i < $infoGroup[0]['member']['count']; $i++) {
                if ($infoGroup[0]['member'][$i] == $userDn) {
                    $userGroup = "marketing";
                }
            }


            // Check if user has MANAGEMENT rights in bc-CLub
            $searchGroup = ldap_search($ldapConn,
                Config::get('bcLDAP.bc-club-group-ou') .
                Config::get('bcLDAP.base-dn'),
                Config::get('bcLDAP.bc-club-management-group'));

            $infoGroup = ldap_get_entries($ldapConn, $searchGroup);

            for ($i = 0; $i < $infoGroup[0]['member']['count']; $i++) {
                if ($infoGroup[0]['member'][$i] == $userDn) {
                    $userGroup = "clubleitung";
                }
            }
        } elseif ($userGroup === "bc-Café") {
            // Check if user has MARKETING rights in bc-Café
            $searchGroup = ldap_search($ldapConn,
                Config::get('bcLDAP.bc-cafe-group-ou') .
                Config::get('bcLDAP.base-dn'),
                Config::get('bcLDAP.bc-cafe-marketing-group'));

            $infoGroup = ldap_get_entries($ldapConn, $searchGroup);

            for ($i = 0; $i < $infoGroup[0]['member']['count']; $i++) {
                if ($infoGroup[0]['member'][$i] == $userDn) {
                    $userGroup = "marketing";
                }
            }


            // Check if user has MANAGEMENT rights in bc-Café
            $searchGroup = ldap_search($ldapConn,
                Config::get('bcLDAP.bc-cafe-group-ou') .
                Config::get('bcLDAP.base-dn'),
                Config::get('bcLDAP.bc-cafe-management-group'));

            $infoGroup = ldap_get_entries($ldapConn, $searchGroup);

            for ($i = 0; $i < $infoGroup[0]['member']['count']; $i++) {
                if ($infoGroup[0]['member'][$i] == $userDn) {
                    $userGroup = "clubleitung";
                }
            }
        }


// SETTING ADMIN CREDENTIALS


        // Checks if user LDAP ID is among hardcoded admin LDAP IDs from the config file
        if (in_array($info[0]['uidnumber'][0], Config::get('bcLDAP.admin-ldap-id'))) {
            $userGroup = "admin";
        }


// PREPARE USER CREDENTIALS


        // Get user nickname if it exists or first name instead
        $userName = (!empty($info[0]['mozillanickname'][0])) ?
            $info[0]['mozillanickname'][0] :
            $info[0]['givenname'][0];

        // Get user club
        if (substr($info[0]['dn'], 22, -7) === "cafe") {
            $userClub = "bc-Café";
        } elseif (substr($info[0]['dn'], 22, -7) === "bc-club") {
            $userClub = "bc-Club";
        }

        // Get user active status
        $userStatus = $info[0]['ilscstate'][0];


// AUTHENTICATE USER


        // Hashing password input
        $password = '{md5}' . base64_encode(mhash(MHASH_MD5, Input::get('password')));

        // end ldapConnection
        ldap_unbind($ldapConn);

        // Compare password in LDAP with hashed input.
        if ($info[0]['userpassword'][0] === $password) {
            $this->loginPersonAsUser($info[0]['uidnumber'][0]);

            Log::info('Auth success: ' .
                $info[0]['cn'][0] .
                ' (' .
                $info[0]['uidnumber'][0] .
                ', "' .
                (!empty($info[0]['mozillanickname'][0]) ? $info[0]['mozillanickname'][0] : $info[0]['givenname'][0]) .
                '", ' .
                $userGroup .
                ') just logged in.');

            return Redirect::back();
        }

        Log::info('Auth fail: ' . $info[0]['cn'][0] . ' (' . $info[0]['uidnumber'][0] . ', ' . $userGroup . ') used wrong password.');

        return $this->loginFailed();
    }

    /**
     * @return mixed
     */
    public function loginFailed()
    {
        Session::put('message', Config::get('messages_de.login-fail'));
        Session::put('msgType', 'danger');

        return Redirect::back();
    }

    /**
     * @param $ldapId
     */
    public function loginPersonAsUser($ldapId)
    {
        $person = Person::where('prsn_ldap_id', $ldapId)->first();
        Auth::login($person->user());
    }

}

/*      This is what the returned bcLDAP object looks like (only useful fields are shown here).  

        Array ( 
            [count] => 1 
            [0] => Array ( 
                [uidnumber] => Array ( 
                    [count] => 1 
                    [0] => 1000 )                               // UID number 

                [uid] => Array ( 
                    [count] => 1 
                    [0] => 1000 )                               // Clubnumber

                [cn] => Array ( 
                    [count] => 1 
                    [0] => Dummy Dumminson )                    // Full name

                [userpassword] => Array ( 
                    [count] => 1 
                    [0] => {md5}somethinghashedhere== )         // Hashed password

                [ilscmember] => Array ( 
                    [count] => 1 
                    [0] => 20110110000000Z )                    // Member since 10. Jan 2011

                [sn] => Array ( 
                    [count] => 1 
                    [0] => Dumminson )                          // Last name 

                [birthday] => Array ( 
                    [count] => 1 [0] => 19990101000000Z )       // Birthday

                [givenname] => Array ( 
                    [count] => 1 
                    [0] => Dummy )                              // First name

                [mozillanickname] => Array ( 
                    [count] => 1 
                    [0] => Dummer )                             // Clubname (nickname)

                [mail] => Array ( 
                    [count] => 1 
                    [0] => dummy@mail.com )                     // Email
               
                [candidate] => Array ( 
                    [count] => 1 
                    [0] => 20101011000000Z )                    // Candidate since 11. Oct 2010
               
                [ilscstate] => Array ( 
                    [count] => 1 
                    [0] => veteran )                            // Club status (active/candidate/veteran/resigned)

                [dn] => uid=1000,ou=People,ou=bc-club,o=ilsc )  // Full DN
            ) 

/*      bc-clubcl & bcMarketing Group object:

        array(2) { ["count"]=> int(1) 
        [0]=> array(10) { 
            ["cn"]=> array(2) { 
                ["count"]=> int(1) 
                [0]=> string(9) "bc-clubcl" 
            } 

            ["member"]=> array(6) { 
                ["count"]=> int(5) 
                [0]=> string(36) "uid=9999,ou=People,ou=bc-club,o=ilsc" 
                [1]=> string(36) "uid=9998,ou=People,ou=bc-club,o=ilsc" 
                [2]=> string(36) "uid=9997,ou=People,ou=bc-club,o=ilsc" 
                [3]=> string(36) "uid=9996,ou=People,ou=bc-club,o=ilsc" 
                [4]=> string(36) "uid=9995,ou=People,ou=bc-club,o=ilsc" 
            } 

*/            

