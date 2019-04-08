@php
    use Illuminate\Support\Collection;
    use Lara\Shift;
    /** @var Collection|Shift $shifts
     ** @var boolean hideComments
    */


if($hideComments){
    $commentClass = 'hide';
} else {
    $commentClass = '';
}
@endphp
<div class="container">
@foreach( $shifts as $shift)
    @include('partials.shifts.takeShiftBar',['shift'=>$shift,'hideComments'=>$hideComments])
@endforeach
</div>
