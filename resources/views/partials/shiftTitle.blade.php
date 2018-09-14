@if($shift->optional)
    @if( is_null($shift->getPerson))
        <div class="shift_free shift_optional">
        @else
        <div class="shift_taken shift_optional">
    @endif
@else
    @if( is_null($shift->getPerson))
        <div class="shift_free">
        @else
        <div class="shift_taken">
    @endif
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
                    @if($shift->optional)
                    ({{Lang::get('mainLang.optionalShort')}})
                    @endif
                    <div class="shift-time hide text-dark-grey">
                        {!! "(" . date("H:i", strtotime($shift->start))
                        . "-" .
                        date("H:i", strtotime($shift->end)) . ")" !!}
                    </div>
                </small>
        </span>
    </div>
