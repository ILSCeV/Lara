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
			{!! Form::open(['method' => 'POST', 'route' => ['updateTemplates']]) !!}
			<table class="table table-hover table-condensed">
				<thead>
					<tr>
						<th>
							&nbsp;
						</th>
						<th class="col-md-1">
							#
						</th>
						<th class="col-md-3">
							Vorlage
						</th>
						<th class="col-md-3">
							Gehört zur Veranstaltung
						</th>
						<th class="col-md-5">
							Verwaltung
						</th>
						<th>
							&nbsp;
						</th>
					</tr>
				</thead>
				<tbody>

			@foreach($templates as $template)
				<tr>
					<td>
						&nbsp;
					</td>
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



