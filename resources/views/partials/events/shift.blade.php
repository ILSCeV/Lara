<div class="box">
    <div class="fa fa-bars"></div>
    <div class="input-append btn-group">
        <input type="text"
               name="shifts[title][]"
               class="input"
               value="{{ $title }}"
               placeholder="{{ trans('mainLang.serviceTypeEnter') }}"
               autocomplete="off"
        />
        <ul class="dropdown-menu dropdown-shiftTypes" style="position: absolute;"></ul>
    </div>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <input type="time"
           class="input"
           name="shifts[start][]"
           value="{{ $startTime }}"
           required
    />

    <input type="time"
           class="input"
           name="shifts[end][]"
           value="{{ $endTime }}"
           required
    />
    <br class="visible-xs">
    <input type="hidden" name="shifts[optional][{{$counter}}]" class="isOptionalHidden" value="0">
    <label>{{trans('mainLang.optional')}}:&nbsp;<input type="checkbox" class="isOptional" name="shifts[optional][{{$counter}}]" {{$optional? 'checked="checked"':''}} /></label>

    &nbsp;<br class="visible-xs">{{ trans('mainLang.weight') }}:&nbsp;
    <input type="number"
           step="0.1"
           min="0"
           class="input"
           name="shifts[weight][]"
           value="{{ $weight }}"
           min="0"
           required
    />

    <input type="button" value="+" class="btn btn-small btn-success btnAdd" />
    &nbsp;&nbsp;
    <input type="button" value="&#8211;" class="btn btn-small btn-danger btnRemove" />
    <input hidden name="shifts[id][]" value="{{$shiftId}}" class="shiftId"/>
    <input hidden name="shifts[type][]" value="{{$shiftTypeId}}" class="shiftId"/>
    @if(!is_null($shiftId) && $shiftId!='' && \Lara\Utilities::requirePermission(['admin','marketing','clubleitung']))
        <a href="{{ route('shiftType.show',['id'=>$shiftTypeId]) }}" target="_blank" class="btn btn-primary btn-small" title="{{trans("mainLang.editShiftType")}}"> <span class="glyphicon glyphicon-pencil"></span> </a>
    @endif
    <hr class="visible-xs">
</div>
