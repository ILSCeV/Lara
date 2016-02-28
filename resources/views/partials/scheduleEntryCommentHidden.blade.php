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
     
{!! Form::text('comment' . $entry->id, 
               $entry->entry_user_comment, 
               array('placeholder'=>'Kommentar hier hinzufügen',
                     'id'=>'comment' . $entry->id,
                     'class'=>'col-xs-10 col-md-10 hidden-print hide col-md-offset-1 col-xs-offset-1 word-break' )) 
!!}