<?php


namespace Lara;


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

    static function getAllCacheKeys(){
        $storage = Cache::getStore(); // will return instance of FileStore
        $filesystem = $storage->getFilesystem(); // will return instance of Filesystem

        $keys = [];
        foreach ($filesystem->allFiles('') as $file1) {
            foreach ($filesystem->allFiles($file1) as $file2) {
                $keys = array_merge($keys, $filesystem->allFiles($file1 . '/' . $file2));
            }
        }

        return $keys;
    }

    static function clearIcalCache(){
        $keys = self::getAllCacheKeys();
        foreach ($keys as $key){
            if(strpos($keys,'ical')!==false){
                \Cache::forever($key);
            }
        }
    }
}