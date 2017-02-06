<?php
/**
 * User: fabian
 * Date: 31.01.17
 * Time: 00:06
 */

namespace Lara\Http\Controllers;

use Eluceo\iCal\Component\Alarm;
use Eluceo\iCal\Component\Calendar;
use Eluceo\iCal\Component\Event;
use Eluceo\iCal\Component\Timezone;
use Illuminate\Support\Facades\Cache;
use Lara\ClubEvent;
use Lara\Person;
use Lara\ScheduleEntry;

class IcalController extends Controller
{
    /** date time format */
    const DATE_TIME_FORMAT = "Y-m-d H:i:s";

    /** date format */
    const DATE_FORMAT = "Y-m-d";

    /** accessor to all ical keys in the cache Cache::get(self::ICAL_ACCESSOR) */
    const ICAL_ACCESSOR = "icalcache";


    /** creates an ical for all club-events*/
    public function events()
    {
        $calendar = Cache::remember("icalAllEvents", 4 * 60, function () {
            $vCalendar = new Calendar('Events');
            $vCalendar->setTimezone(new Timezone("Europe/Berlin"));

            $now = new \DateTimeImmutable();
            $startDate = $now->sub(new \DateInterval("P6M"));
            $stopDate = $now->add(new \DateInterval("P6M"));

            $events = ClubEvent::where("evnt_is_private", "=", '0')
                ->where('evnt_date_start',">=" ,$startDate->format(self::DATE_FORMAT))
                ->where('evnt_date_start',"<=",$stopDate->format(self::DATE_FORMAT))
                ->with('place')
                ->get();
            $vEvents = $events->map(function ($evt) {
                $vEvent = new Event();
                $vEvent->setUseTimezone(true);
                $vEvent->setDtStart(\DateTime::createFromFormat(self::DATE_TIME_FORMAT, "" . $evt->evnt_date_start . " " . $evt->evnt_time_start));
                $vEvent->setDtEnd(\DateTime::createFromFormat(self::DATE_TIME_FORMAT, "" . $evt->evnt_date_end . " " . $evt->evnt_time_end));
                $vEvent->setSummary($evt->evnt_title);
                $vEvent->setDescription($evt->evnt_public_info);
                $place = $evt->place->plc_title;
                $vEvent->setLocation($place, $place);

                $keys = Cache::get(self::ICAL_ACCESSOR, array());
                array_push($keys, "icalAllEvents");
                $keys = array_unique($keys);
                Cache::put(self::ICAL_ACCESSOR, $keys);

                return $vEvent;
            });

            foreach ($vEvents as $vEvent) {
                $vCalendar->addComponent($vEvent);
            }

            return $vCalendar->render();
        });

        return response($calendar)
            ->withHeaders(['Content-Type' => 'text/calendar',
                'charset' => 'utf-8',
                'Content-Disposition' => 'attachment; filename="cal.ics"'
            ]);
    }

    /**
     * creates an individual ical using your club_id
     * @param $club_id - your club id, for example 1970
     * @param $alarm - how many minutes you want to remind before the event
     */
    public function userScheduleWithAlarm($club_id, $alarm = 0)
    {
        $personal_calendar = Cache::remember("ical" . $club_id . $alarm, 4 * 60, function () use ($club_id, $alarm) {
            $person = Person::where('prsn_ldap_id', '=', $club_id)->first();

            $vCalendar = new Calendar('Events');
            $vCalendar->setTimezone(new Timezone("Europe/Berlin"));
           /* $now = new \DateTimeImmutable();
            $startDate = $now->sub(new \DateInterval("P6M"));
            $stopDate = $now->add(new \DateInterval("P6M")); */

            $events = ScheduleEntry::where('prsn_id', '=', $person->id)
                ->with("schedule", "schedule.event.place", "schedule.event", "jobType")
             //   ->where('schedule.event.evnt_date_start',">=" ,$startDate->format(self::DATE_FORMAT))
             //   ->where('schedule.event.evnt_date_start',"<=",$stopDate->format(self::DATE_FORMAT))
                ->get();

            $vEvents = $events->map(function ($evt) use ($alarm) {
                $schedule = $evt->schedule;
                $start_time = "";
                $preparationNeeded = false;
                if ($schedule->event->evnt_time_start == $evt->entry_time_start) {
                    $start_time = $schedule->schdl_time_preparation_start;
                    $preparationNeeded = true;
                } else {
                    $start_time = $evt->entry_time_start;
                    $preparationNeeded = false;
                }

                $stopHour = intval(substr($evt->entry_time_end, 0, 2));

                $stop_date = "";
                if ($stopHour > 18) {
                    $stop_date = $schedule->event->evnt_date_start;
                } else {
                    $stop_date = $schedule->event->evnt_date_end;
                }

                $vEvent = new Event();
                $start_date_time = \DateTime::createFromFormat(self::DATE_TIME_FORMAT, "" . $schedule->event->evnt_date_start . " " . $start_time);
                $stop_date_time = \DateTime::createFromFormat(self::DATE_TIME_FORMAT, "" . $stop_date . " " . $evt->entry_time_end);

                if ($start_date_time != false && $stop_date_time != false) {
                    $vEvent->setDtStart($start_date_time);
                    $vEvent->setDtEnd($stop_date_time);
                    $vEvent->setSummary("" . ($schedule->event->evnt_title) . " - " . ($evt->jobType->jbtyp_title));
                    $prefixDescription = "";
                    if ($preparationNeeded) {
                        $prefixDescription = "shift start:" . $evt->entry_time_start . " DV-time: " . $start_time . "\n";
                    }
                    $vEvent->setDescription($prefixDescription . $schedule->event->evnt_public_info);
                    $place = $schedule->event->place->plc_title;
                    $vEvent->setLocation($place, $place);
                    $vEvent->setUseTimezone(true);
                    if ($alarm > 0 && ($start_date_time > new \DateTime())) {
                        $vAlarm = new Alarm();
                        $vAlarm->setAction(Alarm::ACTION_DISPLAY);
                        $vAlarm->setDescription($schedule->event->evnt_title . " - " . ($evt->jobType->jbtyp_title));
                        $vAlarm->setTrigger("-PT" . $alarm . "M");
                        $vEvent->addComponent($vAlarm);
                    }
                }
                return $vEvent;
            });

            foreach ($vEvents as $vEvent) {
                $vCalendar->addComponent($vEvent);
            }

            $keys = Cache::get(self::ICAL_ACCESSOR, array());
            array_push($keys, "ical" . $club_id . $alarm);
            $keys = array_unique($keys);
            Cache::put(self::ICAL_ACCESSOR, $keys);

            return $vCalendar->render();
        });;


        return response($personal_calendar)
            ->withHeaders(['Content-Type' => 'text/calendar',
                'charset' => 'utf-8',
                'Content-Disposition' => 'attachment; filename="' . $club_id . '.ics"'
            ]);
    }

}