<!-- Needs variables: entry, persons -->
<div class="col-xs-2 col-md-2 no-padding" id="clubStatus{{ $entry->id }}">
    @include("partials.ScheduleEntryStatus", [$entry, $persons])
</div>

@if( is_null($entry->getPerson) )

    {!! Form::text('userName' . $entry->id, 
                 Input::old('userName' . $entry->id), 
                 array('placeholder'=>'=FREI=', 
                       'id'=>'userName' . $entry->id, 
                       'class'=>'col-xs-8 col-md-8')) 
    !!}

@else

    {!! Form::text('userName' . $entry->id, 
                 $entry->getPerson->prsn_name, 
                 array('id'=>'userName' . $entry->id, 
                       'class'=>'col-xs-8 col-md-8') ) 
    !!}
  
@endif

<div class="col-xs-2 col-md-2 input-append btn-group no-padding">
    @include("partials.dropdownUsernames", $persons)
</div>

<div>
    @include("partials.scheduleEntryLdapId")
</div>