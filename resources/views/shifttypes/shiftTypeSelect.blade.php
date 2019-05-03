@php
/**
 * @param $route target route
 * @param $shift Lara\Shift or Lara\ShiftType to replace
 * @param $shiftType Lara\ShiftType which is the replacement
 * @param $shiftTypeId shifttypeid to skip
 */
@endphp
{!! Form::open(
['route'  => $route,
'id' 	 => 'shiftTypeOverride'.$shift->id,
'method' => 'post',
] ) !!}
{{-- Fields to populate --}}
<input type="text" id="{!! 'shift' . $shift->id !!}" name="shift" value="{{ $shift->id }}" hidden/>
<select name="shiftType" class="{{$selectorClass}}">
    <option value="-1" disabled selected>{{ trans('mainLang.substituteThisInstance') }}</option>
    @foreach($shiftTypes->sortBy('title') as $shiftType)
        @if($shiftType->id === $shiftTypeId)
            @continue
        @endif
        <option data-icon='far fa-clock' value="{{$shiftType->id}}">
            {{  date("H:i", strtotime($shiftType->start))
                . "-" .
                date("H:i", strtotime($shiftType->end)) . ")" }}
            (#{{ $shiftType->id }})
            {{  $shiftType->title }}
        </option>
    @endforeach
</select>
<input name="shiftName" value="{{$shift->title}}" hidden>
{!! Form::submit( 'save', ['id' => 'btn-submit-changes' . $shift->id, 'hidden'] ) !!}
{!! Form::close() !!}
