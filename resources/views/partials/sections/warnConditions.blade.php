@php
/**
 * @var \Lara\EventWarnConditions $eventWarnCondition
 * @var int $counter
 */
@endphp

<div class="form-row">
    <div class="col align-content-center">
        <label for="theDateStart{{$counter}}">Datum von </label>
        <input id="theDateStart{{$counter}}" class="form-control" type="date" name="warnDateStart[]" placeholder="Datum" />
    </div>
    <div class="col">
        <label for="theDateEnd{{$counter}}">Datum bis </label>
        <input id="theDateEnd{{$counter}}" class="form-control" type="date" name="warnDateEnd[]" placeholder="Datum" />
    </div>
    <div class="col">
        <label for="weekday{{$counter}}">Wochentag</label>
        @include('partials.inputs.weekdayInput', ['id' => 'weekday'.$counter, 'name'=>'weekday[]', 'class'=> 'selectpicker'])
    </div>
    <div class="col">
        <label for="warnTimeStart{{$counter}}">Uhrzeit von</label>
        <input class="form-control" id="warnTimeStart{{$counter}}" type="time" name="warnTimeStart[]"/>
    </div>
    <div class="col">
        <label for="warnTimeEnd{{$counter}}">Uhrzeit bis</label>
        <input class="form-control" id="warnTimeEnd{{$counter}}" type="time" name="warnTimeEnd[]"/>
    </div>
    <div class="col w-auto">
        <label for="reason{{$counter}}">Begründung</label>
        <input id="reason{{$counter}}" class="form-control" type="text" name="reason[]" required />
    </div>
    <div class="col">
        <button type="button" role="button" class="btn btn-success add-warn-condition-btn">+</button>
    </div>
</div>

