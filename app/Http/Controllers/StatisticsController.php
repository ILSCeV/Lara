<?php

namespace Lara\Http\Controllers;

use DateTime;
use Illuminate\Support\Collection;
use Lara\Http\Middleware\ClOnly;
use Lara\Http\Middleware\RejectGuests;
use Lara\Person;
use Lara\Shift;
use Lara\StatisticsInformation;
use Redirect;
use View;

class StatisticsController extends Controller
{
    CONST STATISTIC_SELECT = /** @lang MariaDB */
        "select p.id personId,
           coalesce(SUM(own_section_shifts.statistical_weight), 0)   own_section,
           coalesce(SUM(other_section_shifts.statistical_weight), 0) other_section
    from persons p
             join users u on u.person_id = p.id
             join (
        select ssh.person_id, ssh.id, sce.plc_id
        from shifts ssh
                 join schedules ssched on ssh.schedule_id = ssched.id
                 join club_events sce on ssched.evnt_id = sce.id
                 join persons sp on sp.id = ssh.person_id
        where ssh.person_id is not null
          and sce.evnt_date_start >= cast(:start as date)
          and sce.evnt_date_end <= date_add(cast(:end as date),interval 1 day )
    ) relevant_shifts on relevant_shifts.person_id = p.id
             left outer join shifts own_section_shifts
                             on p.id = own_section_shifts.person_id and relevant_shifts.id = own_section_shifts.id and
                                relevant_shifts.plc_id = u.section_id
             left outer join shifts other_section_shifts
                             on p.id = other_section_shifts.person_id and
                                relevant_shifts.id = other_section_shifts.id and relevant_shifts.plc_id <> u.section_id
    where prsn_ldap_id is not null
    group by p.id, u.name
    order by u.name, p.id";

    public function __construct()
    {
        $this->middleware(RejectGuests::class);
    }

    public function showStatistics($year = null, $month = null)
    {
        if (!isset($year)) {
            $year = strftime('%Y');
        }
        if (!isset($month)) {
            $month = strftime('%m');
        }
        //return CacheUtility::remember('month-statistics-view-'.$year.'-'.$month, function () use ($year, $month) {
            return $this->showStatisticsInternal($year, $month);
        //});
    }

    private function showStatisticsInternal($year = null, $month = null)
    {
        $from = new DateTime($year.'-'.$month.'-01');
        $till = new DateTime($from->format('Y-m-d'));
        $till->modify('next month')->modify('-1 day');
        $isMonthStatistic = 1;
        list($clubInfos, $infos) = $this->generateStatisticInformationForSections($from, $till);

        return View::make('statisticsView',
            compact('infos', 'clubInfos', 'year', 'month', 'isMonthStatistic'))->render();

    }

    public function showYearStatistics($year = null)
    {
        if (!isset($year)) {
            $year = strftime('%Y');
        }
       // return CacheUtility::remember('year-statistics-view-'.$year, function () use ($year) {
            return $this->showYearStatisticsInternal($year);
       // });
    }

    private function showYearStatisticsInternal($year = null)
    {
        if (!isset($year)) {
            $year = strftime('%Y');
        }
        $from = new DateTime($year.'-'.'01-01');
        $till = new DateTime($from->format('Y-m-d'));
        $till->modify('next year')->modify('-1 day');
        $isMonthStatistic = 0;

        list($clubInfos, $infos) = $this->generateStatisticInformationForSections($from, $till);
        $month = $till->format("m");

        return View::make('statisticsView',
            compact('infos', 'clubInfos', 'year', 'month', 'isMonthStatistic'))->render();
    }

    /**
     * Returns list of all shifts a selected person did in a chosen month, with some associated metadata
     *
     * @param  int $id
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     * @throws \Exception
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
     *
     * @param DateTime $from
     * @param DateTime $till
     * @return array
     */
    private function generateStatisticInformationForSections($from, $till)
    {
        $queryResults = \DB::select(self::STATISTIC_SELECT, ["start" => $from, "end" => $till]);
        $groupedInformation = collect($queryResults)->map(function ($row) {
            $info = new StatisticsInformation();
            $info->user = Person::query()->whereKey($row->personId)->first();
            $info->userClub = $info->user->club;
            $info->inOwnClub = $row->own_section;
            $info->inOtherClubs = $row->other_section;

            return $info;
        })->groupBy(function (StatisticsInformation $item) {
            return $item->userClub->id;
        });
        $clubInfos = $groupedInformation->flatMap(function (Collection $item) {
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
