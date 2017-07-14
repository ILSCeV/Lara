{{-- Needs variable: $current_shiftType, $shiftTypes, $entries --}}
@extends('layouts.master')

@section('title')
	{{ trans('mainLang.management') }}: #{{ $current_shiftType->id }} - {!! $current_shiftType->jbtyp_title !!} 
@stop

@section('content')

@if(Session::has('userGroup')
	AND (Session::get('userGroup') == 'marketing'
	OR Session::get('userGroup') == 'clubleitung'
	OR Session::get('userGroup') == 'admin'))

	<div class="panel panel-info">
		<div class="panel-heading">
			<h4 class="panel-title">#{{ $current_shiftType->id }}: "{!! $current_shiftType->jbtyp_title !!}" </h4>
		</div>
		<div class="panel panel-body no-padding">
			<table class="table table-hover">
				{!! Form::open(  array( 'route' => ['shiftType.update', $current_shiftType->id],
		                                'id' => $current_shiftType->id, 
		                                'method' => 'PUT', 
		                                'class' => 'shiftType')  ) !!}
					<tr>
						<td width="20%" class="left-padding-16">
							<i>{{ trans('mainLang.shiftType') }}:</i>
						</td>
						<td>
							{!! Form::text('jbtyp_title' . $current_shiftType->id, 
							   $current_shiftType->jbtyp_title, 
							   array('id'=>'jbtyp_title' . $current_shiftType->id)) !!}
						</td>
					</tr>
					<tr>
						<td width="20%" class="left-padding-16">
							<i>{{ trans('mainLang.begin') }}:</i>
						</td>
						<td>
							{!! Form::input('time','jbtyp_time_start' . $current_shiftType->id, 
							   $current_shiftType->jbtyp_time_start, 
							   array('id'=>'jbtyp_time_start' . $current_shiftType->id)) !!}
						</td>
					</tr>
					<tr>
						<td width="20%" class="left-padding-16">
							<i>{{ trans('mainLang.end') }}:</i>
						</td>
						<td>
							{!! Form::input('time','jbtyp_time_end' . $current_shiftType->id, 
							   $current_shiftType->jbtyp_time_end, 
							   array('id'=>'jbtyp_time_end' . $current_shiftType->id)) !!}
						</td>
					</tr>
					<tr>
						<td width="20%" class="left-padding-16">
							<i>{{ trans('mainLang.weight') }}:</i>
						</td>
						<td>
							{!! Form::text('jbtyp_statistical_weight' . $current_shiftType->id, 
							   $current_shiftType->jbtyp_statistical_weight, 
							   array('id'=>'jbtyp_statistical_weight' . $current_shiftType->id)) !!} <br/>
						</td>
					</tr>
					<tr>
						<td>
							&nbsp;
						</td>
						<td>
							<button type="reset" class="btn btn-small btn-default">{{ trans('mainLang.reset') }}</button>
					    	<button type="submit" class="btn btn-small btn-success">{{ trans('mainLang.update') }}</button>
						</td>
					</tr>
				{!! Form::close() !!}

				@if( $entries->count() == 0 )
					<tr>
						<td width="100%" colspan="2" class="left-padding-16">
							{{ trans('mainLang.shiftTypeNeverUsed') }}
							<a href="../shiftType/{{ $current_shiftType->id }}"
							   class="btn btn-small btn-danger"
							   data-toggle="tooltip"
			                   data-placement="bottom"
			                   title="&#39;&#39;{!! $current_shiftType->jbtyp_title !!}&#39;&#39; (#{{ $current_shiftType->id }}) löschen"
							   data-method="delete"
							   data-token="{{csrf_token()}}"
							   rel="nofollow"
							   data-confirm="{{ trans('mainLang.deleteConfirmation') }} &#39;&#39;{!! $current_shiftType->jbtyp_title !!}&#39;&#39; (#{{ $current_shiftType->id }})? {{ trans('mainLang.warningNotReversible') }}">
								   	{{ trans('mainLang.delete') }}
							</a>
							?
						</td>
					</tr>
				@else
					<tr>
						<td width="100%" colspan="2" class="left-padding-16">
					      	{{ trans('mainLang.shiftTypeUsedInFollowingEvents') }}
					    </td>
					</tr>
					<tr>
						<td width="100%" colspan="2" class="no-padding">
							<table class="table table-hover table-condensed" id="events-rows">
								<thead>
									<tr class="active">
										<th class="col-md-1 col-xs-1">
											#
										</th>
										<th class="col-md-3 col-xs-4">
											{{ trans('mainLang.event') }}
										</th>
										<th class="col-md-1 col-xs-1">
											{{ trans('mainLang.section') }}
										</th>
										<th class="col-md-2 col-xs-4">
											{{ trans('mainLang.date') }}
										</th>
										<th class="col-md-5 col-xs-2">
											{{ trans('mainLang.actions') }}
										</th>
									</tr>
								</thead>
								<tbody>
									@foreach($entries as $entry)
										<tr class="{!! "shiftType-event-row" . $entry->id !!}" name="{!! "shiftType-event-row" . $entry->id !!}">
											<td>
										      	{!! $entry->schedule->event->id !!}
											</td>
											<td>						
												<a href="/event/{!! $entry->schedule->event->id !!}">{!! $entry->schedule->event->evnt_title !!}</a>
											</td>
											<td>
												{!! $entry->schedule->event->getPlace->plc_title !!}
											</td>
											<td>
												{!! strftime("%a, %d. %b %Y", strtotime($entry->schedule->event->evnt_date_start)) !!} um  
												{!! date("H:i", strtotime($entry->schedule->event->evnt_time_start)) !!}
											</td>
											<td>
												{!! Form::open(  array( 'route'  => ['entry.update', $entry->id],
										                                'id' 	 => $entry->id,
										                                'method' => 'put',
										                                'class'  => 'updateShiftType')  ) !!}

									           		{{-- Fields to populate --}}
											        <input type="text" id="{!! 'entry' . $entry->id !!}" name="{!!   'entry' . $entry->id !!}" value="" hidden />
											        <input type="text" id="{!! 'shiftType' . $entry->id !!}" name="{!! 'shiftType' . $entry->id !!}" value="" hidden />

									           		<div class="btn-group dropdown-shiftTypes">

													  	<a href="#" 
													  	   class="btn btn-small btn-default" 
													  	   name={{ "dropdown" . $entry->id }}
										           		   id={{   "dropdown" . $entry->id }}
										           		   data-toggle="dropdown" 
										           		   aria-expanded="true">
										           		   		{{ trans('mainLang.substituteThisInstance') }}
													  			<span class="caret"></span>
													  	</a>

														<ul class="dropdown-menu">
															@foreach($shiftTypes as $shiftType)
																@if($shiftType->id !== $current_shiftType->id)
																	<li class="dropdown"> 
																		<a href="javascript:void(0);" 
																		   onClick="document.getElementById('{{ 'entry'. $entry->id }}').value='{{ $entry->id }}';
																					document.getElementById('{{ 'shiftType'. $entry->id }}').value='{{ $shiftType->id }}';
																					document.getElementById('{{ 'btn-submit-changes'. $entry->id }}').click();">
																		   	(#{{ $shiftType->id }}) 
																		   	{{  $shiftType->jbtyp_title }} 
																		   	(<i class='fa fa-clock-o'></i>
																			{{  date("H:i", strtotime($shiftType->jbtyp_time_start))
																				. "-" .
																			    date("H:i", strtotime($shiftType->jbtyp_time_end)) . ")" }}
																		</a>
																	</li>
																@endif
															@endforeach
														</ul>

													</div>
												{!! Form::submit( 'save', array('id' => 'btn-submit-changes' . $entry->id, 'hidden') ) !!}
							        			{!! Form::close() !!}
											</td>
										</tr>
									@endforeach

								</tbody>
							</table>
						</td>
					</tr>
				@endif
			</table>
		</div>
	</div>
		
	<center>
		{{ $entries->links() }}
	</center>
	
	<br/>
@else
	@include('partials.accessDenied')
@endif
@stop



