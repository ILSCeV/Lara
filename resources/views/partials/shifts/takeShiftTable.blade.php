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

<table class="table-no-bordered container-fluid">
    <thead>
    <tr>
        <th ></th>
        <th style="width: 13%"></th>
        <th ></th>
        <th ></th>
        <th ></th>
        <th ></th>
        <th class="{{$commentClass}}" ></th>
    </tr>
    </thead>
    <tbody>
    @foreach( $shifts as $shift)
        @include('partials.shifts.takeShiftBar',['shift'=>$shift,'hideComments'=>$hideComments])
        {{-- Show a line after each row except the last one --}}
        @if($shift !== $shifts->last() )
            <tr></tr>
        @endif
    @endforeach
    </tbody>
</table>
