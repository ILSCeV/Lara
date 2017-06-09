<div class="box">
    <div class="fa fa-bars fa-2x"></div>
    <div class="input-append btn-group">
        <input type="text"
               name="shifts[title][]"
               class="input"
               value="{{ $title }}"
               placeholder="{{ trans('mainLang.serviceTypeEnter') }}"
        />
        <ul class="dropdown-menu dropdown-jobtypes" style="position: absolute;"/>
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

    &nbsp;<br class="visible-xs">{{ trans('mainLang.weight') }}:&nbsp;
    <input type="number"
           step="0.1"
           min="0"
           class="input"
           name="shifts[weight][]"
           value="{{ $weight }}"
           onkeypress="return event.charCode >= 48"
           min="0"
           required
    />
    <input type="button" value="+" class="btn btn-small btn-success btnAdd" />
    &nbsp;&nbsp;
    <input type="button" value="&#8211;" class="btn btn-small btn-danger btnRemove" />
    <input hidden name="shifts[id][]" value="{{$shiftId}}" class="shiftId"/>
    <input hidden name="shifts[type][]" value="{{$shiftTypeId}}" class="shiftId"/>
</div>
