<div id="section-filter" class="btn-group hidden-print">  
	{{-- Show/hide events belonging to a chosen section --}}
	@foreach($sections as $section)
		<button class="btn btn-xs section-filter-selector" 
				type="button" 
				id="filter-{!! $section["plc_title"] !!}" 
				data-filter="{!! $section["plc_title"] !!}">
			{!! $section["plc_title"] !!}
		</button>
	@endforeach
</div>

