<div class="col-md-3 col-xs-3 no-padding" id="clubStatus{{ $entry->id }}">
    @include("partials.scheduleEntryStatus")
</div>

@if( is_null($entry->getPerson) )
    {!! Form::text('userName' . $entry->id, 
                 Input::old('userName' . $entry->id), 
                 array('placeholder'=>'=FREI=', 
                       'id'=>'userName' . $entry->id, 
                       'class'=>'col-xs-8 col-md-8',
                       'autocomplete'=>'off',
                       'data-toggle'=>'tooltip',
                       'data-placement'=>'top', 
                       'title'=>'',
                       'data-original-title'=>'')) 
    !!}
@else
    
    {!! Form::text('userName' . $entry->id, 
                 $entry->getPerson->prsn_name, 
                 array('id'=>'userName' . $entry->id, 
                       'class'=>'col-xs-8 col-md-8',
                        'autocomplete'=>'off') ) 
    !!}
@endif

<ul class="dropdown-menu" style="position: absolute;" id="dropdown">
    <li id="yourself">
        <a href="javascript:void(0);" 
           onClick="document.getElementById('userName{{ ''. $entry->id }}').value='{{Session::get('userName')}}';
                    document.getElementById('club{{ ''. $entry->id }}').value='{{Session::get('userClub')}}';
                    document.getElementById('ldapId{{ ''. $entry->id }}').value='{{Session::get('userId')}}'
                        document.getElementById('btn-submit-changes{{ ''. $entry->id }}').click();">
            <b>Ich mach's!</b>
        </a>
    </li>
</ul>

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
