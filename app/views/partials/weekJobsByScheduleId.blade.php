{{-- Needs variables: entries --}}

	@foreach($entries as $entry)
			<tr>
				@if( !is_null($entry->getPerson) && !empty($entry->entry_user_comment) )
					<td rowspan="2" class="col-md-4">
				@else
					<td class="col-md-4">
				@endif
						{{{ $entry->getJobType->jbtyp_title }}}
					</td>
				{{-- if entry is free - let anyone edit it --}}
				@if( is_null($entry->getPerson) )
					<td class="col-md-8 red">=FREI=
				@else
					<td class="col-md-8 green">{{{ $entry->getPerson->prsn_name }}} ({{{ $entry->getPerson->getClub->clb_title }}})
				@endif				
					</td>
			</tr>				
			@if( !is_null($entry->getPerson) && !empty($entry->entry_user_comment) )
				<tr>					
					<td class="comment">
						{{{ $entry->entry_user_comment }}}
					</td>
				</tr>
			@endif				
			
	@endforeach