<?php

namespace Lara\Http\Controllers;


use Auth;
use Config;
use Lara\Settings;
use Redirect;
use Session;

class LanguageController extends Controller
{
    public function switchLang($lang)
    {
        if (array_key_exists($lang, Config::get('languages'))) {
            Session::put('applocale', $lang);

            $user = Auth::user();
            if($user) {
                $ldap_id = $user->person->prsn_ldap_id;

                $userSettings = Settings::where('userId','=', $ldap_id)->first();

                if(!isset($userSettings)){
                    $userSettings = new Settings();
                }

                $userSettings->language = ''.$lang;
                $userSettings->userId = $ldap_id;
                $userSettings->save();
            }
        }

        return Redirect::back();
    }
}
