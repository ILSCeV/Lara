{{-- Needs variables: schedule, templates, jobtypes, entries --}}

@extends('layouts.master')

@section('title')
	Aufgabe ändern
@stop

@section('content')

@if(Session::has('userId'))

{{ Form::open(['method' => 'POST', 'route' => ['editTask', $schedule->id]]) }}
	
	<div class="row">
		<div class="container col-md-6">
			<div class="panel">
				<div class="panel-heading">
					<h4 class="panel-title">Aufgabe ändern:</h4>
				</div>
				
				<br>
				
				<div class="panel-body">
					<div class="form-group">
				      	<label for="title" class="col-md-3 control-label">Titel:</label>
				      	<div class="col-md-9">
				      		{{ Form::text( 'title', $schedule->schdl_title , array('class'=>'form-control', 
				      										  'placeholder'=>'z.B. Flyer austeilen KW01',
				      										  'style'=>'cursor: auto',
				      										  'required') ) }}
				     	</div>
				    </div>
					
					<br>
					
				    <div class="form-group">	
						<label for="dueDate" class="col-md-3 control-label">Fälligkeitsdatum:</label>
						<div class="col-md-9">
							{{ Form::input('date', 'dueDate', $schedule->schdl_due_date) }}
						</div>
				    </div>

				    <br>

				    <div class="form-group">	
				     	<label for="showInWeekView" class="col-md-6 control-label">In der Wochenansicht anzeigen?</label>
				     	<div class="col-md-6">
							{{ Form::checkbox('showInWeekView', '1', $schedule->schdl_show_in_week_view ) }}&nbsp;
						</div>
				    </div>	

				    <br>
				</div>
			</div>
		</div>
	</div>


	@include('partials.editSchedule', array('schedule', 'templates', 'jobtypes', 'entries'))
	
	<br>
	
	{{ Form::submit('Änderungen speichern', array('class'=>'btn btn-primary')) }}
	&nbsp;&nbsp;
	<a href="javascript:history.back()" class="btn btn-default">Ohne Änderung zurück</a>
	
	{{ Form::close() }}
	
@else
	@include('partials.accessDenied')
@endif
@stop



