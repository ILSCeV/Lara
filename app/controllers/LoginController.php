<?php

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

class LoginController extends BaseController {


    /**
     * Logout current user, delete relevant session data.
     *
	 * @return RedirectResponse
     */
    public function doLogout()
    {
        Session::flush();
        return Redirect::to('calendar/');
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
     * Authentificates a user and saves credentials in session data.
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
     * @param  string $userName      (as form input)
     * @param  string $userpassword  (as form input)
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
        // Placeholder for development, groups will be implemented later
        $userGroup = 'marketing';

        $input = array("1001" => "Neo", "1002" => "Morpheus", "1003" => "Trinity", "1004" => "Cypher", 
                       "1004" => "Tank", "1005" => "Hawkeye", "1006" => "Blackwidow", "1007" => "Deadpool", 
                       "1008" => "Taskmaster", "1009" => "nicht-FREI", "1010" => "Venom", "1011" => "Superman", 
                       "1012" => "Bart", "1013" => "Fry", "1014" => "Bender");
        
        $userId = array_rand($input, 1);
        $userName = $input[$userId];
        
        // get user club
        $inputClub = array("bc-Club", "bc-Café");

        $userClubId = array_rand($inputClub, 1);
        $userClub = $inputClub[$userClubId];

        $userStatus = "aktiv";

        Session::put('userId',      $userId);
        Session::put('userName',    $userName);
        Session::put('userGroup',   $userGroup);            
        Session::put('userClub',    $userClub);
        Session::put('userStatus',  $userStatus);

        Log::info('Auth success: User ' . $userName . ' (' . $userId .', group: ' . $userGroup . ') just logged in.');
      
        return Redirect::back();
  
        }
    }

/**
 * WORKING CONTROLLER BELOW THIS LINE,
 * will only function with a bcLDAP-Config present.
 */
/*
    public function doLogin()
    {
        // Connection to LDAP-dev
        $ldapConn = ldap_connect( Config::get('bcLDAP.server') );

        // Set some ldap options for talking to AD
        // LDAP_OPT_PROTOCOL_VERSION: LDAP protocol version
        ldap_set_option($ldapConn, LDAP_OPT_PROTOCOL_VERSION, 3);
        // LDAP_OPT_REFERRALS: Specifies whether to automatically follow referrals returned by the LDAP server
        ldap_set_option($ldapConn, LDAP_OPT_REFERRALS, 0);

        // Bind as a domain admin
        $ldap_bind = ldap_bind($ldapConn, 
                               Config::get('bcLDAP.adminUsername'), 
                               Config::get('bcLDAP.adminPassword'));
       
        // Request UID if none entered
        if(Input::get('username') === '')
        {
            ldap_unbind($ldapConn);

            Session::put('message', Config::get('messages_de.user-id-input-empty'));
            Session::put('msgType', 'danger');

            Log::info('Auth fail: empty userID given.');

            return Redirect::back();
        }

        // Request numeric UID if something else is entered
        if(!is_numeric(Input::get('username')))
        {
            ldap_unbind($ldapConn);

            Session::put('message', Config::get('messages_de.user-id-not-numeric'));
            Session::put('msgType', 'danger');

            Log::info('Auth fail: not a number given as userID (username: ' . Input::get('username') . ').');
            
            return Redirect::back();
        }

        // Search for a bc-Club user with the uid number entered
        $search = ldap_search($ldapConn, 
                              Config::get('bcLDAP.bcou') .
                              Config::get('bcLDAP.basedn'), 
                              '(uid=' . Input::get('username') . ')');

        // GET command
        $info = ldap_get_entries($ldapConn, $search);

        // Set default user group to "mitglied"
        $userGroup = "Mitglied"; //ToDO: set to "marketing" for development, change back to "bc-Club"


        // If no such user found in the Club - check Café next.
        if($info['count'] === 0){

            // Search for a Café-user with the uid number entered
            $search = ldap_search($ldapConn, 
                              Config::get('bcLDAP.cafeou') .
                              Config::get('bcLDAP.basedn'), 
                              '(uid=' . Input::get('username') . ')');

            // GET command
            $info = ldap_get_entries($ldapConn, $search);

            // If found - set user group to café
            if($info['count'] != 0){
                $userGroup = "bc-Café";
            }
        }

        // If no match found in both clubs - throw an error
        if($info['count'] === 0)
        {
            ldap_unbind($ldapConn);
            Session::put('message', Config::get('messages_de.uid-not-found'));
            Session::put('msgType', 'danger');
            Log::info('Auth fail: wrong userID given (username: ' . Input::get('username') . ', password: ' . Input::get('password') . ').');
            return Redirect::back();
        }

        // Hashing password input
        $password = '{md5}' . base64_encode(mhash(MHASH_MD5, Input::get('password')));

        // get full user DN
        $userDn = $info[0]['dn'];

        // Check if user is bc-Clubleitung
        $searchGroup = ldap_search( $ldapConn, 
                                    Config::get('bcLDAP.groupou') . 
                                    Config::get('bcLDAP.basedn'),
                                    Config::get('bcLDAP.clgroup'));

        $infoGroup = ldap_get_entries($ldapConn, $searchGroup);

        for ($i=0; $i < $infoGroup[0]['member']['count']; $i++) { 
            if($infoGroup[0]['member'][$i] == $userDn){ $userGroup = "clubleitung"; }
        } 

            
        // If not CL than maybe bc-Marketing
        $searchGroup = ldap_search($ldapConn, 
                                    Config::get('bcLDAP.groupou') . 
                                    Config::get('bcLDAP.basedn'),
                                    Config::get('bcLDAP.marketinggroup'));

        $infoGroup = ldap_get_entries($ldapConn, $searchGroup);

        for ($i=0; $i < $infoGroup[0]['member']['count']; $i++) { 
            if($infoGroup[0]['member'][$i] == $userDn){ $userGroup = "marketing"; }
        }

        // Get user nickname if it exists or first name instead
        $userName = (!empty($info[0]['mozillanickname'][0])) ? 
            $info[0]['mozillanickname'][0] :
            $info[0]['givenname'][0];

        // get user club
        $userClub = substr($info[0]['dn'], 22, -7);

        // Get user active status
        $userClubStatus = $info[0]['ilscstate'][0];

        // Compare password in LDAP with hashed input and inform about error or success,
        // Retrieve information about the user based on uidNumber if passwords match.
        // Also closing the connection here.
        if($info[0]['userpassword'][0] === $password)
        {
            ldap_unbind($ldapConn);
            
            Session::put('message', Config::get('messages_de.login-success'));
            Session::put('msgType', 'success');

            Session::put('userId', $info[0]['uidnumber'][0]);
            Session::put('userName', $userName);
            Session::put('userGroup', $userGroup);
            Session::put('userClub', $userClub);
            Session::put('userStatus', $userClubStatus);

            Log::info('Auth success: User ' . $info[0]['cn'][0] . ' (' . $info[0]['uidnumber'][0] .', group: ' . $userGroup . ') just logged in.');
          
            return Redirect::back();
  
        } else {
  
            ldap_unbind($ldapConn);

            Session::put('message', Config::get('messages_de.login-fail'));
            Session::put('msgType', 'danger');
           
            Log::info('Auth fail: User ' . $info[0]['cn'][0] . ' (' . $info[0]['uidnumber'][0] .', group: ' . $userGroup . ') used wrong password.');
           
            return Redirect::back();
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
                    [0] => veteran )                            // Club status (active/candidate/veteran)

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

