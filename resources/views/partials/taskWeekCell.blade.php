<!-- Needs variables: i, date, id -->
<div class="panel">
	<div class="panel-heading">
		<h4 class="panel-title">
			<a href="{{ Request::getBasePath() }}/task/id/{{ $task->id }}"> 
				{{ $task->schdl_title }}
			</a>
		</h4>
		
		Fällig am: {{ strftime("%a, %d. %b", strtotime($task->schdl_due_date)) }} 
	</div>
	
	@if (!is_null($task->getEntries))
		<div class="panel-body">
			<table class="table table-borderless table-condensed" width="100%">
				<tbody>
					@include('partials.weekJobsByScheduleId', array('entries' => $task->getEntries))
				</tbody>
			</table>
	        @if(Session::has('userGroup')
	        AND (Session::get('userGroup') == 'marketing'
	        OR Session::get('userGroup') == 'clubleitung'))
				<div class="pull-right hidden-print">
					<small><a href="#" class="hide-event">Ausblenden</a></small>
				</div>
			@endif
		</div>
	@endif

</div>