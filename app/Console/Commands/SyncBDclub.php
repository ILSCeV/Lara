<?php

namespace Lara\Console\Commands;

use Carbon\Carbon;
use ICal\Event;
use ICal\ICal;
use Illuminate\Console\Command;
use Lara\Club;
use Lara\ClubEvent;
use Lara\Logging;
use Lara\Person;
use Lara\Schedule;
use Lara\Section;
use Lara\Shift;
use Lara\Template;

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
    const BD_TEMPLATE_NAME = 'BD Template';

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
            $club = new Club();
            $club->clb_title = $section->title;
            $club->save();
        }


        passthru('sh backup-calendars.sh ' . config('bd_credentials.host'), $result);
        
        $this->info('result: ' . $result);
        $calendars = file_get_contents(config('bd_credentials.searchdir') . '/filelist');
        $events = explode("\n", $calendars);

        //$this->info(var_dump($arrayOfCalendars));

        /* @var $template Template */
        $template = Template::where('title', '=', self::BD_TEMPLATE_NAME)->first();


        //$this->info(var_dump($events));
        foreach ($events as $event) {
            $ical = new ICal();
            try {
                $ical->initFile(config('bd_credentials.searchdir') . '/' . $event);
            } catch (\Exception $exception) {
                continue;
            }
            $icalEvents = $ical->events();
            /* @var $icevt Event */
            foreach ($icalEvents as $icevt) {
                $this->info($icevt->summary);
                //$this->info($icevt->comment);
                if (isset($icevt->comment)) {
                    $extraData = json_decode($icevt->comment);
                } else {
                    $extraData = json_decode("{\"isactive\":null,\"responsible\":\"\",\"music\":\"\",\"flyer\":\"\",\"flyerdone\":null,\"face\":\"\",\"facedone\":null,\"price_ak_n\":\"\",\"price_ak_v\":\"\",\"price_vk_n\":\"\",\"price_vk_v\":\"\",\"notes\":\"\"}");
                }
                if (is_null($extraData)) {
                    $extraData = json_decode("{\"isactive\":null,\"responsible\":\"\",\"music\":\"\",\"flyer\":\"\",\"flyerdone\":null,\"face\":\"\",\"facedone\":null,\"price_ak_n\":\"\",\"price_ak_v\":\"\",\"price_vk_n\":\"\",\"price_vk_v\":\"\",\"notes\":\"\"}");
                }
                $this->info("extradata:" . json_encode($extraData));
                //$this->info(var_dump($extraData));
                $this->info($icevt->uid);

                /* @var $clubEvent ClubEvent */
                $clubEvent = ClubEvent::where('external_id', '=', $icevt->uid)->first();

                if (is_null($clubEvent)) {
                    $this->info('Create new event for ' . $icevt->summary);
                    $clubEvent = $template->toClubEvent();
                    $this->info('event created: ' . json_encode($clubEvent->shifts));
                    $eventCreated = true;
                } else if ($clubEvent->was_manually_edited){
                    $this->info('Skipping Event' . $icevt->summary . '. It was manually edited.');
                    continue;
                } else {
                    $this->info('update event ' . $icevt->summary);
                }

                $clubEvent->evnt_title = $icevt->summary;
                $clubEvent->external_id = $icevt->uid;
                $clubEvent->evnt_is_private = !(!is_null($extraData->isactive) && $extraData->isactive == 'on');
                $clubEvent->evnt_date_start = (new \DateTime($icevt->dtstart))->format('Y-m-d');
                $clubEvent->evnt_date_end = (new \DateTime($icevt->dtend))->format('Y-m-d');
                $clubEvent->evnt_time_start = '21:00:00';
                $clubEvent->evnt_time_end = '01:00:00';
                $clubEvent->plc_id = $section->id;
                $clubEvent->price_normal = $extraData->price_ak_n;
                $clubEvent->price_external = $extraData->price_ak_v;
                $clubEvent->price_tickets_external = $extraData->price_vk_v;
                $clubEvent->price_tickets_normal = $extraData->price_vk_n;
                $clubEvent->save();
                $clubEvent->showToSection()->sync($section->id);

                $clubEvent->facebook_done = $extraData->facedone != null && $extraData->facedone == "on";
                $clubEvent->event_url = $extraData->face;
                $flyerDone = "Flyer erledigt: " . ($extraData->flyerdone != null ? "Ja" : "Nein");

                $clubEvent->evnt_private_details = $flyerDone . "\n\n" . $extraData->notes;

                /* @var $schedule Schedule */
                $schedule = $clubEvent->schedule;
                if (is_null($schedule)) {
                    $schedule = new Schedule();
                    //Logging::scheduleCreated($schedule);
                }
                $schedule->evnt_id = $clubEvent->id;
                $schedule->schdl_title = $clubEvent->evnt_title;
                $schedule->schdl_time_preparation_start = '20:00:00';

                $schedule->save();
                if (isset($eventCreated)) {
                    $shifts = $clubEvent->shifts;
                    /* @var $shift Shift */
                    foreach ($shifts as $shift) {
                        if ($shift->type->title == 'AV' && !is_null($extraData->responsible) && $extraData->responsible != '') {
                            $person = $this->createGuestAccount();
                            $person->prsn_name = $extraData->responsible;
                            $person->save();
                            $shift->person_id = $person->id;
                        }
                        if ($shift->type->title == 'Musik' && !is_null($extraData->music) && $extraData->music != '') {
                            $person = $this->createGuestAccount();
                            $person->prsn_name = $extraData->music;
                            $person->save();
                            $shift->person_id = $person->id;
                        }
                        $shift->save();
                    }
                }

                $clubEvent->save();
            }
        }
        $this->deleteDir(config('bd_credentials.searchdir'));
        
        return 0;
    }

    private function deleteDir($dirPath)
    {
        if (!is_dir($dirPath)) {
            throw new \InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }

    /** @param $date \DateTime
     * @return string
     */
    private function formatDateTime($date)
    {
        return $date->format(self::DATE_TIME_FORMAT_PREFIX) . 'T' . $date->format(self::DATE_TIME_FORMAT_SUFFIX) . 'Z';
    }

    /**
     * @return Person
     */
    private function createGuestAccount()
    {
        $personClub = Club::where('clb_title', '=',
            self::BD_SECTION_NAME)->firstOrCreate(['clb_title' => self::BD_SECTION_NAME]);
        $person = Person::create(['prsn_ldap_id' => null]);
        $person->prsn_status = "";
        $person->clb_id = $personClub->id;
        $person->updated_at = Carbon::now();

        return $person;
    }
}
