@php
    use Illuminate\Support\Collection;
    use Lara\Shift;
    /** @var Collection|Shift $shifts
     * @var boolean hideComments
     * @var boolean commentsInSeparateLine
    */


if($hideComments){
    $commentClass = 'hide';
} else {
    $commentClass = '';
}
@endphp
<div class=" @if($commentsInSeparateLine) container @endif">
    @foreach( $shifts as $shift)
        @include('partials.shifts.takeShiftBar',['shift'=>$shift,'hideComments'=>$hideComments,'commentsInSeparateLine' => $commentsInSeparateLine])
    @endforeach
</div>
