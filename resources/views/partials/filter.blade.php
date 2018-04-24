<div id="section-filter" class="hidden-print">
	{{-- Show/hide events belonging to a chosen section --}}
	@foreach($sections as $section)
		<span id="label-{!! $section["title"] !!}" class="label label-filters palette-{{$section->color}}-500-Primary bg">{!! $section["title"] !!}</span>
	@endforeach
	<select id="section-filter-selector" class="hidden show-tick" multiple title="{{ trans('mainLang.chooseAtLeastOne') }}" data-selected-text-format="count > 2" data-actions-box="true">
	@foreach($sections as $section)
		<option value="filter-{!! $section["title"] !!}" class="palette-{{$section->color}}-500-Primary bg option-shadow">{!! $section["title"] !!}</option>
	@endforeach
	</select>
</div>

