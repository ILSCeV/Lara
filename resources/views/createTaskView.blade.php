<!-- Needs variables: templates, jobtypes -->

@extends('layouts.master')

@section('title')
	Neue Aufgabe erstellen
@stop

@section('content')

@if(Session::has('userId'))

	{!! Form::open(['method' => 'POST', 'route' => ['newTask']]) !!}
	
	<div class="row">
		<div class="container col-md-6">
			<div class="panel">
				<div class="panel-heading">
					<h4 class="panel-title">Neue Aufgabe erstellen:</h4>
				</div>
				
				<br>
				
				<div class="panel-body">
					<div class="form-group">
				      	<label for="title" class="col-md-3 control-label">Titel:</label>
				      	<div class="col-md-9">
				      		{!! Form::text( 'title', '', array('class'=>'form-control', 
				      										  'placeholder'=>'z.B. Flyer austeilen KW01',
				      										  'style'=>'cursor: auto',
				      										  'required') ) !!}
				     	</div>
				    </div>
					
					<br>
					
				    <div class="form-group">	
						<label for="dueDate" class="col-md-3 control-label">Fälligkeitsdatum:</label>
						<div class="col-md-9">
							{!! Form::input('date', 'dueDate', date("Y-m-d")) !!}
						</div>
				    </div>

				    <br>

				    <div class="form-group">	
				     	<label for="showInWeekView" class="col-md-6 control-label">In der Wochenansicht anzeigen?</label>
				     	<div class="col-md-6">
							{!! Form::checkbox('showInWeekView', '1', false) !!}&nbsp;
						</div>
					</div> 

					<br>
				</div>
			</div>
		</div>
	</div>
	
	@include('partials.editSchedule')
	
	<br>
	
	{!! Form::submit('Aufgabe speichern', array('class'=>'btn btn-primary')) !!}
	&nbsp;&nbsp;&nbsp;&nbsp;
	<a href="javascript:history.back()" class="btn btn-default">Ohne Änderung zurück</a>
	
	{!! Form::close() !!}

@else
	@include('partials.accessDenied')
@endif
@stop



