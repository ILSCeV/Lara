<!-- Needs variables: templates -->

@extends('layouts.master')

@section('title')
	Verwaltung: Vorlagen
@stop

@section('content')

@if(Session::has('userGroup')
	AND (Session::get('userGroup') == 'marketing'
	OR Session::get('userGroup') == 'clubleitung'))

	<div class="panel">
		<div class="panel-heading">
				
				<h4 class="panel-title">Verwaltung: Vorlagen</h4>

		</div>
		<br>
		<div class="panel-body">	
			{!! Form::open(['method' => 'POST', 'route' => ['updateTemplates']]) !!}
			<table class="table table-hover table-condensed">
				<thead>
					<tr>
						<th class="col-md-1">
							ID
						</th>
						<th class="col-md-3">
							Vorlagenname
						</th>
						<th class="col-md-3">
							Gehört zur Veranstaltung
						</th>
						<th class="col-md-5">
							Verwaltung
						</th>
					</tr>
				</thead>
				<tbody>

			@foreach($templates as $template)
				<tr>
					<td>
						{{ $template->id }}
					</td>
					<td>
				      	{!! Form::text('schdl_title' . $template->id, 
									   $template->schdl_title, 
									   array('id'=>'schdl_title' . $template->id, 'class'=>'col-md-11')) !!}
					</td>
					<td>
						<a href="{{ Request::getBasePath() }}/calendar/id/{{ $template->evnt_id }}">
							{{ $template->evnt_id }}
							&nbsp;-&nbsp;
							{{ $template->getClubEvent->evnt_title }}
						</a>
					</td>
					<td>
							{!! Form::label('destroy' . $template->id, 'Vorlage aus der Liste entfernen?', array('class' => 'col-md-6 control-label')) !!}
							{!! Form::checkbox('destroy' . $template->id, true) !!}			
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



