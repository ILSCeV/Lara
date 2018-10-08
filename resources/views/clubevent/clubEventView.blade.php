@extends('layouts.master')
@section('title')
	{{ $clubEvent->evnt_title }}
@stop
@section('content')
    <div class="panelEventView">
		<div class="row no-margin">
			<div class="card col no-padding">
                @php
                    $clubEventClass = \Lara\utilities\ViewUtility::getEventPaletteClass($clubEvent);
                    $commonHeader = 'card-header '. $clubEventClass;
                @endphp
				{{-- Set card color --}}
				<div class="{{ $commonHeader }}">
					<h4 class="card-title">@include("partials.event-marker")&nbsp;{{ $clubEvent->evnt_title }}</h4>
					<h5 class="card-title">{{ $clubEvent->evnt_subtitle }}</h5>
				</div>
					<table class="table table-hover">
						<tr>
							<td width="20%" class="left-padding-16 text-align-right">
								<i>{{ trans('mainLang.type') }}:</i>
							</td>
							<td>
                                {{ \Lara\Utilities::getEventTypeTranslation($clubEvent->evnt_type)  }}
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
								&nbsp;&nbsp;<br class="d-block d-sm-none">
								<i>({{ trans('mainLang.willShowFor') }}: {{ implode(", ", $clubEvent->showToSectionNames()) }})</i>
							</td>
						</tr>
					</table>

					<hr class="no-margin no-padding">

					{{-- Internat event metadata --}}
					@auth
						<table class="table table-hover">
                            @if(isset($clubEvent->facebook_done))
                                <tr class="">
                                    <td width="33%" class="left-padding-16">
                                        <i>{{ trans('mainLang.faceDone') }}?</i>
                                    </td>
                                    <td>
                                        @if($clubEvent->facebook_done == 1)
                                            <i class="text-success" aria-hidden="true">{{ trans('mainLang.yes') }}</i>
                                        @else
                                            <i class="text-danger" aria-hidden="true">{{ trans('mainLang.no') }}</i>
                                        @endif
                                    </td>
                                </tr>
                            @endif
							@if($clubEvent->event_url!=null && $clubEvent->event_url!="")
								<tr class="">
									<td width="33%" class="left-padding-16">
										<i>{{ trans('mainLang.eventUrl') }}:</i>
									</td>
									<td class="d-block">
                                        <div class="text-truncate col-9">
                                            <a target="_blank" href="{{ $clubEvent->event_url }}"  >
                                                {{$clubEvent->event_url}}
                                            </a>
                                        </div>
									</td>
								</tr>
							@endif
                            @if(isset($clubEvent->price_tickets_normal))
                                <tr class="">
                                    <td width="33%" class="left-padding-16">
                                        <i>{{ trans('mainLang.priceTickets') }}:</i>
                                    </td>
                                    <td>
                                        {{  $clubEvent->price_tickets_normal !== null ? $clubEvent->price_tickets_normal : '--' }} €
                                        /
                                        {{ $clubEvent->price_tickets_external !== null ? $clubEvent->price_tickets_external : '--' }} €
                                        &nbsp;&nbsp;
                                        <br class="d-block d-sm-none">
                                        ({{ trans('mainLang.studentExtern') }})
                                    </td>
                                </tr>
                            @endif
                            @if(isset($clubEvent->price_normal))
                                <tr class="">
                                    <td width="33%" class="left-padding-16">
                                        <i>{{ trans('mainLang.price') }}:</i>
                                    </td>
                                    <td>
                                        {{  $clubEvent->price_normal !== null ? $clubEvent->price_normal : '--' }} €
                                        /
                                        {{ $clubEvent->price_external !== null ? $clubEvent->price_external : '--' }} €
                                        &nbsp;&nbsp;
                                        <br class="d-block d-sm-none">
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
					@endauth

				{{-- CRUD --}}
				@isInSection(['marketing', 'clubleitung', 'admin'], $clubEvent->section)
                    @include('partials/events/editOptions', ['event' => $clubEvent])
                @elseif(Lara\Person::isCurrent($created_by))
					@include('partials/events/editOptions', ['event' => $clubEvent])
				@endisInSection
			</div>
	                        <span class="displayMobile">
	                            <br>	&nbsp;
	                        </span>

			<div class="col-xs-12 col-md-6 col-sm-12 no-padding-xs">
				@if( $clubEvent->evnt_public_info != '')
				<div class="card">
					<div class="card-body more-info">
						<h5 class="card-title">{{ trans('mainLang.additionalInfo') }}:</h5>
						{!! nl2br($clubEvent->evnt_public_info) !!}
					</div>
					<button type="button" class="moreless-more-info btn btn-primary btn-margin" data-dismiss="alert">{{ trans('mainLang.showMore') }}</button>
					<button type="button" class="moreless-less-info btn btn-primary btn-margin" data-dismiss="alert">{{ trans('mainLang.showLess') }}</button>
				</div>
				@endif

				@auth
					@if($clubEvent->evnt_private_details != '')
					<div class="card hidden-print">
						<div class="card-body more-details">
							<h5 class="card-title">{{ trans('mainLang.moreDetails') }}:</h5>
							{!! nl2br($clubEvent->evnt_private_details) !!}
						</div>
						<button type="button" class="moreless-more-details btn btn-primary btn-margin" data-dismiss="alert">{{ trans('mainLang.showMore') }}</button>
						<button type="button" class="moreless-less-details btn btn-primary btn-margin" data-dismiss="alert">{{ trans('mainLang.showLess') }}</button>
					</div>
					@endif
				@endauth
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


	<div class="card bg-warning">
		@if( $clubEvent->getSchedule->schdl_password != '')
			<div class="hidden-print card-header">
			    {!! Form::password('password', array('required',
			                                         'class'=>'col-md-4 col-sm-4 col-xs-12 black-text',
		                                             'id'=>'password' . $clubEvent->getSchedule->id,
			                                         'placeholder'=>Lang::get('mainLang.enterPasswordHere'))) !!}
			    <br />
			</div>

		@endif

		<div class="card-body no-padding ">
			@foreach($shifts as $shift)
				{{-- highlight with my-shift class if the signed in user is the person to do the shift --}}
				<div class="row paddingTop {!! ( isset($shift->getPerson->prsn_ldap_id) && Auth::user() && $shift->getPerson->prsn_ldap_id === Auth::user()->person->prsn_ldap_id) ? "my-shift" : false !!}">
			        {!! Form::open(  array( 'route' => ['shift.update', $shift->id],
			                                'id' => $shift->id,
			                                'method' => 'PUT',
			                                'class' => 'shift form-inline')  ) !!}

			        {{-- SPAMBOT HONEYPOT - this field will be hidden, so if it's filled, then it's a bot or a user tampering with page source --}}
			        <div id="welcome-to-our-mechanical-overlords">
			            <small>If you can read this this - refresh the page to update CSS styles or switch CSS support on.</small>
			            <input type="text" id="{!! 'website' . $shift->id !!}" name="{!! 'website' . $shift->id !!}" value="" />
			        </div>

			        {{-- SHIFT TITLE --}}
			        <div class="col left-padding-8">
			            @include("partials.shiftTitle")
			        </div>

			        {{-- show public events, but protect members' shifts from being changed by guests --}}
			        @if( isset($shift->getPerson->prsn_ldap_id) && !Auth::user())
						<div class="col input-append btn-group">
						    {{-- SHIFT STATUS --}}
						    <div class="col form-control" id="clubStatus{{ $shift->id }}">
						        @include("partials.shiftStatus")
						    </div>
                            @php
                            /** @var \Lara\Shift $shift*/
                            @endphp
                            @if($shift->getPerson->isNamePrivate() == 0)
                                {{-- Shift USERNAME--}}
                                <div id="{!! 'userName' . $shift->id !!}" >
                                    {!! $shift->getPerson->prsn_name !!}
                                </div>
                            @else
                                <div id="{!! 'userName' . $shift->id !!}" >
                                    @if(isset($shift->person->user))
                                        {{ trans($shift->person->user->section->title . '.' . $shift->person->user->status) }}
                                    @endif
                                </div>
                            @endif

						    {{-- no need to show LDAP ID or TIMESTAMP in this case --}}
						</div>

						{{-- SHIFT CLUB --}}
						<div id="{!! 'club' . $shift->id !!}" class="col no-padding">
						    {!! "(" . $shift->getPerson->getClub->clb_title . ")" !!}
						</div>

						<br class="d-block d-sm-none d-sm-none">

						{{-- COMMENT SECTION --}}
						<div class="col hidden-print word-break no-margin">
						    <span class="float-left">
						    	{!! $shift->comment === "" ? '<i class="fa fa-comment-o"></i>' : '<i class="fa fa-comment"></i>' !!}
						    	&nbsp;&nbsp;
						    </span>

						    <span class="col-10 no-padding no-margin">
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
						<br class="visible-print d-md-none d-sm-none d-none">
						<br class="visible-print d-md-none d-sm-none d-none">
						<div class="col-md-6 col-sm-12 col-xs-12 no-margin">
						    <span class="float-left">
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
						<br class="visible-print d-md-none d-sm-none d-none">

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

	@auth
		{{-- REVISIONS --}}
		<span class="d-none">&nbsp;&nbsp;</span><span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
		<a id="show-hide-history" class="text-muted hidden-print" href="#">
			{{ trans('mainLang.listChanges') }} &nbsp;&nbsp;<i class="fa fa-caret-right" id="arrow-icon"></i>
		</a>

		<div class="card hide" id="change-history">
			<table class="table table-hover table-sm">
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
		<br class="d-block d-sm-none">
                    </div>
	@endauth

@stop
