{{-- Needs variables: clubEvents, date, schedule, entries --}}



@extends('layouts.master')

@section('title')
	@if(count($events)==0)
		Keine Treffer
	@else
		Gefundene Veranstaltungen
	@endif 
@stop

@section('content')
	
<ul class="pager" >
	<li><a href="{{ Request::getBasePath() }}/calendar/{{$date['previousWeek']}}" class="hidden-print">&lt;&lt;</a></li>
	<li><h5 style="display: inline;">&nbsp;&nbsp;&nbsp;&nbsp;Kalenderwoche {{ $date['week']}}: 
	vom {{ strftime("%d. %B", strtotime($weekStart)) }} bis {{ strftime("%d. %B", strtotime($weekEnd)) }}&nbsp;&nbsp;&nbsp;&nbsp;</h5></li>
	<li><a href="{{ Request::getBasePath() }}/calendar/{{$date['nextWeek']}}" class="hidden-print">&gt;&gt;</a></li>
</ul>
@if(Session::has('userGroup')
        AND (Session::get('userGroup') == 'marketing'
        OR Session::get('userGroup') == 'clubleitung'))
        <a href="{{ Request::getBasePath() }}/calendar/create" class="btn btn-primary hidden-print" >Neue Veranstaltung erstellen</a>
@endif
<div  class="day-container">
	<div class="row">
		{{-- add counter for page-break css --}}
		<?php $i=1; $page_break=""; ?>
		@foreach($events as $clubEvent)
			@if($i % 4 == 0)
				<?php $page_break="page-break"; ?>
			@endif
			@if($clubEvent->evnt_is_private)
				@if(Session::has('userId'))
					<div class="inline-block {{ $page_break }}">
						@include('partials.weekCell', $clubEvent)
					</div>
				@endif
			@else
				<div class="inline-block {{ $page_break }}">
					@include('partials.weekCell', $clubEvent)
				</div>
			@endif
			<?php $i++; $page_break=""; ?>
		@endforeach 
	</div>
</div>

<br>

<div  class="day-container">
	<div class="row">
		{{-- add counter for page-break css --}}
		<?php $i=1; $page_break=""; ?>
		@foreach($tasks as $task)
			@if($i % 4 == 0)
				<?php $page_break="page-break"; ?>
			@endif
				<div class="inline-block {{ $page_break }}">
					@include('partials.taskWeekCell', $task)
				</div>
			<?php $i++; $page_break=""; ?>
		@endforeach 
	</div>
</div>

@stop



