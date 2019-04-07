@php
    /***
    * @var \Lara\Shift $shift
    * @var boolean hideComments
    */

if(Auth::user()){
    $autocomplete = 'autocomplete';
} else {
    $autocomplete = '';
}

if($hideComments){
    $commentClass = 'hide';
} else {
    $commentClass = '';
}

@endphp


    <tr class="shiftRow {!! ( isset($shift->person->prsn_ldap_id)
                                                  && Auth::user()
                                                  && $shift->person->prsn_ldap_id === Auth::user()->person->prsn_ldap_id) ? "my-shift" : false !!}">
        <td>
            @include('partials.shifts.updateShiftFormOpener',['shift'=>$shift, 'autocomplete'=>$autocomplete])
            {{-- SPAMBOT HONEYPOT - this field will be hidden, so if it's filled, then it's a bot or a user tampering with page source --}}
            <div class="welcome-to-our-mechanical-overlords">
                <small>If you can read this this - refresh the page to update CSS styles or switch CSS support on.</small>
                <input type="text" id="{!! 'website' . $shift->id !!}" name="{!! 'website' . $shift->id !!}" value=""/>
            </div>
            <button type="submit" class="hidden hide"></button>
            {!! Form::close() !!}
        </td>
        <td>
            @include("partials.shiftTitle")
        </td>
        <td>
            @include('partials.shifts.updateShiftFormOpener',['shift'=>$shift, 'autocomplete'=>$autocomplete])
            <div id="clubStatus{{ $shift->id }}">
                @include("partials.shiftStatus")
            </div>
            {!! Form::close() !!}
        </td>
        @if( isset($shift->getPerson->prsn_ldap_id) && !Auth::user())
            <td>
                <div class="form-group form-group-sm">
                    @if($shift->getPerson->isNamePrivate() == 0)
                        {{-- Shift USERNAME--}}
                        <div id="{!! 'userName' . $shift->id !!}" class="form-control form-control-sm">
                            {!! $shift->getPerson->prsn_name !!}
                        </div>
                    @else
                        <div id="{!! 'userName' . $shift->id !!}" class="form-control form-control-sm">
                            @if(isset($shift->person->user))
                                {{ trans($shift->person->user->section->title . '.' . $shift->person->user->status) }}
                            @endif
                        </div>
                    @endif
                </div>
            </td>
            {{-- SHIFT CLUB --}}
            <td>
                <div id="{!! 'club' . $shift->id !!}" class="form-group form-group-sm ">
                    <div class="form-control form-control-sm">
                        {!! "(" . $shift->getPerson->getClub->clb_title . ")" !!}
                    </div>
                </div>
            </td>
            <td>
            <span class="float-left">
                <button class="showhide btn btn-sm btn-secondary">@if($shift->comment === "") <i class="far fa-comment"></i> @else <i class="fas fa-comment"></i> @endif</button>
		    </span>
            </td>
            {{-- COMMENT SECTION --}}
            <td>
                <div class="form-group from-group-sm hidden-print word-break ">
                    <div class="form-control form-control-sm">
                        <span class="w-auto @if(isset($hideComments) && $hideComments) hide @endif" id="{{'comment'.$shift->id}}"
                          name="{{'comment' . $shift->id}}">{!! !empty($shift->comment) ? $shift->comment : "-" !!}</span>
                    </div>
                </div>
            </td>
        @else
            {{-- show everything for members --}}
            {{-- SHIFT STATUS, USERNAME, DROPDOWN USERNAME and LDAP ID --}}
            <td>
                @include('partials.shifts.updateShiftFormOpener',['shift'=>$shift, 'autocomplete'=>$autocomplete])
                <div class="form-group form-group-sm">
                    @include('partials.shifts.shiftName',['shift'=>$shift])
                </div>
                <button type="submit" class="hidden hide"></button>
                {!! Form::close() !!}
            </td>
            {{-- SHIFT CLUB and DROPDOWN CLUB --}}
            <td>
                @include('partials.shifts.updateShiftFormOpener',['shift'=>$shift, 'autocomplete'=>$autocomplete])
                <div class="form-group form-group-sm ">
                    @include('partials.shifts.shiftClub',['shift'=>$shift])
                </div>
                <button type="submit" class="hidden hide"></button>
                {!! Form::close() !!}
            </td>
            {{-- COMMENT SECTION --}}
            {{-- Hidden comment field to be opened after the click on the icon
                 see filter-scripts "Show/hide comments" function --}}
            <td>
                <div class="form-control form-control-sm">
                    <span class="float-left">
                        <button class="showhide btn btn-sm btn-secondary">@if($shift->comment === "") <i class="far fa-comment"></i> @else <i class="fas fa-comment"></i> @endif</button>                        &nbsp;&nbsp;
                    </span>
                </div>
            </td>
            <td>
                @include('partials.shifts.updateShiftFormOpener',['shift'=>$shift, 'autocomplete'=>$autocomplete])
                <div class="form-group from-group-sm hidden-print word-break ">

                    {!! Form::text('comment' . $shift->id,
                                               $shift->comment,
                                               ['placeholder'=>Lang::get('mainLang.addCommentHere'),
                                                     'id'=>'comment' . $shift->id,
                                                      'name'=>'comment' . $shift->id,
                                                     'class'=>'form-control form-control-sm '. $commentClass])
                                    !!}

                </div>
                <button type="submit" class="hidden hide"></button>
                {!! Form::close() !!}
            </td>
        @endif
    </tr>


