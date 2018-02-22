<div class="panel no-padding">
	<div class="panel-heading">
		<h4 class="panel-title">{{ trans('mainLang.adjustRoster') }}:</h4>
	</div>

	<div class="panel-body" id="main">
		{{-- shiftType fields --}}
	    <div id="shiftContainer" class="container shiftContainer">
			@foreach($shifts as $shift)
				@include('partials.events.shift', [
					"title" => $shift->type->title,
					"startTime" => $shift->start,
					"endTime" => $shift->end,
					"weight" => $shift->statistical_weight,
					"shiftId" => $shift->id,
					'shiftTypeId' => $shift->type->id
				])
				<br class="visible-xs">
			@endforeach

			@include('partials.events.shift', [
				"title" => "",
				"startTime" => $timeStart,
				'endTime' => $timeEnd,
				"weight" => 1,
				"shiftId" => "",
				'shiftTypeId' => ""
			])

		</div>
	</div>
</div>
