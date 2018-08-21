<?php

namespace Lara\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Lara\Http\Middleware\RejectGuests;
use Lara\Shift;

class UserPersonalPageController extends Controller
{
    public function __construct()
    {
        $this->middleware(RejectGuests::class);
    }
    
    public function edit(){
        $user = \Auth::user();
        $shifts = Shift::query()->where('person_id', '=', $user->person->id)
            ->with("schedule", "schedule.event.section", "schedule.event", "type")
            ->whereHas("schedule.event" , function ($query){
               $query->where('evnt_date_start','>=', new \DateTime());
            } )
            ->get();
        return View::make('userpersonalpage.index',compact(['user','shifts']));
    }
}
