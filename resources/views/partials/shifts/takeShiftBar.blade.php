@php
    /***
    * @var \Lara\Shift $shift
    * @var boolean $hideComments
    * @var boolean $commentsInSeparateLine
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
if($commentsInSeparateLine){
    $shiftTitleColumnClass = 'col-3 p-0';
    $personNameColumnClass = 'col-4';
    $clubColumnClass = 'col-2';
    $commentColumnClass = 'col-12';
} else {
    $shiftTitleColumnClass = 'col-md-3 col-4 p-0';
    $personNameColumnClass = 'col-md-2 col-4';
    $clubColumnClass = 'col-md-1 col-2';
    $commentColumnClass = 'col-md col-12';
}
@endphp


<div class="row row-cols-md-auto align-items-center shiftRow mt-2 mb-2 border-bottom  {!! ( isset($shift->person->prsn_ldap_id)
                                                  && Auth::user()
                                                  && $shift->person->prsn_ldap_id === Auth::user()->person->prsn_ldap_id) ? "my-shift" : false !!}">
    <div class="d-none">
        @include('partials.shifts.updateShiftFormOpener',['shift'=>$shift, 'autocomplete'=>$autocomplete])
        {{-- SPAMBOT HONEYPOT - this field will be hidden, so if it's filled, then it's a bot or a user tampering with page source --}}
        <div class="welcome-to-our-mechanical-overlords">
            <small>If you can read this this - refresh the page to update CSS styles or switch CSS support on.</small>
            <input type="text" id="{!! 'website' . $shift->id !!}" name="{!! 'website' . $shift->id !!}" value=""/>
        </div>
        <button type="submit" class="hidden hide"></button>
        {!! Form::close() !!}
    </div>

    {{-- Name and time of the shift --}}
    <div class="{{$shiftTitleColumnClass}} p-0 align-content-center">
        @include("partials.shiftTitle")
    </div>

    {{-- User status icon --}}
    <div class="col-auto" style="width:31px">
        @include('partials.shifts.updateShiftFormOpener',['shift'=>$shift, 'autocomplete'=>$autocomplete])
        <div id="clubStatus{{ $shift->id }}">
            @include("partials.shiftStatus")
        </div>
        {!! Form::close() !!}
    </div>
    @if( isset($shift->getPerson->prsn_ldap_id) && !Auth::user())
        <div class="{{$personNameColumnClass}}">
            <div class="form-group form-group-sm">
                @if($shift->getPerson->isNamePrivate() == 0)
                    {{-- Shift USERNAME--}}
                    <div id="{!! 'userName' . $shift->id !!}" class="form-control form-control-sm">
                        {!! $shift->getPerson->prsn_name !!}
                    </div>
                @else
                    <div id="{!! 'userName' . $shift->id !!}" class="form-control form-control-sm">
                        @if(isset($shift->person->user))
                            {{ __($shift->person->user->section->title . '.' . $shift->person->user->status) }}
                        @endif
                    </div>
                @endif
            </div>
        </div>
        {{-- SHIFT CLUB --}}
        <div class="{{$clubColumnClass}}">
            <div id="{!! 'club' . $shift->id !!}" class="form-group form-group-sm ">
                <div class="form-control form-control-sm">
                    {!! "(" . $shift->getPerson->getClub->clb_title . ")" !!}
                </div>
            </div>
        </div>
        <div class="col-1">
            <button class="showhide btn btn-sm btn-outline-secondary">@if($shift->comment === "") <i
                    class="far fa-comment"></i> @else <i class="fa-solid fa-comment"></i> @endif</button>
        </div>
        {{-- COMMENT SECTION --}}
        <div class="{{$commentColumnClass}}">
            <div class="form-group from-group-sm hidden-print word-break">
                        <span class="w-auto @if(isset($hideComments) && $hideComments) hide @endif"
                              id="{{'comment'.$shift->id}}"
                              name="{{'comment' . $shift->id}}">{!! !empty($shift->comment) ? $shift->comment : "-" !!}</span>
            </div>
        </div>
    @else
        {{-- show everything for members --}}
        {{-- SHIFT STATUS, USERNAME, DROPDOWN USERNAME and LDAP ID --}}
        <div class="{{$shiftTitleColumnClass}} p-0">
            @include('partials.shifts.updateShiftFormOpener',['shift'=>$shift, 'autocomplete'=>$autocomplete])
            <div class="form-group form-group-sm">
                @include('partials.shifts.shiftName',['shift'=>$shift])
            </div>
            <button type="submit" class="hidden hide"></button>
            {!! Form::close() !!}
        </div>
        {{-- SHIFT CLUB and DROPDOWN CLUB --}}
        <div class="{{$clubColumnClass}}">
            @include('partials.shifts.updateShiftFormOpener',['shift'=>$shift, 'autocomplete'=>$autocomplete])
            <div class="form-group form-group-sm ">
                @include('partials.shifts.shiftClub',['shift'=>$shift])
            </div>
            <button type="submit" class="hidden hide"></button>
            {!! Form::close() !!}
        </div>
        {{-- COMMENT SECTION --}}
        {{-- Hidden comment field to be opened after the click on the icon
             see filter-scripts "Show/hide comments" function --}}
        <div class="col-1">
            <button class="showhide btn btn-sm btn-outline-secondary">@if($shift->comment === "") <i
                    class="far fa-comment"></i> @else <i class="fa-solid  fa-comment"></i> @endif</button>
        </div>
        <div class="{{$commentColumnClass}}">
            @include('partials.shifts.updateShiftFormOpener',['shift'=>$shift, 'autocomplete'=>$autocomplete])
            <div class="form-group from-group-sm hidden-print word-break w-100">

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
        </div>
    @endif
</div>


