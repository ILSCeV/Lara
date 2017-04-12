<?php

namespace Lara\Http\Controllers;

use Illuminate\Http\Request;

use Lara\Http\Requests;
use Lara\Http\Controllers\Controller;
use Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Lara\Settings;

class LanguageController extends Controller
{
    public function switchLang($lang)
    {
        if (array_key_exists($lang, Config::get('languages'))) {
            Session::set('applocale', $lang);
            
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
