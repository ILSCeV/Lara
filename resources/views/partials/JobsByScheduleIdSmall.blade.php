{{-- ENTRY STATUS, USERNAME, DROPDOWN USERNAME and LDAP ID --}}
<div class="col-xs-5 col-md-5 input-append btn-group">      
    @include("partials.scheduleEntryName")
</div>                
        
{{-- ENTRY CLUB and DROPDOWN CLUB --}}
<div class="col-xs-3 col-md-3">
    @include("partials.scheduleEntryClub")                 
</div>   

{{-- SMALL COMMENT ICON: divs handled inside the partial, col-md/xs-1 for the icon; col-md/xs-12 for the comment input --}}
@include("partials.scheduleEntryCommentHidden")