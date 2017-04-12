<?php

namespace Lara\Http\Controllers;


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
            
            if(Session::has('userId')){
               $userSettings = Settings::where('userId','=',Session::get('userId'))->first();
               if(!isset($userSettings)){
                   $userSettings = new Settings();
               }
               $userSettings->language = ''.$lang;
               $userSettings->userId = Session::get('userId');
               $userSettings->save();
            }
        }

        return Redirect::back();
    }
}
