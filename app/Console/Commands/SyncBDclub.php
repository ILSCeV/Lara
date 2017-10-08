<?php

namespace Lara\Console\Commands;

use ICal\Event;
use ICal\ICal;
use Illuminate\Console\Command;
use it\thecsea\simple_caldav_client\SimpleCalDAVClient;
use Lara\ClubEvent;
use Lara\Schedule;
use Lara\Section;

/**
 * To work with this, you need a file named bd_credentials.php in the config folder
 * content:
 * <code>
 * return [
 * 'host'=>
 * 'example.com',
 * 'user' => 'foo',
 * 'password' => 'bar',
 * 'calendarName' =>'foobar'
 * ];
 * </code>
 */
class SyncBDclub extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lara:sync-bd-club';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'download all events from bd club';
    
    const DATE_TIME_FORMAT_PREFIX = 'Ymd';
    
    const DATE_TIME_FORMAT_SUFFIX = 'His';
    
    const BD_SECTION_NAME = 'bd-Club';
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        
        $section = Section::where('title', '=', self::BD_SECTION_NAME)->first();
        if (is_null($section)) {
            $section = new Section();
            $section->title = self::BD_SECTION_NAME;
            $section->section_uid = hash("sha512", uniqid());
            $section->save();
        }
        
        
        $caldavClient = new SimpleCalDAVClient();
        
        
        $caldavClient->connect(config('bd_credentials.host'),
            config('bd_credentials.user'), config('bd_credentials.password'));
        $arrayOfCalenars = $caldavClient->findCalendars();
        $caldavClient->setCalendar($arrayOfCalenars[config('bd_credentials.calendarName')]);
        // $this->info(var_dump($arrayOfCalenars));
        $gmt = new \DateTimeZone('GMT');
        $year = strftime('%Y');
        $month = strftime('%m');
        
        $from = new \DateTime($year.'-'.$month.'-01');
        $until = new \DateTime($from->format('Y-m-d'));
        $from->modify('previous month');
        $until->modify('next month')->modify('-1 day');
        $from->setTimezone($gmt);
        $until->setTimezone($gmt);
        
        //$this->info($this->formatDateTime($from));
        $events = $caldavClient->getEvents($this->formatDateTime($from), $this->formatDateTime($until));
        
        //$this->info(var_dump($events));
        foreach ($events as $event) {
            $ical = new ICal();
            $ical->initString($event->getData());
            $icalEvents = $ical->events();
            /* @var $icevt Event */
            foreach ($icalEvents as $icevt) {
                $this->info($icevt->summary);
                //$this->info($icevt->comment);
                $extraData = json_decode($icevt->comment);
                //$this->info(var_dump($extraData));
                $this->info($icevt->uid);
                
                /* @var $clubEvent ClubEvent */
                $clubEvent = ClubEvent::where('external_id', '=', $icevt->uid)->first();
                if (is_null($clubEvent)) {
                    $this->info('Create new event for '.$icevt->summary);
                    $clubEvent = new ClubEvent();
                } else {
                    $this->info('update event '.$icevt->summary);
                }
                
                $clubEvent->evnt_title = $icevt->summary;
                $clubEvent->external_id = $icevt->uid;
                $clubEvent->evnt_is_private = !(!is_null($extraData->isactive) && $extraData->isactive == 'on');
                $clubEvent->evnt_date_start = (new \DateTime($icevt->dtstart))->format('Y-m-d');
                $clubEvent->evnt_date_end = (new \DateTime($icevt->dtend))->format('Y-m-d');
                $clubEvent->evnt_time_start = '22:00:00';
                $clubEvent->evnt_time_end = '01:00:00';
                $clubEvent->plc_id = $section->id;
                $clubEvent->save();
                $clubEvent->showToSection()->sync($section->id);
                
                /* @var $schedule Schedule */
                $schedule = $clubEvent->schedule;
                if (is_null($schedule)) {
                    $schedule = new Schedule();
                }
                $schedule->evnt_id = $clubEvent->id;
                $schedule->schdl_title = $clubEvent->evnt_title;
                $schedule->schdl_time_preparation_start = '21:00:00';
                
                $schedule->save();
                
                $clubEvent->save();
                $this->info(var_dump($extraData));
                //     $this->info(var_dump($clubEvent));
            }
            // $this->info(var_dump($ical->events()));
        }
    }
    
    /** @param $date \DateTime
     * @return string
     */
    private function formatDateTime($date)
    {
        return $date->format(self::DATE_TIME_FORMAT_PREFIX).'T'.$date->format(self::DATE_TIME_FORMAT_SUFFIX).'Z';
    }
}
