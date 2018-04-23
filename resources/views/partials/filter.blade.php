<div id="section-filter" class="hidden-print">
	{{-- Show/hide events belonging to a chosen section --}}
	@foreach($sections as $section)
		<style>
			.toggle.{!! $section["title"] !!} input:checked {
				background-color: {!! $section["color"] !!};
			}
		</style>
	@endforeach

	<div class="row">
	@foreach($sections as $section)
		<div class="toggle {!! $section["title"] !!} col-xs-6 col-sm-2">
		<label for="filter-{!! $section["title"] !!}" >{!! $section["title"] !!}</label>
		<input class="section-filter-selector"
				type="checkbox"
				id="filter-{!! $section["title"] !!}" 
				data-filter="{!! $section["title"] !!}" />
		</div>
	@endforeach
		</div>
</div>

