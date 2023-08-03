data-total-shifts="{{$clubEvent->shifts->count()}}"
data-empty-shifts="{{$clubEvent->shifts->filter(function ($shift) {
    return is_null($shift->person_id) && $shift->optional == 0;
 })->count()}}"
data-opt-empty-shifts="{{$clubEvent->shifts->filter(function ($shift) {
    return is_null($shift->person_id) && $shift->optional == 1;
 })->count()}}"