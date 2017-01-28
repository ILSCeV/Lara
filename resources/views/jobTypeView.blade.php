{{-- Needs variable: $current_jobtype, $jobtypes, $entries --}}
@extends('layouts.master')

@section('title')
	Verwaltung: {!! $current_jobtype->jbtyp_title !!} (#{{ $current_jobtype->id }})
@stop

@section('content')

@if(Session::has('userGroup')
	AND (Session::get('userGroup') == 'marketing'
	OR Session::get('userGroup') == 'clubleitung'
	OR Session::get('userGroup') == 'admin'))

	<div class="panel panel-info">
		<div class="panel-heading">
			<h4 class="panel-title">#{{ $current_jobtype->id }}: "{!! $current_jobtype->jbtyp_title !!}" </h4>
		</div>
		<div class="panel panel-body no-padding">
			<table class="table table-hover">
				{!! Form::open(  array( 'route' => ['jobtype.update', $current_jobtype->id],
		                                'id' => $current_jobtype->id, 
		                                'method' => 'PUT', 
		                                'class' => 'jobType')  ) !!}
					<tr>
						<td width="20%" class="left-padding-16">
							<i>{{ trans('mainLang.jobType') }}:</i>
						</td>
						<td>
							{!! Form::text('jbtyp_title' . $current_jobtype->id, 
							   $current_jobtype->jbtyp_title, 
							   array('id'=>'jbtyp_title' . $current_jobtype->id)) !!}
						</td>
					</tr>
					<tr>
						<td width="20%" class="left-padding-16">
							<i>{{ trans('mainLang.begin') }}:</i>
						</td>
						<td>
							{!! Form::input('time','jbtyp_time_start' . $current_jobtype->id, 
							   $current_jobtype->jbtyp_time_start, 
							   array('id'=>'jbtyp_time_start' . $current_jobtype->id)) !!}
						</td>
					</tr>
					<tr>
						<td width="20%" class="left-padding-16">
							<i>{{ trans('mainLang.end') }}:</i>
						</td>
						<td>
							{!! Form::input('time','jbtyp_time_end' . $current_jobtype->id, 
							   $current_jobtype->jbtyp_time_end, 
							   array('id'=>'jbtyp_time_end' . $current_jobtype->id)) !!}
						</td>
					</tr>
					<tr>
						<td width="20%" class="left-padding-16">
							<i>{{ trans('mainLang.weight') }}:</i>
						</td>
						<td>
							{!! Form::text('jbtyp_statistical_weight' . $current_jobtype->id, 
							   $current_jobtype->jbtyp_statistical_weight, 
							   array('id'=>'jbtyp_statistical_weight' . $current_jobtype->id)) !!} <br/>
						</td>
					</tr>
					<tr>
						<td>
							&nbsp;
						</td>
						<td>
							<button type="reset" class="btn btn-small btn-default">Reset</button>
					    	<button type="submit" class="btn btn-small btn-success">Update</button>
						</td>
					</tr>
				{!! Form::close() !!}

				@if( $entries->count() == 0 )
					<tr>
						<td width="100%" colspan="2" class="left-padding-16">
							Dieser Diensttyp wird bei keinem einzigen Event benutzt... Traurig, so was...<br/>
							Vielleicht wäre es sinnvoll, ihn einfach zu
							<a href="../jobtype/{{ $current_jobtype->id }}"
							   class="btn btn-small btn-danger"
							   data-toggle="tooltip"
			                   data-placement="bottom"
			                   title="&#39;&#39;{!! $current_jobtype->jbtyp_title !!}&#39;&#39; (#{{ $current_jobtype->id }}) löschen"
							   data-method="delete"
							   data-token="{{csrf_token()}}"
							   rel="nofollow"
							   data-confirm="Möchtest du &#39;&#39;{!! $current_jobtype->jbtyp_title !!}&#39;&#39; (#{{ $current_jobtype->id }}) wirklich löschen? Diese Aktion kann man nicht rückgängig machen!">
								   	löschen
							</a>
							?
						</td>
					</tr>
				@else
					<tr>
						<td width="100%" colspan="2" class="left-padding-16">
					      	Dieser Dienstyp wird bei folgenden Events eingesetzt. Um ihn zu entfernen, ersetze jede Instanz erst mit einem anderen Diensttyp.
					    </td>
					</tr>
					<tr>
						<td width="100%" colspan="2" class="no-padding">
							<table class="table table-hover table-condensed" id="events-rows">
								<thead>
									<tr class="active">
										<th>
											&nbsp;
										</th>
										<th class="col-md-1">
											ID
										</th>
										<th class="col-md-3">
											Event
										</th>
										<th class="col-md-1">
											Sektion
										</th>
										<th class="col-md-2">
											Wann?
										</th>
										<th class="col-md-5">
											Aktionen
										</th>
									</tr>
								</thead>
								<tbody>
									@foreach($entries as $entry)
										<tr class="{!! "jobtype-event-row" . $entry->id !!}" name="{!! "jobtype-event-row" . $entry->id !!}">
											<td>
												&nbsp;
											</td>
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
												{!! strftime("%a, %d. %b", strtotime($entry->schedule->event->evnt_date_start)) !!} um  
												{!! date("H:i", strtotime($entry->schedule->event->evnt_time_start)) !!}
											</td>
											<td>
												{!! Form::open(  array( 'route'  => ['entry.update', $entry->id],
										                                'id' 	 => $entry->id,
										                                'method' => 'put',
										                                'class'  => 'updateJobtype')  ) !!}

									           		{{-- Fields to populate --}}
											        <input type="text" id="{!! 'entry' . $entry->id !!}" name="{!!   'entry' . $entry->id !!}" value="" hidden />
											        <input type="text" id="{!! 'jobtype' . $entry->id !!}" name="{!! 'jobtype' . $entry->id !!}" value="" hidden />

									           		<div class="btn-group dropdown-jobtypes">

													  	<a href="#" 
													  	   class="btn btn-small btn-default" 
													  	   name={{ "dropdown" . $entry->id }}
										           		   id={{   "dropdown" . $entry->id }}
										           		   data-toggle="dropdown" 
										           		   aria-expanded="true">
													  			Ersetze "{!! $current_jobtype->jbtyp_title !!}" (#{{ $current_jobtype->id }}) hier durch...
													  			<span class="caret"></span>
													  	</a>

														<ul class="dropdown-menu">
															@foreach($jobtypes as $jobtype)
																@if($jobtype->id !== $current_jobtype->id)
																	<li class="dropdown"> 
																		<a href="javascript:void(0);" 
																		   onClick="document.getElementById('{{ 'entry'. $entry->id }}').value='{{ $entry->id }}';
																					document.getElementById('{{ 'jobtype'. $entry->id }}').value='{{ $jobtype->id }}';
																					document.getElementById('{{ 'btn-submit-changes'. $entry->id }}').click();">
																		   	(#{{ $jobtype->id }}) 
																		   	{{  $jobtype->jbtyp_title }} 
																		   	(<i class='fa fa-clock-o'></i>
																			{{  date("H:i", strtotime($jobtype->jbtyp_time_start))
																				. "-" .
																			    date("H:i", strtotime($jobtype->jbtyp_time_end)) . ")" }}
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



