<div class="col-md-3 col-xs-3 no-padding" id="clubStatus{{ $shift->id }}">
    @include("partials.shiftStatus")
</div>

@if( is_null($shift->getPerson) )
    {!! Form::text('userName' . $shift->id, 
                 Input::old('userName' . $shift->id), 
                 array('placeholder'=>'=FREI=', 
                       'id'=>'userName' . $shift->id, 
                       'class'=>'col-xs-8 col-md-8 ',
                       'autocomplete'=>'off')) 
    !!}
@else
    
    {!! Form::text('userName' . $shift->id, 
                 $shift->getPerson->prsn_name, 
                 array('id'=>'userName' . $shift->id, 
                       'class'=>'col-xs-8 col-md-8',
                        'autocomplete'=>'off') ) 
    !!}
@endif

{{-- Show dropdowns only for members --}}
@if (Session::has("userName"))
    <ul class="dropdown-menu dropdown-username" style="position: absolute;">
        <li id="yourself">
            <a href="javascript:void(0);"
               onClick="document.getElementById('userName{{ ''. $shift->id }}').value='{{Session::get('userName')}}';
                       document.getElementById('club{{ ''. $shift->id }}').value='{{Session::get('userClub')}}';
                       document.getElementById('ldapId{{ ''. $shift->id }}').value='{{Session::get('userId')}}';
                       document.getElementById('btn-submit-changes{{ ''. $shift->id }}').click();
                       $(this).parents('.row').addClass('my-shift')">
                <b>{{ trans('mainLang.IDoIt') }}</b>
            </a>
        </li>
    </ul>
@endif

<div>
    @if( is_null($shift->getPerson) )
        {!! Form::hidden('ldapId' . $shift->id, 
                         '', 
                         array('id'=>'ldapId' . $shift->id) ) 
        !!}
    @else
        {!! Form::hidden('ldapId' . $shift->id, 
                         $shift->getPerson->prsn_ldap_id, 
                         array('id'=>'ldapId' . $shift->id) ) 
        !!}
    @endif
</div>

<div>
    @if( is_null($shift->getPerson) )
        {!! Form::hidden('timestamp' . $shift->id, 
                         '', 
                         array('id'=>'timestamp' . $shift->id) ) 
        !!}
    @else
        {!! Form::hidden('timestamp' . $shift->id, 
                         $shift->updated_at, 
                         array('id'=>'timestamp' . $shift->id) ) 
        !!}
    @endif
</div>
