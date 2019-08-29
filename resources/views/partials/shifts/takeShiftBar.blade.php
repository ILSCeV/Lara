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
    $clubColumnClass = 'col-3';
    $commentColumnClass = 'col-12';
} else {
    $shiftTitleColumnClass = 'col-md-2 col-3 p-0';
    $personNameColumnClass = 'col-md-2 col-4';
    $clubColumnClass = 'col-md-2 col-3';
    $commentColumnClass = 'col-md-4 col-12';
}
@endphp


<div class="row shiftRow divider  {!! ( isset($shift->person->prsn_ldap_id)
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
    <div class="{{$shiftTitleColumnClass}} p-0 align-content-center">
        @include("partials.shiftTitle")
    </div>
    <div class="col-1 p-0">
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
                            {{ trans($shift->person->user->section->title . '.' . $shift->person->user->status) }}
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
            <button class="showhide btn btn-sm btn-secondary">@if($shift->comment === "") <i
                    class="far fa-comment"></i> @else <i class="fas fa-comment"></i> @endif</button>
        </div>
        {{-- COMMENT SECTION --}}
        <div class="{{$commentColumnClass}}">
            <div class="form-group from-group-sm hidden-print word-break w-100 pt-2">
                        <span class="mb-2 w-auto @if(isset($hideComments) && $hideComments) hide @endif"
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
            <button class="showhide btn btn-sm btn-secondary">@if($shift->comment === "") <i
                    class="far fa-comment"></i> @else <i class="fas fa-comment"></i> @endif</button>
        </div>
        <div class="{{$commentColumnClass}}">
            @include('partials.shifts.updateShiftFormOpener',['shift'=>$shift, 'autocomplete'=>$autocomplete])
            <div class="form-group from-group-sm hidden-print word-break w-100 pt-2">

                {!! Form::text('comment' . $shift->id,
                                           $shift->comment,
                                           ['placeholder'=>Lang::get('mainLang.addCommentHere'),
                                                 'id'=>'comment' . $shift->id,
                                                  'name'=>'comment' . $shift->id,
                                                 'class'=>'form-control form-control-sm w-100 mb-2 '. $commentClass])
                                !!}

            </div>
            <button type="submit" class="hidden hide"></button>
            {!! Form::close() !!}
        </div>
    @endif
</div>


