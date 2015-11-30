<!-- Needs variables: $entry -->

<!-- Show only the icon first -->
<div class="col-xs-1 col-md-1">
    @if( is_null($entry->getPerson) )
	        <button type="button" class="showhide btn-small btn-default hidden-print" data-dismiss="alert">
	            <i class="fa fa-comment-o"></i>
	        </button>
    @else           
        @if( $entry->entry_user_comment == "" )
            <button type="button" class="showhide btn-small btn-default hidden-print" data-dismiss="alert">
                <i class="fa fa-comment-o"></i>
            </button>
        @else
            <button type="button" class="showhide btn-small btn-default hidden-print" data-dismiss="alert">
                <i class="fa fa-comment"></i>
            </button>
        @endif
    @endif
</div>

<!-- Hidden comment field to be opened after the click on the icon 
	 see vedst-scripts "Show/hide comments" function -->
<div class="col-md-12 col-xs-12">
	<div class="hidden-print hide">
	        @if( is_null($entry->getPerson) )   
                {!! Form::text('comment' . $entry->id, 
                               Input::old('comment' . $entry->id),  
                               array('placeholder'=>'Kommentar hier hinzufügen', 
                                     'class'=>'col-xs-12 col-md-12')) 
                !!}
	         @else
                {!! Form::text('comment' . $entry->id, 
                               $entry->entry_user_comment, 
                               array('placeholder'=>'Kommentar hier hinzufügen',
                                     'class'=>'col-xs-12 col-md-12')) 
                !!}
	        @endif
	</div>
</div>