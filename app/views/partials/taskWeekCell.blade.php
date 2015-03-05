{{-- Needs variables: i, date, id --}}
<div class="panel">
	<div class="panel-heading">
		<h4 class="panel-title hidden-print"><a href="{{ Request::getBasePath() }}/task/id/{{ $task->id }}"> {{ $task->schdl_title }}</a></h4>
		<h4 class="panel-title visible-print"> {{ $task->schdl_title }}</h4>
					Fällig am: {{ strftime("%a, %d. %b", strtotime($task->schdl_due_date)) }} 
					&nbsp;&nbsp;&nbsp;&nbsp;
	</div>
	
	@if (!is_null($task->getEntries))
		<div class="panel-body">
			<table class="table" width="100%">
				<tbody>
					@include('partials.weekJobsByScheduleId', array('entries' => $task->getEntries))
				</tbody>
			</table>
		</div>
	@endif

</div>