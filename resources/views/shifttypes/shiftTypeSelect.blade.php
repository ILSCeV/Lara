{!! Form::open(
['route'  => 'shiftTypeOverride',
'id' 	 => 'shiftTypeOverride'.$shift->id,
'method' => 'post',
] ) !!}
{{-- Fields to populate --}}
<input type="text" id="{!! 'shift' . $shift->id !!}" name="shift" value="{{ $shift->id }}" hidden/>
<select name="shiftType" data-submit="{{ 'shiftTypeOverride'.$shift->id }}" class="shiftTypeSelector">
    <option value="-1" disabled selected>{{ trans('mainLang.substituteThisInstance') }}</option>
    @foreach($shiftTypes->sortBy('title') as $shiftType)
        @if($shiftType->id === $current_shiftType->id)
            @continue
        @endif
        <option data-icon='fa fa-clock-o' value="{{$shiftType->id}}">
            {{  date("H:i", strtotime($shiftType->start))
                . "-" .
                date("H:i", strtotime($shiftType->end)) . ")" }}
            (#{{ $shiftType->id }})
            {{  $shiftType->title }}
        </option>
    @endforeach
</select>
{!! Form::submit( 'save', ['id' => 'btn-submit-changes' . $shift->id, 'hidden'] ) !!}
{!! Form::close() !!}
