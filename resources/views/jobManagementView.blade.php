<!-- Needs variables: jobtypes -->

@extends('layouts.master')

@section('title')
	Verwaltung: Diensttypen
@stop

@section('content')

@if(Session::has('userGroup')
	AND (Session::get('userGroup') == 'marketing'
	OR Session::get('userGroup') == 'clubleitung'))

	<div class="panel">
		<div class="panel-heading">
				<h4 class="panel-title">Verwaltung: Diensttypen</h4>
		</div>
		
			{!! Form::open(['method' => 'POST', 'route' => ['updateJobTypes']]) !!}
			<table class="table table-hover table-condensed">
				<thead>
					<tr>
						<th>
							&nbsp;
						</th>
						<th class="col-md-2">
							Dienst
						</th>
						<th class="col-md-3">
							Dauer
						</th>
						<th class="col-md-1">
							Wertung
						</th>
						<th class="col-md-6">
							Verwaltung
						</th>
						<th>
							&nbsp;
						</th>
					</tr>
				</thead>
				<tbody>

			@foreach($jobtypes as $jobtype)
				<tr>
					<td>
						&nbsp;
					</td>
					<td>
				      	{!! Form::text('jbtyp_title' . $jobtype->id, 
									   $jobtype->jbtyp_title, 
									   array('id'=>'jbtyp_title' . $jobtype->id)) !!}
					</td>
					<td>						
						{!! Form::input('time','jbtyp_time_start' . $jobtype->id, 
									   $jobtype->jbtyp_time_start, 
									   array('id'=>'jbtyp_time_start' . $jobtype->id)) !!}
						-
						{!! Form::input('time','jbtyp_time_end' . $jobtype->id, 
									   $jobtype->jbtyp_time_end, 
									   array('id'=>'jbtyp_time_end' . $jobtype->id)) !!}
					</td>
					<td>
						{!! Form::text('jbtyp_statistical_weight' . $jobtype->id, 
									   $jobtype->jbtyp_statistical_weight, 
									   array('id'=>'jbtyp_statistical_weight' . $jobtype->id)) !!}
					</td>
					<td>
						{!! Form::label('jbtyp_is_archived' . $jobtype->id, 'Archivieren?', array('class' => 'col-md-3 control-label')) !!}
						{!! Form::checkbox('jbtyp_is_archived' . $jobtype->id, 
									   	   true, 
									  	   $jobtype->jbtyp_is_archived) !!}
					</td>
					<td>
						&nbsp;
					</td>
				</tr>
			@endforeach
				
				</tbody>
			</table>

			</div>
		
	{!! Form::submit('Änderungen speichern', array('class'=>'btn btn-success')) !!}
	{!! Form::close() !!}

@else
	@include('partials.accessDenied')
@endif
@stop



