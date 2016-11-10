{{-- Needs variable: $current_jobtype, $jobtypes, $schedules --}}
@extends('layouts.master')

@section('title')
	Verwaltung: {!! $current_jobtype->jbtyp_title !!} (#{{ $current_jobtype->id }})
@stop

@section('content')

@if(Session::has('userGroup')
	AND (Session::get('userGroup') == 'marketing'
	OR Session::get('userGroup') == 'clubleitung'
	OR Session::get('userGroup') == 'admin'))

	<div class="panel">
		<div class="panel-heading">
				<h4 class="panel-title">"{!! $current_jobtype->jbtyp_title !!}" (#{{ $current_jobtype->id }}) ist bei folgenden Events im Einsatz:</h4>
		</div>		
		<table class="table table-hover table-condensed">
			<thead>
				<tr>
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
						Datum
					</th>
					<th class="col-md-5">
						Ersetze mit: <span>{{$counter = 0}}</span>
					</th>
				</tr>
			</thead>
			<tbody>
				<div class="container">
					@foreach($events as $event)
						<tr>
							<td>
						      	{!! $event->schedule->event->id !!}
							</td>
							<td>						
								{!! $event->schedule->event->evnt_title !!}
							</td>
							<td>
								{!! $event->schedule->event->plc_id !!}
							</td>
							<td>
								{!! $event->schedule->event->evnt_date_start !!} 
								{!! $event->schedule->event->evnt_time_start !!}-{!! $event->schedule->event->evnt_time_end !!}
							</td>
							<td>
								<div id={{ "box" . ++$counter }} class="box">
					           	<div class="input-append btn-group">
						           	<input type="text" 
						           		   name={{ "jobType" . $counter }}
						           		   class="input" 
						           		   id={{ "jobType" . $counter }}
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
													   								   &#39;{{ $jobtype->jbtyp_statistical_weight }}&#39;);">
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
					@endforeach
					<tr>
						<td colspan="5">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td colspan="5">
							<a href="../jobtype/{{ $current_jobtype->id }}"
								   class="btn btn-danger"
								   data-toggle="tooltip"
			                       data-placement="right"
			                       title="{!! $current_jobtype->jbtyp_title !!} löschen"
								   data-method="delete"
								   data-token="{{csrf_token()}}"
								   rel="nofollow"
								   data-confirm="Wirklich löschen?">
								   Änderungen speichern und "{!! $current_jobtype->jbtyp_title !!}" (#{{ $current_jobtype->id }}) löschen
								</a>
								&nbsp;&nbsp;
								Dafür musst du für jedes Event, wo dieser Dienst früher benutzt wurde, einen Ersatz gewählt haben. Geht auch seitenweise.
						</td>
					</tr>
				</div>
			</tbody>
		</table>
	</div>
		
	<center>
		{{ $events->links() }}
	</center>
	
	<br/>
@else
	@include('partials.accessDenied')
@endif
@stop



