@if( is_null($entry->getPerson) )
    <div class="red">
@else
    <div class="green">
@endif
        <span class="word-break" 
              data-toggle="tooltip" 
              data-placement="top" 
              title="{{ date("H:i", strtotime($entry->start)) .
              "-" . 
              date("H:i", strtotime($entry->entry_time_end)) }}">
                <small>
                    <strong>
                        {{ $entry->type->title() }}
                    </strong>
                    <div class="entry-time hide text-dark-grey">
                        {!! "(" . date("H:i", strtotime($entry->start))
                        . "-" .
                        date("H:i", strtotime($entry->entry_time_end)) . ")" !!}
                    </div>
                </small>
        </span>
    </div>