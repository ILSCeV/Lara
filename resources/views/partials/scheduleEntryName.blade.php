<div class="col-md-3 col-xs-3 no-padding" id="clubStatus{{ $entry->id }}">
    @include("partials.scheduleEntryStatus")
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

<div class="col-xs-1 col-md-1 input-append btn-group no-padding">
    @if(Session::has("userName"))
        @include("partials.dropdownUsernames", $persons)
    @else
        &nbsp;
    @endif
</div>

<div>
    @if( is_null($entry->getPerson) )
        {!! Form::hidden('ldapId' . $entry->id, 
                         '', 
                         array('id'=>'ldapId' . $entry->id) ) 
        !!}
    @else
        {!! Form::hidden('ldapId' . $entry->id, 
                         $entry->getPerson->prsn_ldap_id, 
                         array('id'=>'ldapId' . $entry->id) ) 
        !!}
    @endif
</div>

<div>
    @if( is_null($entry->getPerson) )
        {!! Form::hidden('timestamp' . $entry->id, 
                         '', 
                         array('id'=>'timestamp' . $entry->id) ) 
        !!}
    @else
        {!! Form::hidden('timestamp' . $entry->id, 
                         $entry->updated_at, 
                         array('id'=>'timestamp' . $entry->id) ) 
        !!}
    @endif
</div>
