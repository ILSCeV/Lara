
@php
use Illuminate\Support\Collection;
    use Lara\Shift;
    /** @var Collection|Shift $shifts
     ** @var string autocomplete
    */
@endphp

{!! Form::open(  array( 'route' => ['shift.update', $shift->id],
	                                'method' => 'PUT',
	                                'class' => 'shift form-inline col-12 '. $autocomplete,
	                                 'data-shiftid'=>$shift->id)  ) !!}
