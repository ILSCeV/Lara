<?php

namespace Lara\Http\Controllers;

use Request;
use Redirect;
use View;

use Lara\Http\Requests;
use Lara\Http\Controllers\Controller;

use Lara\ClubEvent;

class DateController extends Controller {

    /**
     * Fills missing parameters: if no day specified use current date.
     *
     * @return  int $day
     * @return  int $month
     * @return  int $year
     * @return RedirectResponse
     */       
    public function currentDate()
    {
        return Redirect::action( 'DateController@showDate', ['year' => date("Y"), 
                                                             'month' => date("m"), 
                                                             'day' => date("d")] );
    }


     /**
     * Generates the view for the list of all events on a specific date.
     *
     * @param  int $year
     * @param  int $month
     * @param  int $day
     *
     * @return view calendarView
     * @return ClubEvent[] $events
     * @return string $date
     */      
    public function showDate($year, $month, $day)
    {
        $dateInput = $year.$month.$day;

        $date = strftime("%a, %d. %b %Y", strtotime($dateInput));

        $events = ClubEvent::where('evnt_date_start','=',$dateInput)
                           ->with('getSection')
                           ->paginate(15);

        return View::make('listView', compact('events', 'date'));
    }

}
