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
			<h4 class="panel-title">"{!! $current_jobtype->jbtyp_title !!}" (#{{ $current_jobtype->id }})</h4>
			Dauer:&nbsp;&nbsp;{!! date("H:i", strtotime($current_jobtype->jbtyp_time_start)) !!} -
							  {!! date("H:i", strtotime($current_jobtype->jbtyp_time_end)) !!}<br/>
			Statistische Wertung: {!! $current_jobtype->jbtyp_statistical_weight !!} <br/>
		</div>		
		@if( $entries->count() == 0 )
			<div class="panel panel-body">
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
			</div>
		@else
			<div class="panel-body">
		      	Dieser Dienstyp wird bei folgenden Events eingesetzt. Um ihn zu entfernen, ersetze jede Instanz erst mit einem anderen Diensttyp.
			</div>
			<table class="table table-hover table-condensed">
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
											@endforeach
										</ul>

									</div>
								{!! Form::submit( 'save', array('id' => 'btn-submit-changes' . $entry->id, 'hidden') ) !!}
			        			{!! Form::close() !!}
							</td>
						</tr>
					@endforeach

{{-- TODO FOR LATER: BATCH REASSIGN
						<tr>
							<td colspan="4">
						      	&nbsp;
							</td>
							<td>
								Ersetze <strong>alle</strong> Einträge oben durch
					           	<div class="input-append btn-group">
						           	<input type="text" 
						           		   name={{ "entry" . $event->id }}
						           		   class="input" 
						           		   id={{ "entry" . $event->id }}
						           		   value=""
						           		   placeholder="{{ trans('mainLang.serviceTypeEnter') }}"/>

									<span id="dropdown">
										<a class="btn-small btn-primary dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);">
											<span class="caret"></span>
										</a>

										<ul class="dropdown-menu">
											@foreach($jobtypes as $jobtype)
												<li class="dropdown"> 
													<a href="javascript:void(0);" 
													   onClick="$(this).dropdownSelect(&#39;{{ $jobtype->jbtyp_title }}&#39;, 
													   								   &#39;{{ $jobtype->jbtyp_time_start }}&#39;, 
													   								   &#39;{{ $jobtype->jbtyp_time_end }}&#39;,
													   								   &#39;{{ $jobtype->jbtyp_statistical_weight }}&#39;); alert('Will set jobtype of all of the above to jobtype {!! $jobtype->jbtyp_title !!} (#{!! $jobtype->id !!}) ')">
													   	(#{{ $jobtype->id }}) 
													   	{{  $jobtype->jbtyp_title }} 
													   	(<i class='fa fa-clock-o'></i>
														{{  date("H:i", strtotime($jobtype->jbtyp_time_start))
															. "-" .
														    date("H:i", strtotime($jobtype->jbtyp_time_end)) . ")" }}
													</a>
												</li>
											@endforeach
										</ul>
									</span>
								</div>
							</td>
						</tr>
--}}

				</tbody>
			</table>
		@endif
	</div>
		
	<center>
		{{ $entries->links() }}
	</center>
	
	<br/>
@else
	@include('partials.accessDenied')
@endif
@stop



