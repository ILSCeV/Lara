<!-- Needs variables: places -->

@extends('layouts.master')

@section('title')
	Verwaltung: Orte
@stop

@section('content')

@if(Session::has('userGroup')
	AND (Session::get('userGroup') == 'marketing'
	OR Session::get('userGroup') == 'clubleitung'))

	<div class="panel">
		<div class="panel-heading">
				
				<h4 class="panel-title">Verwaltung: Orte</h4>

		</div>
		<br>
		<div class="panel-body">	
			{!! Form::open(['method' => 'POST', 'route' => ['updatePlaces']]) !!}
			<table class="table table-hover table-condensed">
				<thead>
					<tr>
						<th class="col-md-1">
							Id
						</th>
						<th class="col-md-4">
							Place
						</th>
						<th class="col-md-7">
							Verwaltung
						</th>
					</tr>
				</thead>
				<tbody>

			@foreach($places as $place)
				<tr>
					<td>
						{{ $place->id }}
					</td>
					<td>
				      	{!! Form::text('plc_title' . $place->id, 
									   $place->plc_title, 
									   array('id'=>'plc_title' . $place->id)) !!}
					</td>
					<td>
						@if($place->id >> 2)
							{!! Form::label('destroy' . $place->id, 'Löschen?', array('class' => 'col-md-2 control-label')) !!}
							{!! Form::checkbox('destroy' . $place->id, true) !!}
						@else
							<small>Dieser Ort kann nicht gelöscht werden</small>
						@endif
					</td>
				</tr>
			@endforeach
				
				</tbody>
			</table>

			<br>
			{!! Form::submit('Änderungen speichern', array('class'=>'btn btn-primary')) !!}
			{!! Form::close() !!}
			
		</div>
	</div>


@else
	@include('partials.accessDenied')
@endif
@stop



