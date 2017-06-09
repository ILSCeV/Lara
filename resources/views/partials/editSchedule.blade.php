<div class="panel">
	<div class="panel-heading">
		<h4 class="panel-title">{{ trans('mainLang.adjustRoster') }}:</h4>
	</div>
	<div class="panel-body" id="main">
	{{-- jobtype fields --}}
	    <div id="shiftContainer" class="container shiftContainer">
			@foreach($entries as $shift)
				@include('partials.events.shift', [
					"title" => $shift->type->jbtyp_title,
					"startTime" => $shift->start,
					"endTime" => $shift->entry_time_end,
					"weight" => $shift->entry_statistical_weight,
					"shiftId" => $shift->id,
					'shiftTypeId' => $shift->type->id
				])
			@endforeach
			@include('partials.events.shift', [
				"title" => "",
				"startTime" => "21:00",
				'endTime' => "01:00",
				"weight" => 1,
				"shiftId" => "",
				'shiftTypeId' => ""
			])
		</div>
	</div>
</div>