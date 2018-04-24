<div id="section-filter" class="hidden-print">
	{{-- Show/hide events belonging to a chosen section --}}

	<select id="section-filter-selector" class="hidden show-tick" multiple title="{{ trans('mainLang.chooseAtLeastOne') }}" data-selected-text-format="count > 2" data-actions-box="true">
	@foreach($sections as $section)
		<option value="filter-{!! $section["title"] !!}" class="palette-{!! $section["color"] !!}-100 bg">{!! $section["title"] !!}</option>
	@endforeach
	</select>
</div>

