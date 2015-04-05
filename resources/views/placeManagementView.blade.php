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

			{!! Form::open(['method' => 'POST', 'route' => ['updatePlaces']]) !!}
			<table class="table table-hover table-condensed">
				<thead>
					<tr>
						<th>
							&nbsp;
						</th>
						<th class="col-md-1">
							#
						</th>
						<th class="col-md-4">
							Ort
						</th>
						<th class="col-md-7">
							Verwaltung
						</th>
						<th>
							&nbsp;
						</th>
					</tr>
				</thead>
				<tbody>

			@foreach($places as $place)
				<tr>
					<td>
						&nbsp;
					</td>
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



