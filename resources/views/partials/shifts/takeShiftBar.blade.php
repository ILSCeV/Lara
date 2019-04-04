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
{!! Form::open(  array( 'route' => ['shift.update', $shift->id],
	                                'id' => $shift->id,
	                                'method' => 'PUT',
	                                'class' => 'shift form-inline col-12 '. $autocomplete)  ) !!}

{{-- SPAMBOT HONEYPOT - this field will be hidden, so if it's filled, then it's a bot or a user tampering with page source --}}
<div class="welcome-to-our-mechanical-overlords">
    <small>If you can read this this - refresh the page to update CSS styles or switch CSS support on.</small>
    <input type="text" id="{!! 'website' . $shift->id !!}" name="{!! 'website' . $shift->id !!}" value=""/>
</div>

<div class="col-md-3 px-1">
    @include("partials.shiftTitle")
</div>
<div class="col-md-1 p-0">
    <div id="clubStatus{{ $shift->id }}">
        @include("partials.shiftStatus")
    </div>
</div>
@if( isset($shift->getPerson->prsn_ldap_id) && !Auth::user())
    <div class="col-md-4 p-0">
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
    <div class="col-md-2 p-0">
        <div id="{!! 'club' . $shift->id !!}" class="form-group form-group-sm ">
            <div class="form-control form-control-sm">
                {!! "(" . $shift->getPerson->getClub->clb_title . ")" !!}
            </div>
        </div>
    </div>
    <div class="col-md-1 p-0">
            <span class="float-left">
		   @if($shift->comment === "") <i class="fas fa-comment-alt"></i> @else <i class="fas fa-comment"></i> @endif
            &nbsp;&nbsp;
		</span>
    </div>
    {{-- COMMENT SECTION --}}
    <div class="col-md-10 col-md-offset-1">
        <div class="form-group from-group-sm hidden-print word-break ">
            <div class="form-control form-control-sm">
                        <span class="w-auto @if(isset($hideComments)) hide @endif" id="{{'comment'.$shift->id}}"
                              name="{{'comment' . $shift->id}}">
		                {!! !empty($shift->comment) ? $shift->comment : "-" !!}
		                </span>
            </div>
        </div>
    </div>
@else
    {{-- show everything for members --}}
    {{-- SHIFT STATUS, USERNAME, DROPDOWN USERNAME and LDAP ID --}}
    <div class="col-md-4 px-1">
        <div class="form-group form-group-sm">
            @include('partials.shifts.shiftName',['shift'=>$shift])
        </div>
    </div>
    {{-- SHIFT CLUB and DROPDOWN CLUB --}}
    <div class="col-md-2 p-0">
        <div class="form-group form-group-sm ">
            @include('partials.shifts.shiftClub',['shift'=>$shift])
        </div>
    </div>
    {{-- COMMENT SECTION --}}
    {{-- Hidden comment field to be opened after the click on the icon
         see vedst-scripts "Show/hide comments" function --}}
    <div class="col-md-1 p-0">
        <div class="form-control form-control-sm">
                <span class="float-left">
                 @if($shift->comment === "") <i class="fas fa-comment-alt"></i> @else <i
                            class="fas fa-comment"></i> @endif
                    &nbsp;&nbsp;
                </span>
        </div>
    </div>
    <div class="col-md-10 col-md-offset-1">
        <div class="form-group from-group-sm hidden-print word-break ">

            {!! Form::text('comment' . $shift->id,
                                       $shift->comment,
                                       ['placeholder'=>Lang::get('mainLang.addCommentHere'),
                                             'id'=>'comment' . $shift->id,
                                              'name'=>'comment' . $shift->id,
                                             'class'=>'form-control form-control-sm '. $commentClass])
                            !!}

        </div>
    </div>
@endif
{!! Form::close() !!}
