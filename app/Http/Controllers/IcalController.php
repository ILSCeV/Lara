<?php
/**
 * User: fabian
 * Date: 31.01.17
 * Time: 00:06
 */

namespace Lara\Http\Controllers;

use Cache;
use Eluceo\iCal\Component\Alarm;
use Eluceo\iCal\Component\Calendar;
use Eluceo\iCal\Component\Event;
use Eluceo\iCal\Component\Timezone;
use Lara\ClubEvent;
use Lara\Person;
use Lara\Place;
use Lara\ScheduleEntry;
use Lara\Settings;
use Lara\Utilities;
use Log;
use Redirect;
use Session;
use URL;

/** Controller for generating iCal feeds */
class IcalController extends Controller
{
    /** date time format */
    const DATE_TIME_FORMAT = "Y-m-d H:i:s";
    
    /** date format */
    const DATE_FORMAT = "Y-m-d";
    
    /** accessor to all ical keys in the cache Cache::get(self::ICAL_ACCESSOR) */
    const ICAL_ACCESSOR = "icalcache";
    
    /** all published events of all locations */
    public function allPublicEvents()
    {
        $calendar = Cache::remember('icalAllPublicEvents', 4 * 60, function () {
            $vCalendar = new Calendar('Events');
            $vCalendar->setTimezone(new Timezone("Europe/Berlin"));
            
            $now = new \DateTimeImmutable();
            $startDate = $now->sub(new \DateInterval("P2M"));
            $stopDate = $now->add(new \DateInterval("P6M"));
            
            $events = ClubEvent::where('evnt_date_start', ">=", $startDate->format(self::DATE_FORMAT))
                ->where('evnt_date_start', "<=", $stopDate->format(self::DATE_FORMAT))
                ->where('evnt_is_private', '=', '0')
                ->where('evnt_is_published', '=', '1')
                ->get();
            
            $vEvents = $events->map(function ($evt) {
                $vEvent = new Event();
                $vEvent->setUseTimezone(true);
                $vEvent->setDtStart(\DateTime::createFromFormat(self::DATE_TIME_FORMAT,
                    "".$evt->evnt_date_start." ".$evt->evnt_time_start));
                $vEvent->setDtEnd(\DateTime::createFromFormat(self::DATE_TIME_FORMAT,
                    "".$evt->evnt_date_end." ".$evt->evnt_time_end));
                $vEvent->setSummary($evt->evnt_title);
                
                $eventLink = "".URL::route('event.show', $evt->id);
                $eventTime = $evt->evnt_time_start;
                
                $additionalInfo = $evt->evnt_public_info !== "" ? $evt->evnt_public_info : "(-)";
                
                $vEvent->setDescription("Link: ".$eventLink."\n"
                    ."\n"
                    .trans('mainLang.begin').": ".$eventTime."\n"
                    ."\n"
                    .trans('mainLang.additionalInfo').":\n"
                    .$additionalInfo."\n");
                
                $place = $evt->place->plc_title;
                $vEvent->setLocation($place, $place);
                
                return $vEvent;
            });
            
            foreach ($vEvents as $vEvent) {
                $vCalendar->addComponent($vEvent);
            }
            
            $keys = Cache::get(self::ICAL_ACCESSOR, []);
            array_push($keys, "icalAllPublicEvents");
            $keys = array_unique($keys);
            Cache::put(self::ICAL_ACCESSOR, $keys, 4 * 60);
            
            return $vCalendar->render();
        });
        
        return response($calendar)
            ->withHeaders([
                'Content-Type'        => 'text/calendar',
                'charset'             => 'utf-8',
                'Content-Disposition' => 'attachment; filename="lara-public-feed-all-locations.ics"',
            ]);
    }
    
    
    /**
     * creates an iCal for all club-events of a chosen location
     * @param $location the location
     * @param $with_private_info if this is enabled private infos will appended to the result
     */
    public function events($location, $with_private_info = 0)
    {
        $calendar = Cache::remember("icalAllEvents".$location, 4 * 60, function () use ($location, $with_private_info) {
            $vCalendar = new Calendar('Events');
            $vCalendar->setTimezone(new Timezone("Europe/Berlin"));
            
            $now = new \DateTimeImmutable();
            $startDate = $now->sub(new \DateInterval("P2M"));
            $stopDate = $now->add(new \DateInterval("P6M"));
            
            $place = null;
            if ($with_private_info > 0) {
                $place = Place::where('place_uid', "=", $location)->first();
            } else {
                $place = Place::where('plc_title', "=", $location)->first();
            }
            if (is_null($place)) {
                return $vCalendar->render();
            }
            
            $eventsQuery = ClubEvent::where('evnt_date_start', ">=", $startDate->format(self::DATE_FORMAT))
                ->where('evnt_date_start', "<=", $stopDate->format(self::DATE_FORMAT))
                ->with('place', 'getSchedule')
                ->where('plc_id', "=", $place->id);
            
            if ($with_private_info == 0) {
                $eventsQuery = $eventsQuery->where("evnt_is_private", "=", '0')->where('evnt_is_published', '=', '1');
            }
            
            $events = $eventsQuery->get();
            $vEvents = $events->map(function ($evt) use ($location, $with_private_info) {
                $vEvent = new Event();
                $vEvent->setUseTimezone(true);
                $vEvent->setDtStart(\DateTime::createFromFormat(self::DATE_TIME_FORMAT,
                    "".$evt->evnt_date_start." ".$evt->evnt_time_start));
                $vEvent->setDtEnd(\DateTime::createFromFormat(self::DATE_TIME_FORMAT,
                    "".$evt->evnt_date_end." ".$evt->evnt_time_end));
                $vEvent->setSummary($evt->evnt_title);
                
                $schedule = $evt->getSchedule;
                $start_time = "";
                
                $eventLink = "".URL::route('event.show', $evt->id);
                $eventTime = $evt->evnt_time_start;
                $preparationsTime = $schedule->schdl_time_preparation_start;
                $additionalInfo = $evt->evnt_public_info !== "" ? $evt->evnt_public_info : "(-)";
                
                $evtDescription = "Link: ".$eventLink."\n"
                    ."\n"
                    .trans('mainLang.begin').": ".$eventTime."\n"
                    .trans('mainLang.DV-Time').": ".$preparationsTime."\n"
                    ."\n"
                    ."---\n"
                    ."\n"
                    .trans('mainLang.additionalInfo').":\n"
                    .$additionalInfo."\n";
                
                if ($with_private_info > 0) {
                    $moreDetails = $evt->evnt_private_details !== "" ? $evt->evnt_private_details : "(-)";
                    
                    $evtDescription = $evtDescription
                        ."\n"
                        ."---\n"
                        ."\n"
                        .trans('mainLang.moreDetails').":\n"
                        .$moreDetails;
                }
                
                $vEvent->setDescription($evtDescription);
                
                $place = $evt->place->plc_title;
                $vEvent->setLocation($place, $place);
                
                return $vEvent;
            });
            
            foreach ($vEvents as $vEvent) {
                $vCalendar->addComponent($vEvent);
            }
            
            $keys = Cache::get(self::ICAL_ACCESSOR, []);
            array_push($keys, "icalAllEvents".$location);
            $keys = array_unique($keys);
            Cache::put(self::ICAL_ACCESSOR, $keys, 4 * 60);
            
            return $vCalendar->render();
        });
        
        $filename = 'all-events-single-location';
        if ($with_private_info != 0) {
            $filename = $filename."-with-private-info";
        }
        
        return response($calendar)
            ->withHeaders([
                'Content-Type'        => 'text/calendar',
                'charset'             => 'utf-8',
                'Content-Disposition' => 'attachment; filename="'.$filename.'.ics"',
            ]);
    }
    
    
    /**
     * creates an individual iCal using your club_id
     * @param $prsn_uid - your club id, for example 56202a26bbe2c6a847ed83fe266b2017d8a21cf6db610886990dbbad2fdb1fd68b58e6209154a2358f925b670b98daaed403413f0f152f0522ff0f22e3b39b9c
     * @param $alarm - how many minutes you want to remind before the event
     */
    public function userScheduleWithAlarm($prsn_uid, $alarm = 0)
    {
        $personal_calendar = Cache::remember("ical".$prsn_uid.$alarm, 4 * 60, function () use ($prsn_uid, $alarm) {
            $person = Person::where('prsn_uid', '=', $prsn_uid)->first();
    
            if(isset($person)) {
                $userSettings = Settings::where('userId', '=', $person->prsn_ldap_id)->first();
                if (isset($userSettings)) {
                    Session::put('applocale', $userSettings->language);
                }
            }
            $vCalendar = new Calendar('Events');
            $vCalendar->setTimezone(new Timezone("Europe/Berlin"));
            
            $events = ScheduleEntry::where('prsn_id', '=', $person->id)
                ->with("schedule", "schedule.event.place", "schedule.event", "jobType")
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
                
                $start_date_time = \DateTime::createFromFormat(self::DATE_TIME_FORMAT,
                    "".$schedule->event->evnt_date_start." ".$start_time);
                $stop_date_time = \DateTime::createFromFormat(self::DATE_TIME_FORMAT,
                    "".$stop_date." ".$evt->entry_time_end);
                
                if ($start_date_time != false && $stop_date_time != false) {
                    $vEvent->setDtStart($start_date_time);
                    $vEvent->setDtEnd($stop_date_time);
                    $vEvent->setSummary("".($schedule->event->evnt_title)." - ".($evt->jobType->jbtyp_title));
                    
                    $eventLink = "".URL::route('event.show', $schedule->event->id);
                    $eventTime = $evt->entry_time_start;
                    $preparationsTime = $schedule->schdl_time_preparation_start;
                    
                    $additionalInfo = $schedule->event->evnt_public_info !== "" ? $schedule->event->evnt_public_info : "(-)";
                    $moreDetails = $schedule->event->evnt_private_details !== "" ? $schedule->event->evnt_private_details : "(-)";
                    
                    $vEvent->setDescription("Link: ".$eventLink."\n"
                        ."\n"
                        .trans('mainLang.begin').": ".$eventTime."\n"
                        .trans('mainLang.DV-Time').": ".$preparationsTime."\n"
                        ."\n"
                        .trans('mainLang.shift').": ".$evt->jobtype->jbtyp_title."\n"
                        .trans('mainLang.shiftTime').": ".$evt->entry_time_start." - ".$evt->entry_time_end."\n"
                        ."\n"
                        ."---\n"
                        ."\n"
                        .trans('mainLang.additionalInfo').":\n"
                        .$additionalInfo."\n"
                        ."\n"
                        ."---\n"
                        ."\n"
                        .trans('mainLang.moreDetails').":\n"
                        .$moreDetails);
                    
                    $place = $schedule->event->place->plc_title;
                    
                    $vEvent->setLocation($place, $place);
                    $vEvent->setUseTimezone(true);
                    
                    if ($alarm > 0 && ($start_date_time > new \DateTime())) {
                        $vAlarm = new Alarm();
                        $vAlarm->setAction(Alarm::ACTION_DISPLAY);
                        $vAlarm->setDescription($schedule->event->evnt_title." - ".($evt->jobType->jbtyp_title));
                        $vAlarm->setTrigger("-PT".$alarm."M");
                        $vEvent->addComponent($vAlarm);
                    }
                }
                
                return $vEvent;
            });
            
            foreach ($vEvents as $vEvent) {
                $vCalendar->addComponent($vEvent);
            }
            
            $keys = Cache::get(self::ICAL_ACCESSOR, []);
            array_push($keys, "ical".$prsn_uid.$alarm);
            $keys = array_unique($keys);
            Cache::put(self::ICAL_ACCESSOR, $keys, 4 * 60);
            
            return $vCalendar->render();
        });
        
        
        return response($personal_calendar)
            ->withHeaders([
                'Content-Type'        => 'text/calendar',
                'charset'             => 'utf-8',
                'Content-Disposition' => 'attachment; filename="personal-calendar.ics"',
            ]);
    }
    
    
    /**
     * creates an iCal for a single event
     * @param $evt_id id of the event that should be exported
     */
    public function singleEvent($evt_id)
    {
        $calendar = Cache::remember("ical".$evt_id.Session::has('userGroup'), 4 * 60, function () use ($evt_id) {
            $vCalendar = new Calendar('Events');
            $vCalendar->setTimezone(new Timezone("Europe/Berlin"));
            $event = ClubEvent::where('id', '=', $evt_id)->first();
            if (isset($event)) {
                $vEvent = new Event();
                $vEvent->setUseTimezone(true);
                $vEvent->setDtStart(\DateTime::createFromFormat(self::DATE_TIME_FORMAT,
                    "".$event->evnt_date_start." ".$event->evnt_time_start));
                $vEvent->setDtEnd(\DateTime::createFromFormat(self::DATE_TIME_FORMAT,
                    "".$event->evnt_date_end." ".$event->evnt_time_end));
                $vEvent->setSummary($event->evnt_title);
                
                $eventLink = "".URL::route('event.show', $event->id);
                $eventTime = $event->evnt_time_start;
                $additionalInfo = $event->evnt_public_info !== "" ? $event->evnt_public_info : "(-)";
                
                $vEvent->setDescription("Link: ".$eventLink."\n"
                    ."\n"
                    .trans('mainLang.begin').": ".$eventTime."\n"
                    ."\n"
                    .trans('mainLang.additionalInfo').":\n"
                    .$additionalInfo."\n");
                
                $place = $event->place->plc_title;
                $vEvent->setLocation($place, $place);
                $vCalendar->addComponent($vEvent);
            }
            
            $keys = Cache::get(self::ICAL_ACCESSOR, []);
            array_push($keys, "ical".$evt_id.Session::has('userGroup'));
            $keys = array_unique($keys);
            Cache::put(self::ICAL_ACCESSOR, $keys, 4 * 60);
            
            return $vCalendar->render();
        });
        
        return response($calendar)
            ->withHeaders([
                'Content-Type'        => 'text/calendar',
                'charset'             => 'utf-8',
                'Content-Disposition' => 'attachment; filename="lara-event-'.$evt_id.'.ics"',
            ]);
    }
    
    
    /** generate links for ui */
    public function generateLinks()
    {
        $userId = Session::get('userId');
        
        $person = Person::where('prsn_ldap_id', '=', $userId)->first();
        
        $result = [];
        
        $places = Place::where('plc_title', '<>', '-')->get();
        
        $result['allPublicEvents'] = URL::route('icalallevents');
        
        foreach ($places as $place) {
            
            if (Session::has('userGroup')) {
                $placeLink = [$place->plc_title => URL::to('/').'/ical/feed/'.$place->place_uid."/1"];
                $result['location'][] = $placeLink;
            }
            $publicLinks = [$place->plc_title => URL::to('/')."/ical/location/".$place->plc_title];
            
            $result['locationPublic'][] = $publicLinks;
            $result['locationName'][] = $place->plc_title;
        }
        
        if (!Session::has('userId')) {
            $result['isPublic'] = true;
        } else {
            $result['isPublic'] = false;
        }
        
        if (!is_null($person) && Session::has('userId')) {
            $result['personal'] = URL::to('/').'/ical/events/user/'.$person->prsn_uid.'/';
        }
        
        return response()->json($result);
    }
    
    
    /**
     * Will change the publishing state of a given event to the opposite
     * @param  int $id for event ID
     * @return Redirect back
     */
    public function togglePublishState($id)
    {
        
        // Find event
        $event = ClubEvent::findOrFail($id);
        
        // Check credentials: you can only delete, if you have rights for marketing or management. 
        if (!Session::has('userId')
            OR (Session::get('userGroup') != 'marketing'
                AND Session::get('userGroup') != 'clubleitung'
                AND Session::get('userGroup') != 'admin')
        ) {
            Session::put('message',
                'Du darfst dieses Event nicht veröffentlichen! Frage die Clubleitung oder Markleting ;)');
            Session::put('msgType', 'danger');
            
            return back();
        }
        
        if ($event->evnt_is_published == 1) {
            // was published, intent: unpublish
            
            $event->evnt_is_published = 0;
            $event->save();
            Utilities::clearIcalCache();
            
            // Log the action while we still have the data
            Log::info('Event unpublished: '.Session::get('userName').' ('.Session::get('userId').', '
                .Session::get('userGroup').') unpublished event "'.$event->evnt_title.'" (eventID: '.$event->id.') on '.$event->evnt_date_start.'.');
            
            // Inform the user
            Session::put('message', "Dieses Event wurde erfolgreich aus dem Kalenderfeed entfernt.");
            Session::put('msgType', 'danger');
            
            return back();
            
        } else {
            // was unpublished, intent: publish
            
            $event->evnt_is_published = 1;
            $event->save();
            Utilities::clearIcalCache();
            
            // Log the action while we still have the data
            Log::info('Event published: '.Session::get('userName').' ('.Session::get('userId').', '
                .Session::get('userGroup').') published event "'.$event->evnt_title.'" (eventID: '.$event->id.') on '.$event->evnt_date_start.'.');
            
            // Inform the user
            Session::put('message', "Dieses Event wurde erfolgreich zum Kalenderfeed hinzugefügt.");
            Session::put('msgType', 'success');
            
            return back();
        }
    }
    
}