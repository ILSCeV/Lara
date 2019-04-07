{{-- Show/hide events belonging to a chosen section --}}
@php
/** @var \Illuminate\Support\Collection/\Lara\Section*/
$filterSection = $sections->sortBy('title');
@endphp
<div class="row col-12 col-md-12 d-block p-auto m-auto">
    <span id="label-none"
          class="badge label-filters palette-Red-900-Primary bg d-none float-right">
        &nbsp;
        {{trans('mainLang.noSectionSelected')}}
    </span>
    <span id="label-section-survey"
          class="badge label-filters palette-Purple-900 bg d-none float-right">
        {{trans('mainLang.survey')}}
        &nbsp;
        <span class="far fa-times-circle"></span>
    </span>
    @foreach( $filterSection->reverse() as $section)
        <span id="label-section-{!! $section["id"] !!}"
              class="badge label-filters palette-{{$section->color}}-500-Primary bg d-none float-right">
            {!! $section["title"] !!}
            &nbsp;
            <span class="far fa-times-circle"></span>
        </span>
    @endforeach
</div>

<br>

<div class="row col-md-12 d-block p-auto m-auto align-right">
    <select id="section-filter-selector"
            class="d-none show-tick float-right"
            multiple
            title="{{ trans('mainLang.chooseAtLeastOne') }}"
            data-selected-text-format="count > 2"
            data-actions-box="true"
            data-select-all-text="{{ trans('mainLang.selectAll') }}"
            data-deselect-all-text="{{ trans('mainLang.selectNone') }}"
            data-count-selected-text="{{ trans('mainLang.countSectionsSelected') }}"
            data-style="btn btn-sm btn-light"
            >
        @foreach($filterSection as $section)
            <option value="filter-section-{!! $section["id"] !!}"
                    class="palette-{{$section->color}}-500-Primary bg option-shadow">
                        {!! $section["title"] !!}
            </option>
        @endforeach
        <option value="filter-section-survey"
                class="palette-Purple-900 bg option-shadow">
                    {{trans('mainLang.survey')}}
        </option>
    </select>
</div>
