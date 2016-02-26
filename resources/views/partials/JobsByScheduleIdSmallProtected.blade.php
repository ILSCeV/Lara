<div class="col-xs-5 col-md-5 input-append btn-group">

    {{-- ENTRY STATUS --}}
    <div class="col-xs-2 col-md-2 no-padding" id="clubStatus{{ $entry->id }}">
        @include("partials.ScheduleEntryStatus")
    </div>

    {{-- ENTRY USERNAME--}}
    <div id="{!! 'userName' . $entry->id !!}" class="col-xs-10 col-md-10">
        {!! $entry->getPerson->prsn_name !!}
    </div>

    {{-- no need to show LDAP ID in this case --}}

</div>

{{-- ENTRY CLUB --}}
<div id="{!! 'club' . $entry->id !!}" class="col-xs-3 col-md-3 no-padding">
    {!! "(" . $entry->getPerson->getClub->clb_title . ")" !!}
</div>


{{-- ENTRY COMMENT --}}
{{-- Show only the icon first --}}
<div class="col-xs-1 col-md-1 no-padding">      
    @if( $entry->entry_user_comment == "" )
        <button type="button" class="showhide btn-small btn-default hidden-print" data-dismiss="alert">
            <i class="fa fa-comment-o"></i>
        </button>
    @else
        <button type="button" class="showhide btn-small btn-default hidden-print" data-dismiss="alert">
            <i class="fa fa-comment"></i>
        </button>
    @endif
</div>

{{-- Hidden comment field to be opened after the click on the icon 
     see vedst-scripts "Show/hide comments" function --}}         
<div id="{!! 'comment' . $entry->id !!}"
     class="col-xs-10 col-md-10 hidden-print hide col-md-offset-1 word-break">
    {!! !empty($entry->entry_user_comment) ? $entry->entry_user_comment : "-" !!}
</div>
