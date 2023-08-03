<div @class(['shift_title',
'shift_optional'=> $shift->optional,
'shift_free' => is_null($shift->getPerson),
'shift_taken' => !is_null($shift->getPerson)
])>
        <span class="word-break"
              data-bs-toggle="tooltip"
              data-bs-placement="top"
              title="{{ date("H:i", strtotime($shift->start)) .
              "-" .
              date("H:i", strtotime($shift->end)) }}">
            <strong>
                {{ $shift->type->title() }}
            </strong>
            @if($shift->optional)
                ({{Lang::get('mainLang.optionalShort')}})
            @endif
            <div class="shift-time hide">
                {!! "(" . date("H:i", strtotime($shift->start))
                . "-" .
                date("H:i", strtotime($shift->end)) . ")" !!}
            </div>
        </span>
</div>
