<div class="panel no-padding">
	<div class="panel-heading">
		<h4 class="panel-title">{{ trans('mainLang.adjustRoster') }}:</h4>
	</div>

	<div class="panel-body" id="main">
		{{-- shiftType fields --}}
	    <div id="shiftContainer" class="container shiftContainer">
            <?php $counter=0;?>
			@foreach($shifts as $shift)
				@include('partials.events.shift', [
					"title" => $shift->type->title,
					"startTime" => $shift->start,
					"endTime" => $shift->end,
					"weight" => $shift->statistical_weight,
					"shiftId" => $shift->id,
					"optional" => $shift->optional,
					"counter" => $counter,
					'shiftTypeId' => $shift->type->id
				])
				<br class="visible-xs">
				<?php $counter = $counter+1;?>
			@endforeach

			@include('partials.events.shift', [
				"title" => "",
				"startTime" => "21:00",
				'endTime' => "01:00",
				"weight" => 1,
				"shiftId" => "",
				"optional" => 0,
				'shiftTypeId' => ""
			])

		</div>
	</div>
</div>
