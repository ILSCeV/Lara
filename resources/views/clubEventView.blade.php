@extends('layouts.master')
@section('title')
	{{ $clubEvent->evnt_title }}
@stop
@section('content')
    <div class="panelEventView">
		<div class="row no-margin">
			<div class="panel col-xs-12 col-md-6 no-padding">
				{{-- Set panel color --}}
				@if     ($clubEvent->evnt_type == 0)
			        <div class="panel panel-heading palette-{!! $clubEvent->section->color !!}-700 bg">
			    @elseif ($clubEvent->evnt_type == 1)
			        <div class="panel panel-heading palette-Purple-500 bg">
			    @elseif ($clubEvent->evnt_type == 2
			    	  OR $clubEvent->evnt_type == 3)
			        <div class="panel panel-heading palette-{!! $clubEvent->section->color !!}-900 bg">
			    @elseif ($clubEvent->evnt_type == 4
			          OR $clubEvent->evnt_type == 5
			          OR $clubEvent->evnt_type == 6)
			        <div class="panel panel-heading palette-{!! $clubEvent->section->color !!}-500 bg white-text">
			    @elseif ($clubEvent->evnt_type == 7
			          OR $clubEvent->evnt_type == 8)
			        <div class="panel panel-heading palette-{!! $clubEvent->section->color !!}-300 bg white-text">
			    @elseif ($clubEvent->evnt_type == 9)
			        <div class="panel panel-heading palette-{!! $clubEvent->section->color !!}-500 bg white-text">
			    @endif
					<h4 class="panel-title">@include("partials.event-marker")&nbsp;{{ $clubEvent->evnt_title }}</h4>
					<h5 class="panel-title">{{ $clubEvent->evnt_subtitle }}</h5>
				</div>
					<table class="table table-hover">
						<tr>
							<td width="20%" class="left-padding-16 text-align-right">
								<i>{{ trans('mainLang.type') }}:</i>
							</td>
							<td>
								@if( $clubEvent->evnt_type == 0)
									{{ trans('mainLang.normalProgramm') }}
								@elseif( $clubEvent->evnt_type == 1)
									{{ trans('mainLang.information') }}
								@elseif( $clubEvent->evnt_type == 2)
									{{ trans('mainLang.special') }}
								@elseif( $clubEvent->evnt_type == 3)
									{{ trans('mainLang.LiveBandDJ') }}
								@elseif( $clubEvent->evnt_type == 4)
									{{ trans('mainLang.internalEvent') }}
								@elseif( $clubEvent->evnt_type == 5)
									{{ trans('mainLang.utilization') }}
								@elseif( $clubEvent->evnt_type == 6)
									{{ trans('mainLang.flooding') }}
								@elseif( $clubEvent->evnt_type == 7)
									{{ trans('mainLang.flyersPlacard') }}
								@elseif( $clubEvent->evnt_type == 8)
									{{ trans('mainLang.preSale') }}
								@elseif( $clubEvent->evnt_type == 9)
									{{ trans('mainLang.others') }}
								@endif
							</td>
						</tr>
						<tr>
							<td width="20%" class="left-padding-16">
								<i>{{ trans('mainLang.begin') }}:</i>
							</td>
							<td>
								{{ strftime("%a, %d. %b %Y", strtotime($clubEvent->evnt_date_start)) }} um
								{{ date("H:i", strtotime($clubEvent->evnt_time_start)) }}
							</td>
						</tr>
						<tr>
							<td width="20%" class="left-padding-16">
								<i>{{ trans('mainLang.end') }}:</i>
							</td>
							<td>
								{{ strftime("%a, %d. %b %Y", strtotime($clubEvent->evnt_date_end)) }} um
								{{ date("H:i", strtotime($clubEvent->evnt_time_end)) }}
							</td>
						</tr>
						<tr>
							<td width="20%" class="left-padding-16">
								<i>{{ trans('mainLang.DV-Time') }}:</i>
							</td>
							<td>
								{{ date("H:i", strtotime($clubEvent->getSchedule->schdl_time_preparation_start)) }}
							</td>
						</tr>
						<tr>
							<td width="20%" class="left-padding-16">
								<i>{{ trans('mainLang.club') }}:</i>
							</td>
							<td>
								{{ $clubEvent->section->title }}
								&nbsp;&nbsp;<br class="visible-xs">
								<i>({{ trans('mainLang.willShowFor') }}: {{ implode(", ", $clubEvent->showToSectionNames()) }})</i>
							</td>
						</tr>
					</table>

					<hr class="no-margin no-padding">

					{{-- Internat event metadata --}}
					@if(Session::has('userId'))
						<table class="table table-hover">
                            @if(isset($clubEvent->facebook_done))
                                <tr>
                                    <td width="33%" class="left-padding-16">
                                        <i>{{ trans('mainLang.faceDone') }}?</i>
                                    </td>
                                    <td>
                                        @if($clubEvent->facebook_done === 1)
                                            <i class="text-success" aria-hidden="true">{{ trans('mainLang.yes') }}</i>
                                        @else
                                            <i class="text-danger" aria-hidden="true">{{ trans('mainLang.no') }}</i>
                                        @endif
                                    </td>
                                </tr>
                            @endif
							@if($clubEvent->event_url!=null && $clubEvent->event_url!="")
								<tr>
									<td width="33%" class="left-padding-16">
										<i>{{ trans('mainLang.eventUrl') }}:</i>
									</td>
									<td>
										<a target="_blank" href="{{ $clubEvent->event_url }}"  style="word-break: break-all;">
											{{$clubEvent->event_url}}
										</a>
									</td>
								</tr>
							@endif
                            @if(isset($clubEvent->price_tickets_normal))
                                <tr>
                                    <td width="33%" class="left-padding-16">
                                        <i>{{ trans('mainLang.priceTickets') }}:</i>
                                    </td>
                                    <td>
                                        {{  $clubEvent->price_tickets_normal !== null ? $clubEvent->price_tickets_normal : '--' }} €
                                        /
                                        {{ $clubEvent->price_tickets_external !== null ? $clubEvent->price_tickets_external : '--' }} €
                                        &nbsp;&nbsp;
                                        <br class="visible-xs">
                                        ({{ trans('mainLang.studentExtern') }})
                                    </td>
                                </tr>
                            @endif
                            @if(isset($clubEvent->price_normal))
                                <tr>
                                    <td width="33%" class="left-padding-16">
                                        <i>{{ trans('mainLang.price') }}:</i>
                                    </td>
                                    <td>
                                        {{  $clubEvent->price_normal !== null ? $clubEvent->price_normal : '--' }} €
                                        /
                                        {{ $clubEvent->price_external !== null ? $clubEvent->price_external : '--' }} €
                                        &nbsp;&nbsp;
                                        <br class="visible-xs">
                                        ({{ trans('mainLang.studentExtern') }})
                                    </td>
                                </tr>
                            @endif

							{{--

							Disabling iCal until fully functional.


							<tr>
								<td width="20%" class="left-padding-16">
									<i>{{ trans('mainLang.iCal') }}:</i>
								</td>
								<td>
									@if($clubEvent->evnt_is_published === "1")
										<i class="fa fa-check-square-o" aria-hidden="true"></i>
										&nbsp;&nbsp;{{trans('mainLang.eventIsPublished')}}
									@else
										<i class="fa fa-square-o" aria-hidden="true"></i>
										&nbsp;&nbsp;{{trans('mainLang.eventIsUnpublished')}}
									@endif
								</td>
							</tr>

							--}}
						</table>
					@endif

				{{-- CRUD --}}
				@if(Session::get('userGroup') === 'marketing'
				 OR Session::get('userGroup') === 'clubleitung'
				 OR Session::get('userGroup') === 'admin'
				 OR Session::get('userId') === $created_by)
					<div class="panel panel-footer col-md-12 col-xs-12 hidden-print">
						<span class="pull-right">
							{{-- Event publishing only for CL/marketing -> exclude creator


							Disabling iCal until fully functional.


							@if(Session::get('userGroup') === 'marketing'
							 OR Session::get('userGroup') === 'clubleitung'
							 OR Session::get('userGroup') === 'admin')
								<button  id="unPublishEventLnkBtn"
									data-href="{{ URL::route('togglePublishState', $clubEvent->id) }}"
									class="btn btn-danger @if($clubEvent->evnt_is_published === 0) hidden @endif"
									name="toggle-publish-state"
								    data-toggle="tooltip"
								    data-placement="bottom"
								    title="{{trans("mainLang.unpublishEvent")}}"
								    data-token="{{csrf_token()}}"
									>
									<i class="fa fa-bullhorn" aria-hidden="true"></i>
								</button>
								<button  id="pubishEventLnkBtn"
									data-href="{{ URL::route('togglePublishState', $clubEvent->id) }}"
									class="btn btn-success @if($clubEvent->evnt_is_published === 1) hidden @endif"
									name="toggle-publish-state"
									data-toggle="tooltip"
									data-placement="bottom"
									title="{{trans("mainLang.publishEvent")}}"
									data-token="{{csrf_token()}}"
									>
									<i class="fa fa-bullhorn" aria-hidden="true"></i>
								</button>
								&nbsp;&nbsp;
							@endif

							--}}

							<a href="{{ URL::route('event.edit', $clubEvent->id) }}"
							   class="btn btn-primary"
							   data-toggle="tooltip"
		                       data-placement="bottom"
		                       title="{{ trans('mainLang.changeEvent') }}">
							   <i class="fa fa-pencil"></i>
							</a>
							&nbsp;&nbsp;
							<a href="{{ $clubEvent->id }}"
							   class="btn btn-default"
							   data-toggle="tooltip"
		                       data-placement="bottom"
		                       title="{{ trans('mainLang.deleteEvent') }}"
							   data-method="delete"
							   data-token="{{csrf_token()}}"
							   rel="nofollow"
							   data-confirm="{{ trans('mainLang.confirmDeleteEvent') }}">
							   <i class="fa fa-trash"></i>
							</a>
						</span>
					</div>
				@endif
			</div>
	                        <span class="displayMobile">
	                            <br>	&nbsp;
	                        </span>

			<div class="col-xs-12 col-md-6 col-sm-12 no-padding-xs">
				@if( $clubEvent->evnt_public_info != '')
				<div class="panel">
					<div class="panel-body more-info">
						<h5 class="panel-title">{{ trans('mainLang.additionalInfo') }}:</h5>
						{!! nl2br($clubEvent->evnt_public_info) !!}
					</div>
					<button type="button" class="moreless-more-info btn btn-primary btn-margin" data-dismiss="alert">{{ trans('mainLang.showMore') }}</button>
					<button type="button" class="moreless-less-info btn btn-primary btn-margin" data-dismiss="alert">{{ trans('mainLang.showLess') }}</button>
				</div>
				@endif

				@if(Session::has('userId'))
					@if($clubEvent->evnt_private_details != '')
					<div class="panel hidden-print">
						<div class="panel-body more-details">
							<h5 class="panel-title">{{ trans('mainLang.moreDetails') }}:</h5>
							{!! nl2br($clubEvent->evnt_private_details) !!}
						</div>
						<button type="button" class="moreless-more-details btn btn-primary btn-margin" data-dismiss="alert">{{ trans('mainLang.showMore') }}</button>
						<button type="button" class="moreless-less-details btn btn-primary btn-margin" data-dismiss="alert">{{ trans('mainLang.showLess') }}</button>
					</div>
					@endif
				@endif
			</div>
		</div>
	</div>

	<br>

    <div class="panelEventView">
        {{-- show time button Ger.: Zeiten einblenden --}}
		&nbsp;&nbsp;
		<button class="btn btn-xs hidden-print"  type="button" id="toggle-shift-time">{{ trans('mainLang.hideTimes') }}</button>

		{{-- hide taken shifts button Ger.: Vergebenen Diensten ausblenden --}}
		<button class="btn btn-xs hidden-print" type="button" id="toggle-taken-shifts">{{ trans('mainLang.hideTakenShifts') }}</button>
	</div>


	<div class="panel panel-warning">
		@if( $clubEvent->getSchedule->schdl_password != '')
			<div class="hidden-print panel panel-heading">
			    {!! Form::password('password', array('required',
			                                         'class'=>'col-md-4 col-sm-4 col-xs-12 black-text',
		                                             'id'=>'password' . $clubEvent->getSchedule->id,
			                                         'placeholder'=>Lang::get('mainLang.enterPasswordHere'))) !!}
			    <br />
			</div>

		@endif

		<div class="panel-body no-padding ">
			@foreach($shifts as $shift)
				{{-- highlight with my-shift class if the signed in user is the person to do the shift --}}
				<div class="row paddingTop {!! ( isset($shift->getPerson->prsn_ldap_id) AND Session::has('userId') AND $shift->getPerson->prsn_ldap_id === Session::get('userId')) ? "my-shift" : false !!}">
			        {!! Form::open(  array( 'route' => ['shift.update', $shift->id],
			                                'id' => $shift->id,
			                                'method' => 'PUT',
			                                'class' => 'shift')  ) !!}

			        {{-- SPAMBOT HONEYPOT - this field will be hidden, so if it's filled, then it's a bot or a user tampering with page source --}}
			        <div id="welcome-to-our-mechanical-overlords">
			            <small>If you can read this this - refresh the page to update CSS styles or switch CSS support on.</small>
			            <input type="text" id="{!! 'website' . $shift->id !!}" name="{!! 'website' . $shift->id !!}" value="" />
			        </div>

			        {{-- SHIFT TITLE --}}
			        <div class="col-md-2 col-sm-2 col-xs-4 left-padding-8">
			            @include("partials.shiftTitle")
			        </div>

			        {{-- show public events, but protect members' shifts from being changed by guests --}}
			        @if( isset($shift->getPerson->prsn_ldap_id) AND !Session::has('userId'))

						<div class="col-md-2 col-sm-2 col-xs-4 input-append btn-group">
						    {{-- SHIFT STATUS --}}
						    <div class="col-md-3 col-sm-2 col-xs-3 no-padding" id="clubStatus{{ $shift->id }}">
						        @include("partials.shiftStatus")
						    </div>

						    {{-- Shift USERNAME--}}
						    <div id="{!! 'userName' . $shift->id !!}">
						        {!! $shift->getPerson->prsn_name !!}
						    </div>

						    {{-- no need to show LDAP ID or TIMESTAMP in this case --}}
						</div>

						{{-- SHIFT CLUB --}}
						<div id="{!! 'club' . $shift->id !!}" class="col-md-2 col-xs-3 no-padding">
						    {!! "(" . $shift->getPerson->getClub->clb_title . ")" !!}
						</div>

						<br class="visible-xs hidden-sm">

						{{-- COMMENT SECTION --}}
						<div class="col-md-6 col-sm-6 col-xs-12 hidden-print word-break no-margin">
						    <span class="pull-left">
						    	{!! $shift->comment === "" ? '<i class="fa fa-comment-o"></i>' : '<i class="fa fa-comment"></i>' !!}
						    	&nbsp;&nbsp;
						    </span>

						    <span class="col-md-10 col-sm-10 col-xs-10 no-padding no-margin">
							    {!! !empty($shift->comment) ? $shift->comment : "-" !!}
							</span>
						</div>


			        {{-- show everything for members --}}
					@else

			        	{{-- SHIFT STATUS, USERNAME, DROPDOWN USERNAME and LDAP ID --}}
						<div class="col-md-2 col-sm-2 col-xs-5 input-append btn-group">
						    @include("partials.shiftName")
						</div>

						{{-- SHIFT CLUB and DROPDOWN CLUB --}}
						<div class="col-md-2 col-sm-2 col-xs-3 no-padding">
						    @include("partials.shiftClub")
						</div>

						{{-- COMMENT SECTION --}}
						<br class="visible-print hidden-md hidden-sm hidden-xs">
						<br class="visible-print hidden-md hidden-sm hidden-xs">
						<div class="col-md-6 col-sm-12 col-xs-12 no-margin">
						    <span class="pull-left">
						    	{!! $shift->comment === "" ? '<i class="fa fa-comment-o"></i>' : '<i class="fa fa-comment"></i>' !!}
						    	&nbsp;&nbsp;
						    </span>

						    {!! Form::text('comment' . $shift->id,
					                   $shift->comment,
					                   array('placeholder'=>Lang::get('mainLang.addCommentHere'),
					                         'id'=>'comment' . $shift->id,
			                     			 'name'=>'comment' . $shift->id,
					                         'class'=>'col-md-11 col-sm-11 col-xs-10 no-padding no-margin'))
					    	!!}
						</div>
						<br class="visible-print hidden-md hidden-sm hidden-xs">

			        @endif

			        {!! Form::close() !!}

				</div>

				{{-- Show a line after each row except the last one --}}
				@if($shift !== $shifts->last() )
					<hr class="col-md-12 col-md-12 col-xs-12 top-padding no-margin no-padding">
				@endif

			@endforeach
		</div>

	</div>

	<br>

	@if(Session::has('userId'))
		{{-- REVISIONS --}}
		<span class="hidden-xs">&nbsp;&nbsp;</span><span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
		<a id="show-hide-history" class="text-muted hidden-print" href="#">
			{{ trans('mainLang.listChanges') }} &nbsp;&nbsp;<i class="fa fa-caret-right" id="arrow-icon"></i>
		</a>

		<div class="panel hide" id="change-history">
			<table class="table table-hover table-condensed">
				<thead>
					<th class="col-xs-2 col-md-2">
						{{ trans('mainLang.work') }}
					</th>
					<th class="col-xs-2 col-md-2">
						{{ trans('mainLang.whatChanged') }}
					</th>
					<th class="col-xs-2 col-md-2">
						{{ trans('mainLang.oldEntry') }}
					</th>
					<th class="col-xs-2 col-md-2">
						{{ trans('mainLang.newEntry') }}
					</th>
					<th class="col-xs-2 col-md-2">
						{{ trans('mainLang.whoBlame') }}
					</th>
					<th class="col-xs-2 col-md-2">
						{{ trans('mainLang.whenWasIt') }}
					</th>
				</thead>
				<tbody>
				@foreach( $revisions as $revision )
					<tr>
						<td>
							{{ $revision["job type"] }}
						</td>
						<td>
							{{ trans($revision["action"]) }}
						</td>
						<td>
							{{ $revision["old value"] }}
						</td>
						<td>
							{{ $revision["new value"] }}
						</td>
						<td>
							{{ $revision["user name"] }}
						</td>
						<td>
							{{ $revision["timestamp"] }}
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
		<br>
		<br class="visible-xs">
                    </div>
	@endif

@stop



