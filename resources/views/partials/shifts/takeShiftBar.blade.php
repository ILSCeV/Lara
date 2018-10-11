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
<div id="welcome-to-our-mechanical-overlords">
    <small>If you can read this this - refresh the page to update CSS styles or switch CSS support on.</small>
    <input type="text" id="{!! 'website' . $shift->id !!}" name="{!! 'website' . $shift->id !!}" value=""/>
</div>
<div class="form-group form-group-sm pl-md-2 pl-xs-0 col-md-1 col-xs-1">
    @include("partials.shiftTitle")
</div>
<div class="form-group form-group-sm pl-md-2 pl-xs-0 col-1" id="clubStatus{{ $shift->id }}">
    @include("partials.shiftStatus")
</div>
@if( isset($shift->getPerson->prsn_ldap_id) && !Auth::user())

    <div class="form-group form-group-sm  col-md-3 col-xs-4 pl-2">
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
    {{-- SHIFT CLUB --}}
    <div id="{!! 'club' . $shift->id !!}" class="form-group form-group-sm pl-2 col-2">
        <div class="form-control form-control-sm">
            {!! "(" . $shift->getPerson->getClub->clb_title . ")" !!}
        </div>
    </div>
    {{-- COMMENT SECTION --}}
    <div class="form-group from-group-sm hidden-print word-break pl-2">
        <div class="form-control form-control-sm">
		<span class="float-left">
		   @if($shift->comment === "") <i class="fas fa-comment-alt"></i> @else <i class="fas fa-comment"></i> @endif
            &nbsp;&nbsp;
		</span>
         <span class="w-auto @if(isset($hideComments)) hide @endif" id="{{'comment'.$shift->id}}" name="{{'comment' . $shift->id}}">
		  {!! !empty($shift->comment) ? $shift->comment : "-" !!}
		</span>
        </div>
    </div>
@else
    {{-- show everything for members --}}
    {{-- SHIFT STATUS, USERNAME, DROPDOWN USERNAME and LDAP ID --}}
    <div class="form-group form-group-sm col-md-3 col-xs-4 pl-2">
        @include('partials.shifts.shiftName',['shift'=>$shift])
    </div>
    {{-- SHIFT CLUB and DROPDOWN CLUB --}}
    <div class="form-group form-group-sm pl-2 col-md-2 col-xs-4 ">
        @include('partials.shifts.shiftClub',['shift'=>$shift])
    </div>
    {{-- COMMENT SECTION --}}
    {{-- Hidden comment field to be opened after the click on the icon
							     see vedst-scripts "Show/hide comments" function --}}
    <div class="form-group from-group-sm hidden-print word-break p-2">
        <div class="form-control form-control-sm">
            <span class="float-left">
		     @if($shift->comment === "") <i class="fas fa-comment-alt"></i> @else <i class="fas fa-comment"></i> @endif
                &nbsp;&nbsp;
		    </span>
        </div>
            {!! Form::text('comment' . $shift->id,
					                   $shift->comment,
					                   array('placeholder'=>Lang::get('mainLang.addCommentHere'),
					                         'id'=>'comment' . $shift->id,
			                     			 'name'=>'comment' . $shift->id,
					                         'class'=>'form-control form-control-sm '. $commentClass))
					    	!!}

    </div>

@endif
{!! Form::close() !!}
