{{-- Show/hide events belonging to a chosen section --}}
@php
/** @var \Illuminate\Support\Collection/\Lara\Section*/
$filterSection = $sections->sortByDesc('title');
@endphp
<script>
    var sectionCountString = "{{__('mainLang.countSectionsSelected')}}";
</script>
<button
 class="btn btn-primary btn-sm"
 type="button"
 data-bs-toggle="offcanvas"
 data-bs-target="#offcanvas"
 aria-controls="offcanvas"
 >
    <span id="filterCountButtonText"
    data-bs-toggle="tooltip" 
    data-bs-placement="bottom"
    >{{__('mainLang.countSectionsSelected', ['sel'=>0, 'total'=>0])}}</span>
</button>

<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvas"  data-bs-scroll="true" data-bs-backdrop="true" aria-labelledby="offcanvasLabel">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="offcanvasLabel">Enable to Display</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form class="m-2">
            <div class="mb-3 btn-toolbar d-flex justify-content-evenly" role="toolbar" aria-label="Toolbar with buttons">
        <button id="sections-filter-enable-all" type="button" class="btn btn-primary btn-sm me-auto">{{__('mainLang.enableAll')}}</button>
        <button id="sections-filter-disable-all" type="button" class="btn btn-secondary btn-sm">{{__('mainLang.disableAll')}}</button>
            </div>
        @foreach( $filterSection as $section)
        <div class="form-check form-switch mb-3">
            <input class="form-check-input palette-{{$section->color}}-900-Primary bg me-3 ms-1" type="checkbox" role="switch" id="section-filter-{{$section->id }}" 
            data-section-id="{{$section->id }}"
            data-section="{{$section->title}}">
            <label class="form-check-label badge palette-{{$section->color}}-900-Primary bg align-text-bottom" for="section-filter-{{ $section->id }}">{{$section->title }}</label>
        </div>
        @endforeach
        <div class="form-check form-switch">
            <input class="form-check-input palette-Purple-900-Primary bg me-3 ms-1" type="checkbox" role="switch" id="section-filter-survey" 
            data-section-id="survey"
            data-section="survey">
            <label class="form-check-label badge palette-Purple-900-Primary bg align-text-bottom" for="section-filter-survey"> {{__('mainLang.survey')}}</label>
        </div>

        <!--
        <div class="d-block p-auto m-auto">
            <span id="label-none"
                  class="badge label-filters palette-Red-900-Primary bg d-none float-end">
                &nbsp;
                {{__('mainLang.noSectionSelected')}}
            </span>
            <span id="label-section-survey"
                  class="badge label-filters palette-Purple-900 bg d-none float-end">
                {{__('mainLang.survey')}}
                &nbsp;
                <span class="far fa-times-circle"></span>
            </span>
            @foreach( $filterSection->reverse() as $section)
                <span id="label-section-{!! $section["id"] !!}"
                      class="badge label-filters palette-{{$section->color}}-500-Primary bg d-none float-end">
                    {!! $section["title"] !!}
                    &nbsp;
                    <span class="far fa-times-circle"></span>
                </span>
            @endforeach
        </div>
        
        <br>
        
        <div class="row col-md-12 d-block p-auto m-auto align-right">
            <select id="section-filter-selector"
                    class="form-select d-none show-tick float-end"
                    multiple
                    title="{{ __('mainLang.chooseAtLeastOne') }}"
                    data-selected-text-format="count > 2"
                    data-actions-box="true"
                    data-select-all-text="{{ __('mainLang.selectAll') }}"
                    data-deselect-all-text="{{ __('mainLang.selectNone') }}"
                    data-count-selected-text="{{ __('mainLang.countSectionsSelected') }}"
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
                            {{__('mainLang.survey')}}
                </option>
            </select>
        </div>
    -->
    </div>
</div>
