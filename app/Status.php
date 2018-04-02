<?php

namespace Lara;

class Status {

    const CANDIDATE = 'candidate';
    const MEMBER = 'member';
    const VETERAN = 'veteran';

    const ALL_STATUS = [
        self::CANDIDATE,
        self::MEMBER,
        self::VETERAN
    ];

    const SHORTHANDS = [
        self::CANDIDATE => "K",
        self::MEMBER => "A",
        self::VETERAN => "V"
    ];

    public static function shortHand($status)
    {
        return self::SHORTHANDS[$status];
    }

}
