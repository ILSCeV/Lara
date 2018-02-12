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
            
            if(Auth::user())){
               $userSettings = Settings::where('userId','=',Auth::user()->person->prsn_ldap_id)->first();
               if(!isset($userSettings)){
                   $userSettings = new Settings();
               }
               $userSettings->language = ''.$lang;
               $userSettings->userId = Auth::user()->person->prsn_ldap_id;
               $userSettings->save();
            }
        }

        return Redirect::back();
    }
}
