@if( is_null($shift->getPerson) )
    <div class="red">
@else
    <div class="green">
@endif
        <span class="word-break" 
              data-toggle="tooltip" 
              data-placement="top" 
              title="{{ date("H:i", strtotime($shift->start)) .
              "-" . 
              date("H:i", strtotime($shift->end)) }}">
                <small>
                    <strong>
                        {{ $shift->type->title() }}
                    </strong>
                    <div class="shift-time hide text-dark-grey">
                        {!! "(" . date("H:i", strtotime($shift->start))
                        . "-" .
                        date("H:i", strtotime($shift->end)) . ")" !!}
                    </div>
                </small>
        </span>
    </div>
