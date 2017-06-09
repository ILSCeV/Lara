<div class="panel">
	<div class="panel-heading">
		<h4 class="panel-title">{{ trans('mainLang.adjustRoster') }}:</h4>
	</div>
	<div class="panel-body" id="main">
	{{-- jobtype fields --}}
	    <span hidden>{{$counter = 0}}</span>
	    <div id="shiftContainer" class="container shiftContainer">
			@foreach($entries as $entry)
				@include('partials/events/shift.blade.php', [
					"title" => $entry->type->jbtyp_title,
					"startTime" => $entry->entry_time_start,
					"endTime" => $entry->entry_time_end,
					"weight" => $entry->entry_statistical_weight,
					"counter" => $counter
				])
				@php(++$counter)
			@endforeach
			@include('partials/events/shift.blade.php', [
				"title" => "",
				"startTime" => "21:00",
				'endTime' => "01:00",
				"weight" => 1,
				"counter" => $counter
			])
	    	<br>
			<input type="hidden" name="counter" id="counter" value="{{$counter}}" />
		</div>
	</div>
</div>