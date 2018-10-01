<div id="section-filter" class="hidden-print">
    {{-- Show/hide events belonging to a chosen section --}}
    <div class="col-md-12 no-padding">
        <span id="label-none" class="label label-filters palette-Red-900-Primary bg hidden pull-right" >&nbsp;{{trans('mainLang.noSectionSelected')}}
        </span>
        <span id="label-survey" class="label label-filters palette-Purple-900 bg hidden pull-right" >{{trans('mainLang.survey')}}
            &nbsp;
            <span class="glyphicon glyphicon-remove-circle"></span>
        </span>
        @foreach($sections->reverse() as $section)
            {{-- Formatting: "Section Name 123" => "section-name-123" --}}
            <span id="label-{!! str_replace(' ', '-', strtolower($section["title"])) !!}" class="label label-filters palette-{{$section->color}}-500-Primary bg hidden pull-right">
                {!! $section["title"] !!}
                &nbsp;
                <span class="glyphicon glyphicon-remove-circle"></span>
            </span>
        @endforeach
    </div>
    <br>
    <div class="col-md-12 no-padding">
        <select id="section-filter-selector" class="hidden show-tick pull-right bottom-padding"
                                             multiple
                                             title="{{ trans('mainLang.chooseAtLeastOne') }}"
                                             data-selected-text-format="count > 2"
                                             data-actions-box="true"
                                             data-select-all-text="{{ trans('mainLang.selectAll') }}"
                                             data-deselect-all-text="{{ trans('mainLang.selectNone') }}"
                                             data-count-selected-text="{{ trans('mainLang.countSectionsSelected') }}">
            @foreach($sections as $section)
                {{-- Formatting: "Section Name 123" => "section-name-123" --}}
                <option value="filter-{!! str_replace(' ', '-', strtolower($section["title"])) !!}"
                        class="palette-{{$section->color}}-500-Primary bg option-shadow">
                            {!! $section["title"] !!}
                </option>
            @endforeach
            <option value="filter-survey"
                    class="palette-Purple-900 bg option-shadow">
                        {{trans('mainLang.survey')}}
            </option>
        </select>
    </div>
</div>
