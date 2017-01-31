<?php
/**
 * User: fabian
 * Date: 31.01.17
 * Time: 00:06
 */

namespace Lara\Http\Controllers;

use Eluceo\iCal\Component\Calendar;
use Eluceo\iCal\Component\Event;
use Lara\ClubEvent;

class IcalController extends Controller
{

    public function events()
    {
        $vCalendar = new Calendar('Events');

        $events = ClubEvent::all();
        $vEvents = $events->map(function ($evt) {
            $dateString = "Y-m-d H:i:s";
            $vEvent = new Event();

            $vEvent->setDtStart(\DateTime::createFromFormat($dateString,"".$evt->evnt_date_start." ".$evt->evnt_time_start));
            $vEvent->setDtEnd(\DateTime::createFromFormat($dateString,"".$evt->evnt_date_end." ".$evt->evnt_time_end));
            $vEvent->setSummary($evt->evnt_title);
            //var_dump($evt);

            return $vEvent;
        });


        foreach ($vEvents as $vEvent) {
            $vCalendar->addComponent($vEvent);
        }

        header('Content-Type: text/calendar; charset=utf-8');
        header('Content-Disposition: attachment; filename="cal.ics"');

        echo $vCalendar->render();
    }

}