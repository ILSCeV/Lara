<div class="card">
	<div class="card-header">
		<h4 class="card-title">{{ __('mainLang.adjustRoster') }}</h4>
	</div>

	<div class="card-body" id="main">
		{{-- shiftType fields --}}
	    <div id="shiftContainer">
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
					"shiftTypeId" => $shift->type->id
				])
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
