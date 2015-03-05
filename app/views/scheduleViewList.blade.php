{{-- Need variables: schedule  --}}

@extends('layouts.master')

@section('title')
	Dienstplane
@stop

@section('content')

<center>{{ $schedules->links() }}</center>

@if(Session::has('userGroup')
    AND (Session::get('userGroup') == 'marketing'
    OR Session::get('userGroup') == 'clubleitung'))
    <a href="{{ Request::getBasePath() }}/calendar/create" class="btn btn-primary">Neue Veranstaltung erstellen</a>
@endif
<div class="panel">
	<table class="table table-striped table-hover shadow">
		<thead>
		    <tr>
		      	<th class="col-md-0">#</th>

		      	<th class="col-md-0">&nbsp;</th>

	      		<th class="col-md-8">Veranstaltung</th>

		      	<th class="col-md-3">Beginn</th>

		      	<th class="col-md-1">DV-Zeit</th>
		    </tr>
		</thead>
		<tbody>

		@foreach($schedules as $schedule)
		
			<tr>
				<td> {{ $schedule->evnt_id; }} </td>	

				<td style="padding-top: 15px; padding-right: 5px;"> 
				@if($schedule->schdl_password != '') 
					<i class="fa fa-key"></i> 
				@endif </td>			
				<td>
					<a href="{{ Request::getBasePath() }}/calendar/id/{{ $schedule->id }}">
						<b>{{{ $schedule->getClubEvent->evnt_title }}}</b>
					</a>
				</td>

				<td>
					<b>{{ strftime("%a, %d. %b", strtotime($schedule->getClubEvent->evnt_date_start)) }} 
					um {{ date("H:i", strtotime($schedule->getClubEvent->evnt_time_start)) }}</b>
				</td>

				<td>
					{{ date("H:i", strtotime($schedule->schdl_time_preparation_start)) }}
				</td>
			</tr>

		@endforeach

		</tbody>	
	</table>
	</div>
@stop
					