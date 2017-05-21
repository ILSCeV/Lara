<?php

namespace Lara\Http\Controllers;

use Illuminate\Http\Request;
use Cache;
use DateTime;
use DateInterval;
use Session;
use Config;
use Redirect;

use Lara\Person;

use Lara\Http\Requests;
use Lara\Http\Controllers\Controller;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( $query = NULL )
    {
        // Not showing everything to guests
        if(!Session::has('userId'))
        {
            Session::put('message', Config::get('messages_de.access-denied'));
            Session::put('msgType', 'danger');
            return Redirect::action('MonthController@showMonth', ['year' => date("Y"), 'month' => date('m')]);
        }

        // if no parameter specified - empty means "show all"
        if ( is_null($query) ) {  
            $query = "";
        }

        $persons =  \Lara\Person::whereNotNull( "prsn_ldap_id" )
                                // Look for autofill
                                ->where('prsn_name', 'like', '%' . $query . '%')
                                ->orderBy('prsn_name')
                                ->get(['prsn_name',
                                       'prsn_ldap_id',
                                       'prsn_status',
                                       'clb_id']);
                     
        return response()->json($persons);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    /** 
     * Updates status of each Person saved in Lara with the latest state from LDAP
	 * Main purpose: data is usually updated when a member logs in. This function targets updates for members who stop visiting Lara.
     */
    public function LDAPSync() 
    {
    	if (Session::get('userGroup') == 'admin') {

            // get a list of all persons saved in Lara
    		$persons = Person::whereNotNull('prsn_ldap_id')->get();

            // start counting time before processing every person
            $counterStart = microtime(true);

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

// STARTING THE UPDATE

    		foreach ($persons as $person) {

// AUTHENTICATING BC-CLUB

                // Search for a bc-Club user with the uid number entered
                $search = ldap_search($ldapConn,
                    Config::get('bcLDAP.bc-club-ou') .
                    Config::get('bcLDAP.base-dn'),
                    '(uid=' . $person->prsn_ldap_id . ')');

                $info = ldap_get_entries($ldapConn, $search);

// AUTHENTICATING BC-CAFE

                // If no such user found in the bc-Club - check bc-Café next.
                if ($info['count'] === 0) {

                    // Search for a Café-user with the uid number entered
                    $search = ldap_search($ldapConn,
                        Config::get('bcLDAP.bc-cafe-ou') .
                        Config::get('bcLDAP.base-dn'),
                        '(uid=' . $person->prsn_ldap_id . ')');

                    $info = ldap_get_entries($ldapConn, $search);
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

// GETTING USER CREDENTIALS

                // Get user nickname if it exists or first name instead
                $userName = (!empty($info[0]['mozillanickname'][0])) ?
                    $info[0]['mozillanickname'][0] :
                    $info[0]['givenname'][0];

                // Get user active status
                $userStatus = $info[0]['ilscstate'][0];

// UPDATE AND SAVE CHANGES

                print_r("Lara: " .  $person->id . " - " . $person->prsn_ldap_id . " - " . $person->prsn_name . " - " . $person->prsn_status .  "<br>");
                print_r("LDAP: " . $info[0]['uidnumber'][0] . " - " . $userName . " - " . $userStatus . "<br>" . "<br>");
                
                $person->prsn_name = $userName;
                $person->prsn_status = $userStatus;

                $person->save();
    		}

// FINISH UPDATE

            ldap_unbind($ldapConn);

            $counterEnd = microtime(true);

            // report update time
            $response = ($counterEnd - $counterStart) . " sec";

            dd($response);

    	} else {
            dd("admin only");
        }
    }
}
