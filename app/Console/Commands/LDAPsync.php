<?php

namespace Lara\Console\Commands;

use Config;
use Illuminate\Console\Command;
use Lara\Person;
use Lara\Section;
use Log;

class LDAPsync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lara:ldapsync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Overwrite name & status for every Person in Lara DB with the latest state from LDAP.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * Updates status of each Person saved in Lara with the latest state from LDAP
     * Main purpose: data is usually updated when a member logs in. This function targets updates for members who stop visiting Lara.
     *
     *
     * @return mixed
     */
    public function handle()
    {
        // Inform the users
        Log::info('Starting LDAP sync...');
        $this->info('Starting LDAP sync...');

        // get a list of all persons saved in Lara, except ldap-override
        $persons = (new Person)->whereNotNull('prsn_ldap_id')->whereNotIn('prsn_ldap_id', ['9999'])->get();

        // start counting time before processing every person
        $counterStart = microtime(true);

        // Initiate progress bar
        $bar = $this->output->createProgressBar(count($persons));

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
        $allowedSection = (new Section())->whereIn('title',['bc-Club','bc-Café'])->get();

// STARTING THE UPDATE
        /** @var Person $person */
        foreach ($persons as $person) {
            $user = $person->user;
            $bar->advance();
            // skip ldap override
            if($person->prsn_ldap_id == '9999' ){
                continue;
            }
            if(!$allowedSection->contains($person->club->section())){
                continue;
            }
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

            // If no match found in all clubs - log an error
            if ($info['count'] === 0) {
                Log::info('LDAP sync error: could not authenticate ' . $person->prsn_ldap_id . ' in LDAP!');
            }

// GETTING USER CREDENTIALS

            // Get user nickname if it exists or first name instead
            $userName = (!empty($info[0]['mozillanickname'][0])) ?
                $info[0]['mozillanickname'][0] :
                $info[0]['givenname'][0];

            // Get user active status
            $userStatus = $info[0]['ilscstate'][0];
            $userEmail = $info[0]['email'][0];

// UPDATE AND SAVE CHANGES

            if ($person->prsn_name !== $userName) {
                Log::info('LDAP sync: Changing ' . $person->prsn_name . " (" . $person->prsn_ldap_id . ") name from " . $person->prsn_name . " to " . $userName . '.');

                $person->prsn_name = $userName;
                $user->name = $userName;
            }


            if ($person->prsn_status !== $userStatus) {
                Log::info('LDAP sync: Changing ' . $person->prsn_name . " (" . $person->prsn_ldap_id . ") status from " . $person->prsn_status . " to " . $userStatus . '.');

                $person->prsn_status = $userStatus;
                $user->status = $userStatus;
            }

            $user->email = $userEmail;


            $person->save();
            $user->save();
        }

// FINISH UPDATE

        ldap_unbind($ldapConn);

        $bar->finish();

        $counterEnd = microtime(true);

        // report update time
        $this->info('');        // Linebreak
        $this->info('');        // Linebreak
        $this->info('Finished LDAP sync after ' . ($counterEnd - $counterStart) . ' seconds.');
        Log::info('Finished LDAP sync after ' . ($counterEnd - $counterStart) . ' seconds.');
    }
}
