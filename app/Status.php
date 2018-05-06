<?php

namespace Lara;

use Auth;

class Status {

    const CANDIDATE = 'candidate';
    const MEMBER = 'member';
    const VETERAN = 'veteran';
    const RESIGNED = 'resigned';
    const EXMEMBER = 'ex-member';
    const EXCANDIDATE = 'ex-candidate';
    const GUEST = 'guest';

    const ALL = [
        self::CANDIDATE,
        self::MEMBER,
        self::VETERAN,
        self::RESIGNED,
        self::EXMEMBER,
        self::EXCANDIDATE,
    ];

    const ACTIVE = [
        self::CANDIDATE,
        self::MEMBER,
        self::VETERAN,
    ];

    const SHORTHANDS = [
        self::CANDIDATE => "K",
        self::MEMBER => "A",
        self::VETERAN => "V"
    ];

    public static function shortHand($status)
    {
        if (!array_key_exists($status, self::SHORTHANDS)) {
            return "";
        }
        return self::SHORTHANDS[$status];
    }

    public static function style($status, $section = NULL)
    {
        if (!$section) {
            $section = Section::query()->first();
        }
        switch ($status) {
            case STATUS::CANDIDATE:
                return ["status" => "fa fa-adjust", "style" => "color:yellowgreen;", "title" => self::localize($status, $section)];
            case STATUS::VETERAN:
                return ["status" => "fa fa-star", "style" => "color:gold;", "title" => self::localize($status, $section)];
            case STATUS::MEMBER:
                return ["status" => "fa fa-circle", "style" => "color:forestgreen;", "title" => self::localize($status, $section)];
            case STATUS::EXMEMBER:
                return ["status" => "fa fa-star-o", "style" => "color:gold;", "title" => self::localize($status, $section)];
            case STATUS::EXCANDIDATE:
                return ["status"=>"fa fa-circle", "style"=>"color:lightgrey;", "title" => self::localize($status, $section)];
            default:
                return ["status" => "fa fa-circle", "style" => "color:lightgrey;", "title" => "Extern"];
        }
    }

    public static function localize($status, $section = NULL)
    {
        if ($section === NULL) {
            $section = Section::query()->first();
        }
        return trans($section->title . "." . $status);
    }

    public static function localizeCurrent()
    {
        $user = Auth::user();
        if (!$user) {
            return "";
        }
        return self::localize($user->status, $user->section);
    }

}
