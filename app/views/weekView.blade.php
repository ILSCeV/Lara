{{-- Needs variables: 'events', 'schedules',  'date', 'tasks', 'entries', 'weekStart', 'weekEnd', 'persons', 'clubs' --}}



@extends('layouts.master')

@section('title')
	{{ utf8_encode(strftime("%d. %b", strtotime($weekStart))) }} - {{ utf8_encode(strftime("%d. %b", strtotime($weekEnd))) }}
@stop

@section('content')
<div class="row">
{{-- create button --}}
    <div class="col-md-3">
        @if(Session::has('userGroup')
            AND (Session::get('userGroup') == 'marketing'
            OR Session::get('userGroup') == 'clubleitung'))
            <a href="{{ Request::getBasePath() }}/calendar/create" class="btn btn-primary pull-left">Neue Veranstaltung erstellen</a>
        @endif
    </div>

{{-- prev/next week --}}
    <div class="col-md-6">
		<ul class="pager" >
			<li><a href="{{ Request::getBasePath() }}/calendar/{{$date['previousWeek']}}" class="hidden-print">&lt;&lt;</a></li>
			<li><h5 style="display: inline;">&nbsp;&nbsp;&nbsp;&nbsp;KW{{ $date['week']}}: 
			{{ utf8_encode(strftime("%d. %B", strtotime($weekStart))) }} - {{ utf8_encode(strftime("%d. %B", strtotime($weekEnd))) }}&nbsp;&nbsp;&nbsp;&nbsp;</h5></li>
			<li><a href="{{ Request::getBasePath() }}/calendar/{{$date['nextWeek']}}" class="hidden-print">&gt;&gt;</a></li>
		</ul>
    </div>

{{-- club filtering --}}
    <div class="col-md-3">
        @include('partials.filter')
    </div>
</div>

{{ Form::open(array('action' => array('bulkUpdate', $date['year'], $date['week']))) }}	

@if (count($events)>0)
	{{ Form::submit('Änderungen speichern', array('class'=>'btn btn-success')) }}
@else
	<div class="panel">
		Keine Veranstaltungen geplant
	</div>
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
					<div class="inline-block {{ $page_break }} {{ $clubEvent->getPlace->plc_title }}">
						@include('partials.weekCell', $clubEvent)
					</div>
				@endif
			@else
				<div class="inline-block {{ $page_break }} {{ $clubEvent->getPlace->plc_title }}">
					@include('partials.weekCell', $clubEvent)
				</div>
			@endif
			<?php $i++; $page_break=""; ?>
		@endforeach 

		<!-- whitespace on the far right -->
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<!-- end of whitespace -->
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
		<!-- whitespace on the far right -->
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<!-- end of whitespace -->
	</div>
</div>
<br>
{{ Form::close() }}
@stop



