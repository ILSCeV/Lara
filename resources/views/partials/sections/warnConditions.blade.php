@php
/**  */
@endphp
{!! Form::open(['class' => 'form-inline']) !!}
<div class="form-row">
    <div class="col">
        <label for="theDateStart">Datum von </label>
        <input id="theDateStart" class="form-control" type="date" name="warnDateFrom" placeholder="Datum" />
    </div>
    <div class="col">
        <label for="theDateEnd">Datum bis </label>
        <input id="theDateEnd" class="form-control" type="date" name="warnDateUntil" placeholder="Datum" />
    </div>
    <div class="col">
        <label for="weekday">Wochentag</label>
        @include('partials.inputs.weekdayInput', ['id' => 'weekday', 'name'=>'weekday', 'class'=> 'selectpicker'])
    </div>
    <div class="col">
        <label for="warnTimeStart">Uhrzeit von</label>
        <input class="form-control" id="warnTimeStart" type="time" name="warnTimeStart"/>
    </div>
    <div class="col">
        <label for="warnTimeEnd">Uhrzeit bis</label>
        <input class="form-control" id="warnTimeEnd" type="time" name="warnTimeEnd"/>
    </div>
    <div class="col">
        <label for="reason">Begründung</label>
        <input id="reason" class="form-control" type="text" name="reason" required />
    </div>
</div>
{{Form::close()}}
