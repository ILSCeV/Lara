<?php

namespace Lara\Http\Controllers;

use DateTime;
use Illuminate\Support\Collection;
use Lara\Person;
use Lara\Shift;
use Lara\StatisticsInformation;
use Redirect;
use View;

class StatisticsController extends Controller
{
    CONST STATISTIC_SELECT = /** @lang MariaDB */
        "select p.id personId,".
        "u.id userId,".
        "u.name,".
        "coalesce((select sum(ssh.statistical_weight)".
        " from shifts ssh ".
        "join schedules ssched on ssh.schedule_id = ssched.id ".
        "join club_events sce on ssched.evnt_id = sce.id ".
        "join persons sp on sp.id = ssh.person_id ".
        "join users su on su.person_id = sp.id ".
        "where su.section_id = sce.plc_id ".
        "and p.id = sp.id ".
        "and sce.evnt_date_start >= :start ".
        "and sce.evnt_date_end <= :end), 0) own_section, ".
        "coalesce((select sum(ssh.statistical_weight) ".
        "from shifts ssh ".
        "join schedules ssched on ssh.schedule_id = ssched.id ".
        "join club_events sce on ssched.evnt_id = sce.id ".
        "join persons sp on sp.id = ssh.person_id ".
        "join users su on su.person_id = sp.id ".
        "where su.section_id <> sce.plc_id ".
        "and p.id = sp.id ".
        "and sce.evnt_date_start >= :start ".
        "and sce.evnt_date_end <= :end), 0) other_section ".
        "from shifts sh ".
        "join schedules sched on sched.id = sh.schedule_id ".
        "join club_events ce on sched.evnt_id = ce.id ".
        "join persons p on p.id = sh.person_id ".
        "join users u on u.person_id = p.id ".
        "where p.prsn_ldap_id is not null ".
        "and ce.evnt_date_start >= :start ".
        "and ce.evnt_date_end <= :end ".
        "group by p.id, u.id, u.name ".
        "order by u.name ";
    
    public function showStatistics($year = null, $month = null)
    {
        if (!isset($year)) {
            $year = strftime('%Y');
        }
        if (!isset($month)) {
            $month = strftime('%m');
        }
        $from = new DateTime($year.'-'.$month.'-01');
        $till = new DateTime($from->format('Y-m-d'));
        $till->modify('next month')->modify('-1 day');
        $isMonthStatistic = 1;
        list($clubInfos, $infos) = $this->generateStatisticInformationForSections($from, $till, $isMonthStatistic);
        
        return View::make('statisticsView',
            compact('infos', 'clubInfos', 'userId', 'year', 'month', 'isMonthStatistic'));
        
    }
    
    public function showYearStatistics($year = null)
    {
        if (!isset($year)) {
            $year = strftime('%Y');
        }
        $from = new DateTime($year.'-'.'01-01');
        $till = new DateTime($from->format('Y-m-d'));
        $till->modify('next year')->modify('-1 day');
        $isMonthStatistic = 0;
        list($clubInfos, $infos) = $this->generateStatisticInformationForSections($from, $till, $isMonthStatistic);
        $month = $till->format("m");
        
        return View::make('statisticsView',
            compact('infos', 'clubInfos', 'userId', 'year', 'month', 'isMonthStatistic'));
    }
    
    /**
     * Returns list of all shifts a selected person did in a chosen month, with some associated metadata
     *
     * @param  int $id
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function shiftsByPerson($id = null)
    {
        // fill empty parameters - no date selected means show current month and year
        if (!isset($id)) {
            return Redirect::action('StatisticsController@showStatistics');
        }
        request("year") ? $year = request("year") : $year = strftime('%Y');
        request("month") ? $month = request("month") : $month = strftime('%m');
        $isMonthStatistic = request("isMonthStatistic") == 0 ? 0 : 1;
        
        // set the time window
        if ($isMonthStatistic) {
            $from = new DateTime($year.'-'.$month.'-01');
            $till = new DateTime($from->format('Y-m-d'));
            $till->modify('next month')->modify('-1 day');
        } else {
            $from = new DateTime($year.'-'.'01-01');
            $till = new DateTime($from->format('Y-m-d'));
            $till->modify('next year')->modify('-1 day');
        }
        // get all shifts in selected time window, for selected person, with their attributes
        $shifts = Shift::where('person_id', '=', $id)
            ->whereHas('schedule.event', function ($query) use ($from, $till) {
                $query->whereBetween('evnt_date_start', [$from->format('Y-m-d'), $till->format('Y-m-d')]);
            })
            ->with('type', 'schedule.event.section')
            ->get()
            ->sortBy('schedule.event.evnt_date_start');
        // format the response
        $response = [];
        $ownClub = Person::find($id)->club->clb_title;
        
        
        foreach ($shifts as $shift) {
            $clubsOfShift = $shift->schedule->event->showToSectionNames();
            
            $response[] = [
                'id'           => $shift->id,
                'shift'        => $shift->type->title(),
                'event'        => $shift->schedule->event->evnt_title,
                'event_id'     => $shift->schedule->event->id,
                'section'      => $shift->schedule->event->section->title,
                'sectionColor' => $shift->schedule->event->section->color,
                'isOwnClub'    => in_array($ownClub, $clubsOfShift),
                'date'         => strftime("%d.%m.%Y (%a)", strtotime($shift->schedule->event->evnt_date_start)),
                'weight'       => $shift->statistical_weight,
            ];
        }
        
        return response()->json($response);
    }
    
    /**
     * @param bool $isMonthStatistic
     * @param DateTime $from
     * @param DateTime $till
     * @return array
     */
    private function generateStatisticInformationForSections($from, $till, $isMonthStatistic)
    {
        
        $queryResults = \DB::select(str_replace(':end', '\''.$till->format('Y-m-d').'\'',
            str_replace(':start', '\''.$from->format('Y-m-d').'\'', self::STATISTIC_SELECT)));
        $groupedInformations = collect($queryResults)->map(function ($row) {
            $info = new StatisticsInformation();
            $info->user = Person::query()->whereKey($row->personId)->first();
            $info->userClub = $info->user->club;
            $info->inOwnClub = $row->own_section;
            $info->inOtherClubs = $row->other_section;
            
            return $info;
        })->groupBy(function (StatisticsInformation $item) {
            return $item->userClub->id;
        });
        $clubInfos = $groupedInformations->flatMap(function (Collection $item) {
            $club = $item->first()->userClub;
            $maxShift = $item->max(function (StatisticsInformation $info) {
                return $info->inOwnClub + $info->inOtherClubs;
            });
            $maxShift = max($maxShift, 1);
            $infos = $item->map(function (StatisticsInformation $info) use ($maxShift) {
                $info->shiftsPercentIntern = $info->inOwnClub / ($maxShift * 1.5) * 100;
                $info->shiftsPercentExtern = $info->inOtherClubs / ($maxShift * 1.5) * 100;
                
                return $info;
            });
            
            return [$club->clb_title => $infos];
        });
        
        $infos = $clubInfos->flatten();
        
        return [$clubInfos, $infos];
    }
}
