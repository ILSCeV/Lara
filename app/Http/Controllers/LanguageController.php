<?php

namespace Lara\Http\Controllers;


use Auth;
use Config;
use Redirect;
use Session;

use Lara\Settings;

class LanguageController extends Controller
{
    public function switchLang($lang)
    {
        if (array_key_exists($lang, Config::get('languages'))) {
            Session::put('language', $lang);

            $user = Auth::user();
            if ($user) {
                $userSettings = $user->settings;

                if (!isset($userSettings)) {
                    $userSettings = new Settings();
                }

                $userSettings->language = ''.$lang;
                $userSettings->user_id = $user->id;
                $userSettings->save();
            }
        }

        return Redirect::back();
    }
}
