@php
    if($shift->optional) {
        if( is_null($shift->getPerson)) {
            $shiftClass="shift_free shift_optional";
        } else {
            $shiftClass="shift_taken shift_optional";
        }
    } else
        if( is_null($shift->getPerson)) {
            $shiftClass="shift_free";
        } else {
            $shiftClass="shift_taken";
        }
@endphp

<div class="{{$shiftClass}} shift_title">
        <span class="word-break"
              data-toggle="tooltip"
              data-placement="top"
              title="{{ date("H:i", strtotime($shift->start)) .
              "-" .
              date("H:i", strtotime($shift->end)) }}">
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
        </span>
</div>
