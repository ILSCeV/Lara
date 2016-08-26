<?php

namespace Lara\Http\Controllers;

use Illuminate\Http\Request;

use View;
use Lara\Person;
use Session;

use Lara\Http\Requests;
use Lara\StatisticsInformation;

class StatisticsController extends Controller
{
    public function showStatistics()
    {
        $from = date('Y-m-01');
        $till = date('Y-m-t');

        $persons = Person::whereNotNull('prsn_ldap_id')->get();
        $infos = $persons->map(function($person) use($from, $till) {
            $info = new StatisticsInformation();
            $info->make($person, $from, $till);
            return $info;
        });

        $infos = $infos->sortByDesc('totalShifts');

        $userId = Session::get('userId');
        $userInfo = $infos->get($infos->search(function (StatisticsInformation $info) use ($userId) {
            return $info->userId == $userId;
        }));
        return View::make('statisticsView', compact('infos', 'userInfo'));

    }
}
