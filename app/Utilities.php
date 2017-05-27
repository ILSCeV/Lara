<?php


namespace Lara;

use Illuminate\Support\Facades\Cache;
use Lara\Http\Controllers\IcalController;


class Utilities
{
    static function surroundLinksWithTags($text)
    {
        $urlMatching = '$((http(s)?:\/\/)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*))$';
        return preg_replace_callback($urlMatching,
            function ($match) {
                $link = $match[0];
                // if the protocol is missing, we have to add it. Assume http in this case
                if ($match[2] !== 'http://' && $match[2] !== 'https://') {
                    $link = 'http://' . $link;
                }
                return sprintf('<a href="%s" target="_blank"> %s </a>', $link, $match[0]);
            }, $text);
    }

    static function getAllCachedIcalKeys()
    {
        return Cache::get(IcalController::ICAL_ACCESSOR, array());
    }

    static function clearIcalCache()
    {
        $keys = self::getAllCachedIcalKeys();
        foreach ($keys as $key) {
            Cache::forget($key);
        }
        Cache::forget(IcalController::ICAL_ACCESSOR);
    }
}