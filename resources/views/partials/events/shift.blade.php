<div class="box row row-cols-md-auto g-3 align-items-center border-bottom py-2">
    <div class="col-12">
        <i class="fa fa-bars" data-bs-toggle="tooltip" data-bs-placement="right" title="Drag to sort"></i>
    </div>

    <div class="col-12">
        <input type="text" name="shifts[title][]" class="form-control" value="{{ $title }}"
            placeholder="{{ __('mainLang.serviceTypeEnter') }}" autocomplete="off" />
        <ul class="dropdown-menu dropdown-shiftTypes" style="position: absolute;"></ul>
    </div>

    <div class="col-12">
        <input type="time" class="form-control" name="shifts[start][]" value="{{ $startTime }}" required
        data-bs-toggle="tooltip" data-bs-placement="left" title="Start Time" />
    </div>
    <div class="col-12">
        <input type="time" class="form-control" name="shifts[end][]" value="{{ $endTime }}" required 
        data-bs-toggle="tooltip" data-bs-placement="left" title="End Time"/>
    </div>

    <div class="col-12">
        <div class="form-check">
            <input type="hidden" name="shifts[optional][{{ $counter }}]" class="isOptionalHidden form-control"
                value="0">
            <input type="checkbox" class="isOptional form-check-input" name="shifts[optional][{{ $counter }}]"
                id="shifts[optional][{{ $counter }}]" {{ $optional ? 'checked="checked"' : '' }} />
            <label class="form-check-label"
                for="shifts[optional][{{ $counter }}]">{{ __('mainLang.optional') }}</label>
        </div>
    </div>

    {{-- weight --}}
    <div class="col-12">
        <label class="visually-hidden" for="shifts[weight][]">{{ __('mainLang.weight') }}</label>
        <input type="number" step="0.1" min="0" class="form-control col-5" name="shifts[weight][]"
            id="shifts[weight][]" value="{{ $weight }}" min="0" required data-bs-toggle="tooltip" data-bs-placement="left" title="{{ __('mainLang.weight') }}" />
    </div>


    <div class="col-12">
        <button class="btn btn-small btn-success btnAdd" ><i class="fa fa-copy" data-bs-toggle="tooltip" data-bs-placement="top" title="Duplicate"></i></button>
    </div>
    <div class="col-12">
        <button class="btn btn-small btn-danger btnRemove"><i class="fa fa-trash-can" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"></i></button>
    </div>


    <input hidden name="shifts[id][]" value="{{ $shiftId }}" class="shiftId" />
    <input hidden name="shifts[type][]" value="{{ $shiftTypeId }}" class="shiftId" />

    @if (!is_null($shiftId) && $shiftId != '' && \Lara\Utilities::requirePermission('admin', 'marketing', 'clubleitung'))
        <div class="col-12">
            <a href="{{ route('shiftType.show', [$shiftTypeId]) }}" target="_blank" class="btn btn-primary btn-small"
                title="{{ __('mainLang.editShiftType') }}">
                <i class="fa fa-pencil-alt"></i>
            </a>
        </div>
    @endif
</div>
