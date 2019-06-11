@extends('layouts.master')
@section('title')
	{{ $clubEvent->evnt_title }}
@stop
@section('moreScripts')
    <script src="{{asset(WebpackBuiltFiles::$jsFiles['autocomplete'])}}" ></script>
@endsection
@section('content')
    <div class="panelEventView">
		<div class="row mx-md-5 mx-sm-1 my-3">
            <div class="col-12 col-md-6 p-0">
    			<div class="card mx-2">
                    @php
                        $clubEventClass = \Lara\utilities\ViewUtility::getEventPaletteClass($clubEvent);
                        $commonHeader = 'card-header '. $clubEventClass;
                    @endphp
    				{{-- Set card color --}}
    				<div class="{{ $commonHeader }}">
    					<h4 class="card-title">@include("partials.event-marker")&nbsp;{{ $clubEvent->evnt_title }}</h4>
    					<h5 class="card-title">{{ $clubEvent->evnt_subtitle }}</h5>
    				</div>
    					<table class="table table-hover px-3">
    						<tr>
    							<td width="20%" class="text-align-right">
    								<i>{{ trans('mainLang.type') }}:</i>
    							</td>
    							<td>
                                    {{ \Lara\Utilities::getEventTypeTranslation($clubEvent->evnt_type)  }}
    							</td>
    						</tr>
    						<tr>
    							<td width="20%">
    								<i>{{ trans('mainLang.begin') }}:</i>
    							</td>
    							<td>
    								{{ strftime("%a, %d. %b %Y", strtotime($clubEvent->evnt_date_start)) }} um
    								{{ date("H:i", strtotime($clubEvent->evnt_time_start)) }}
    							</td>
    						</tr>
    						<tr>
    							<td width="20%">
    								<i>{{ trans('mainLang.end') }}:</i>
    							</td>
    							<td>
    								{{ strftime("%a, %d. %b %Y", strtotime($clubEvent->evnt_date_end)) }} um
    								{{ date("H:i", strtotime($clubEvent->evnt_time_end)) }}
    							</td>
    						</tr>
    						<tr>
    							<td width="20%">
    								<i>{{ trans('mainLang.DV-Time') }}:</i>
    							</td>
    							<td>
    								{{ date("H:i", strtotime($clubEvent->getSchedule->schdl_time_preparation_start)) }}
    							</td>
    						</tr>
    						<tr>
    							<td width="20%">
    								<i>{{ trans('mainLang.club') }}:</i>
    							</td>
    							<td>
    								{{ $clubEvent->section->title }}
    								&nbsp;&nbsp;<br class="d-block d-sm-none">
    								<i>({{ trans('mainLang.willShowFor') }}: {{ implode(", ", $clubEvent->showToSectionNames()) }})</i>
    							</td>
    						</tr>
    					</table>

    					<hr class="m-0 p-0">

    					{{-- Internal event metadata --}}
    					@auth
    						<table class="table table-hover">
                                @if(isset($clubEvent->facebook_done))
                                    <tr>
                                        <td width="33%">
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
    								<tr>
    									<td width="33%">
    										<i>{{ trans('mainLang.eventUrl') }}:</i>
    									</td>
    									<td>
                                            <a target="_blank" class="d-inline-block text-truncate" style="max-width: 45%;" href="{{ $clubEvent->event_url }}"  >
                                                {{$clubEvent->event_url}}
                                            </a>
    									</td>
    								</tr>
    							@endif
                                @if(isset($clubEvent->price_tickets_normal))
                                    <tr>
                                        <td width="33%" class="pl-3">
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
                                    <tr>
                                        <td width="33%" class="pl-3">
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
    								<td width="20%" class="pl-3">
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
            </div>

			<div class="col-12 col-md-6 p-0">
				@if( $clubEvent->evnt_public_info != '')
    				<div class="card mx-2">
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
                    <br>
    					<div class="card mx-2 hidden-print">
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

        <div class="row mx-md-5 mx-sm-1 my-3">
            {{-- show time button Ger.: Zeiten einblenden --}}
    		&nbsp;&nbsp;
    		<button class="btn btn-sm hidden-print"  type="button" id="toggle-shift-time">
                {{ trans('mainLang.hideTimes') }}
            </button>

    		{{-- hide taken shifts button Ger.: Vergebenen Diensten ausblenden --}}
    		<button class="btn btn-sm hidden-print" type="button" id="toggle-taken-shifts">
                {{ trans('mainLang.hideTakenShifts') }}
            </button>

            {{-- show/hide all comment fields --}}
            <button class="btn btn-sm hidden-print" type="button" id="toggle-all-comments">
                {{ trans('mainLang.comments') }}
            </button>
        </div>

        <div class="row ">
        	<div class="card col-12 mx-sm-1 m-auto">
        		@if( $clubEvent->getSchedule->schdl_password != '')
        			<div class="card-header hidden-print">
        			    {!! Form::password('password', array('required',
        			                                         'class'=>'col-md-4 col-sm-4 col-12 black-text',
        		                                             'id'=>'password' . $clubEvent->getSchedule->id,
        			                                         'placeholder'=>Lang::get('mainLang.enterPasswordHere'))) !!}
        			    <br>
        			</div>
        		@endif

                @include('partials.shifts.takeShiftTable',['shifts'=>$shifts, 'hideComments'=>false, 'commentsInSeparateLine' => false])
        	</div>
        </div>
	</div>

	@auth
		{{-- REVISIONS --}}
        <div class="row mx-md-5 mx-sm-1 m-0">
            <a id="show-hide-history" class="text-muted hidden-print" href="#">
    			{{ trans('mainLang.listChanges') }} &nbsp;&nbsp;<i class="fa fa-caret-right" id="arrow-icon"></i>
    		</a>
        </div>

        <div class="row mx-md-5 mx-sm-1 my-3">
    		<div class="card col-12 p-0 m-0 hide" id="change-history">
    			<table class="table table-hover">
    				<thead>
    					<th scope="col">
    						{{ trans('mainLang.work') }}
    					</th>
    					<th scope="col">
    						{{ trans('mainLang.whatChanged') }}
    					</th>
    					<th scope="col">
    						{{ trans('mainLang.oldEntry') }}
    					</th>
    					<th scope="col">
    						{{ trans('mainLang.newEntry') }}
    					</th>
    					<th scope="col">
    						{{ trans('mainLang.whoBlame') }}
    					</th>
    					<th scope="col">
    						{{ trans('mainLang.whenWasIt') }}
    					</th>
    				</thead>
    				<tbody>
        				@foreach( $revisions as $revision )
        					<tr>
        						<td scope="row">
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
        </div>
	@endauth

@stop
