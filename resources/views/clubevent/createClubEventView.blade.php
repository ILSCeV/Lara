@extends('layouts.master')

@section('title')
    @if ($createClubEvent)
        {{ __('mainLang.createNewVEvent') }}
    @else
        {{ __('mainLang.changeEventJob') }}
    @endif
@stop
@section('moreScripts')
    <script src="{{ asset(WebpackBuiltFiles::$assets['autocomplete.js']) }}"></script>
@endsection

@section('content')
    @php
        $labelClass = 'col-12 col-md-3 form-label';
        $inputClass = 'col-12 col-md-8 form-control';
    @endphp

    <div class="row mb-3">
        <div class="card">
            <div class="card-body">
                @if ($createClubEvent)
                    {!! Form::open(['method' => 'POST', 'id' => 'templateSelectorForm']) !!}
                    <div class="row">
                        <label for="templateSelector" class="col-auto col-form-label">{{ __('mainLang.template') }}:</label>
                        <div class="col-auto">
                            <div id="templateSelector" class="dropdown" data-live-search="true">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    Pick one template
                                </button>
                                <ul class="dropdown-menu">
                                    @foreach ($sections as $tSection)
                                        <b class="ms-3">{{ $tSection->title }}</b>
                                        @foreach ($templates->filter(function ($template) use ($tSection) {
                                            return $template->section_id == $tSection->id;
                                        }) as $template)
                                            <li>
                                                <a @class(['dropdown-item', 'active' => $template->id == $templateId])
                                                    href="{{ Request::getBasePath() }}/event/{{ substr($date, 6, 4) }}/{{ substr($date, 3, 2) }}/{{ substr($date, 0, 2) }}/{{ $template->id }}/create">{{ $template->title }}
                                                </a>
                                            </li>
                                        @endforeach
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <button class="d-none" type="submit"></button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                @elseIf(isset($baseTemplate) && !is_null($baseTemplate))
                    <div class="row mb-3 p-0">
                        <label class="col-form-label col-12 col-md-2">{{ __('mainLang.template') }}:
                            &nbsp;</label>
                        <div class="col-12 col-md-6">
                            <div class="row">
                                {{ $baseTemplate->title }}
                            </div>
                            <div class="row">
                                {{ __('mainLang.begin') }}: {{ $baseTemplate->time_start }}
                            </div>
                            <div class="row">
                                {{ __('mainLang.end') }}: {{ $baseTemplate->time_end }}
                            </div>
                        </div>

                    </div>
                @endif
            </div>

        </div>
    </div>

    @if ($createClubEvent)
        {!! Form::open(['method' => 'POST', 'route' => ['event.store']]) !!}
        <input type="hidden" name="templateId" value="{{$templateId}}"/>
    @else
        {!! Form::open(['method' => 'PUT', 'route' => ['event.update', $event->id]]) !!}
    @endif

    <div class="row">
        <div class="col-md-7">
            <div class="card">

                <h5 class="card-header">
                    @if ($createClubEvent)
                        {{ __('mainLang.createNewEvent') }}
                    @else
                        {{ __('mainLang.changeEventJob') }}
                    @endif
                </h5>

                <div class="card-body">
                    <div class="mb-3">
                        <div class="form-check">
                            {!! Form::checkbox('saveAsTemplate', '1', false, [
                                'class' => 'col-2 form-check-input',
                                'id' => 'saveAsTemplate',
                            ]) !!}
                            <label class="col-10 form-check-label" for="saveAsTemplate">
                                {{ __('mainLang.templateNewSaveQ') }}
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="{{ $labelClass }}">{{ __('mainLang.title') }}:</label>
                        @if (is_null($title))
                            {!! Form::text('title', '', [
                                'placeholder' => Lang::get('mainLang.placeholderTitleWineEvening'),
                                'style' => 'cursor: auto',
                                'class' => $inputClass,
                                'required',
                            ]) !!}
                        @else
                            {!! Form::text('title', $title, [
                                'placeholder' => Lang::get('mainLang.placeholderTitleWineEvening'),
                                'style' => 'cursor: auto',
                                'class' => $inputClass,
                                'required',
                            ]) !!}
                        @endif
                    </div>


                    <div class="mb-3">
                        <label for="subtitle" class="{{ $labelClass }}">{{ __('mainLang.subTitle') }}:</label>
                        @if (is_null($subtitle))
                            {!! Form::text('subtitle', '', [
                                'placeholder' => Lang::get('mainLang.placeholderSubTitleWineEvening'),
                                'class' => $inputClass,
                                'style' => 'cursor: auto',
                            ]) !!}
                        @else
                            {!! Form::text('subtitle', $subtitle, [
                                'placeholder' => Lang::get('mainLang.placeholderSubTitleWineEvening'),
                                'class' => $inputClass,
                                'style' => 'cursor: auto',
                            ]) !!}
                        @endif
                    </div>
                    <br>
                    @is('marketing', 'clubleitung', 'admin')
                        <div class="mb-3">
                            <label for="facebookDone" class="col-4 form-label">{{ __('mainLang.faceDone') }}?</label>
                            <select class="form-select" name="facebookDone" id="facebookDone">
                                <option value="-1" @if ($facebookNeeded === null) selected @endif>
                                    {{ __('mainLang.=FREI=') }} </option>
                                <option value="0" @if ($facebookNeeded !== null && $facebookNeeded === 0) selected @endif>
                                    {{ __('mainLang.no') }} </option>
                                <option value="1" @if ($facebookNeeded) selected @endif>
                                    {{ __('mainLang.yes') }} </option>
                            </select>
                        </div>
                        <br>
                        <div class="mb-3">
                            <label for="eventUrl" class="{{ $labelClass }}">{{ __('mainLang.eventUrl') }}:</label>
                            <input class="col-12 col-md-8 form-control" style="cursor: auto" type="url" name="eventUrl"
                                value="">
                        </div>
                        <br>
                        <div class="row mb-3">
                            <label for="evnt_type" class="col-3">{{ __('mainLang.type') }}:</label>
                            <div class="col-8">
                                @for ($i = 0; $i < 12; $i++)
                                    <div class="form-check">
                                        {{ Form::radio('type', $i, $type == $i, ['class' => 'form-check-input', 'id' => 'type' . $i]) }}
                                        <label class="form-check-label"
                                            for="{{ 'type' . $i }}">{{ \Lara\Utilities::getEventTypeTranslation($i) }}</label>
                                    </div>
                                @endfor
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                {!! Form::checkbox('isPrivate', '1', ($private + 1) % 2, [
                                    'class' => 'form-check-input ',
                                    'id' => 'evnt_private',
                                ]) !!}
                                <label for="evnt_private" class="form-check-label">{{ __('mainLang.showExtern') }}</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                {!! Form::checkbox('isCanceled', '1', $canceled, ['class' => 'form-check-input ', 'id' => 'canceled']) !!}
                                <label for="canceled" class="form-check-label"> {{ __('mainLang.canceled') }} </label>
                            </div>
                        </div>
                    @else
                        <div class="row mb-3">
                            <label for="evnt_type" class="col-3">{{ __('mainLang.type') }}:</label>
                            <div class="col-9">
                                &nbsp;<i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;
                                {{ __('mainLang.normalProgramm') }}
                                <br>
                                &nbsp;<i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;
                                {{ __('mainLang.special') }}
                                <br>
                                &nbsp;<i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;
                                {{ __('mainLang.LiveBandDJ') }}
                                <br>
                                &nbsp;<i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;
                                {{ __('mainLang.utilization') }}
                                <br>
                                &nbsp;{!! Form::radio('type', '4', ['checked' => 'checked', 'class' => 'form-check']) !!}
                                {{ __('mainLang.internalEvent') }}
                                <br>
                                &nbsp;<i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;
                                {{ __('mainLang.flooding') }}
                                <br>
                                &nbsp;<i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;
                                {{ __('mainLang.flyersPlacard') }}
                                <br>
                                &nbsp;<i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;
                                {{ __('mainLang.preSale') }}
                                <br>
                                &nbsp;{!! Form::radio('type', '9', [$type == 9 ? 'checked' : '', 'class' => 'form-check']) !!}
                                {{ __('mainLang.others') }}
                                <br>
                                &nbsp;<i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;
                                {{ __('mainLang.outsideEvent') }}
                                <br>
                                &nbsp;<i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;
                                {{ __('mainLang.buffet') }}
                                <br>
                                &nbsp;{!! Form::radio('type', '1', [$type == 1 ? 'checked' : '', 'class' => 'form-check']) !!}
                                {{ __('mainLang.information') }}
                                <br>
                                &nbsp;<i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;
                                {{ __('mainLang.survey') }}
                                <br>

                                <div>
                                    {!! Form::checkbox('isPrivate', '1', false, ['hidden']) !!}
                                    <span class="text-danger">
                                        <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                                        {{ __('mainLang.showForLoggedInMember') }}<br>
                                        {{ __('mainLang.showForExternOrChangeType') }} <br>
                                        {{ __('mainLang.askTheCLOrMM') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endis

                    <div class="mb-3">
                        <label for="section" class="col-form-label col-3">{{ __('mainLang.section') }}:
                            &nbsp;</label>
                        <select id="section" class="form-select" name="section">
                            @php
                                if (Auth::user()->isAn(\Lara\utilities\RoleUtility::PRIVILEGE_ADMINISTRATOR)) {
                                    $allowedSections = $sections;
                                } else {
                                    $allowedSections = Auth::user()
                                        ->roles->filter(function (\Lara\Role $r) {
                                            return $r->name == \Lara\utilities\RoleUtility::PRIVILEGE_MARKETING || $r->name == \Lara\utilities\RoleUtility::PRIVILEGE_MEMBER;
                                        })
                                        ->map(function (\Lara\Role $r) {
                                            return $r->section;
                                        });
                                }
                            @endphp
                            @foreach ($allowedSections->unique()->sortBy('title') as $eventSection)
                                <option value="{{ $eventSection->id }}"
                                    data-content="<span class='palette-{!! $eventSection->color !!}-900 bg'> {{ $eventSection->title }} </span>"
                                    @if ($eventSection->id == $section->id)
                                    selected
                            @endif >{{ $eventSection->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3" id="filter-checkboxes">
                        <label class="col-form-label {{ $labelClass }}">{{ __('mainLang.showFor') }}:</label>
                        <div id="filter">
                            @if (isset($templateId) && $templateId == null && $createClubEvent)
                                @foreach ($sections->sortBy('title') as $filterSection)
                                    <div class="form-check form-check-inline">
                                        {{ Form::checkbox(
                                            'filter[' . $filterSection->title . ']',
                                            $filterSection->id,
                                            $filterSection->id === Auth::user()->section_id,
                                            ['class' => 'form-check-input', 'id' => 'filter[' . $filterSection->title . ']'],
                                        ) }}
                                        <label class="form-check-label"
                                            for="{{ 'filter[' . $filterSection->title . ']' }}">{{ $filterSection->title }}
                                        </label>
                                    </div>
                                @endforeach
                            @else
                                @foreach ($sections->sortBy('title') as $filterSection)
                                    <div class="form-check form-check-inline">
                                        {{ Form::checkbox(
                                            'filter[' . $filterSection->title . ']',
                                            $filterSection->id,
                                            in_array($filterSection->title, $filter),
                                            ['class' => 'form-check-input', 'id' => 'filter[' . $filterSection->title . ']'],
                                        ) }}

                                        <label class="form-check-label"
                                            for="{{ 'filter[' . $filterSection->title . ']' }}">{{ $filterSection->title }}</label>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="mb-3" id="filter-checkboxes">
                        <label for="priceTickets" class="col-form-label col-md-7">
                            {{ __('mainLang.priceTickets') }}:
                            ({{ __('mainLang.studentExtern') }})</label>
                        <div id="priceTickets" class="input-group col-md-auto">
                            <div class="input-group mb-3">
                                <input class="form-control" type="number" name="priceTicketsNormal" step="0.1"
                                    min="0" placeholder="Student" value="{{ $priceTicketsNormal }}" />
                                <span class="input-group-text">€</span>
                                <span class="input-group-text">/</span>
                                <input class="form-control" type="number" name="priceTicketsExternal" step="0.1"
                                    min="0" placeholder="Extern" value="{{ $priceTicketsExternal }}" />
                                <span class="input-group-text">€</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3" id="filter-checkboxes">
                        <label for="price" class="col-form-label {{ $labelClass }}">
                            {{ __('mainLang.price') }}:
                            ({{ __('mainLang.studentExtern') }})</label>
                        <div id="price" class="input-group col-md-auto">
                            <div class="input-group mb-3">
                                <input class="form-control" type="number" name="priceNormal" step="0.1"
                                    min="0" placeholder="Student" value="{{ $priceNormal }}" />
                                <span class="input-group-text">€</span>
                                <span class="input-group-text">/</span>
                                <input class="form-control" type="number" name="priceExternal" step="0.1"
                                    min="0" placeholder="Extern" value="{{ $priceExternal }}" />
                                <span class="input-group-text">€</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="preparationTime"
                            class="col-form-label {{ $labelClass }}">{{ __('mainLang.DV-Time') }}:</label>
                        <div class="col-8">
                            {!! Form::input('time', 'preparationTime', $dv, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <br>
                    <div class="row mb-3">
                        <label for="beginDate"
                            class="col-form-label col-2 {{ $labelClass }}">{{ __('mainLang.begin') }}:</label>
                        <div class="row">
                            <div class="col-sm">
                            {!! Form::input('date', 'beginDate', date('Y-m-d', strtotime($date)), ['class' => 'form-control']) !!}
                            </div>
                            <div class="col-sm-2 form-text">{{ __('mainLang.at') }}</div>
                            <div class="col-sm-3">
                            {!! Form::input('time', 'beginTime', $timeStart, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row mb-3">
                        <label for="endDate"
                            class="col-form-label col-2 {{ $labelClass }}">{{ __('mainLang.end') }}:</label>
                        <div class="row">
                            <div class="col-sm">
                            @if ($createClubEvent)
                                {!! Form::input(
                                    'date',
                                    'endDate',
                                    date(
                                        'Y-m-d',
                                        strtotime($timeStart) < strtotime($timeEnd) ? strtotime($date) : strtotime('+1 day', strtotime($date)),
                                    ),
                                    ['class' => 'form-control'],
                                ) !!}
                            @else
                                {!! Form::input('date', 'endDate', $event->evnt_date_end, ['class' => 'form-control']) !!}
                            @endif
                                </div>
                            <div class="col-sm-2 form-text">{{ __('mainLang.at') }}</div>
                            <div class="col-sm-3">
                            {!! Form::input('time', 'endTime', $timeEnd, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password"
                            class="col-form-label {{ $labelClass }}">{{ __('mainLang.passwordEntry') }}:</label>
                        <div class="col-8">
                            {!! Form::password('password', ['class' => 'form-control', 'aria-describedby' => 'passwordHelpBlock']) !!}
                        </div>
                    </div>
                    <small id="passwordHelpBlock" class="form-text text-info">
                        <i class="fa fa-circle-info"></i>&nbsp;{{ __('mainLang.passwordDeleteMessage') }}
                    </small>

                    <div class="mb-3">
                        <label for="passwordDouble"
                            class="{{ $labelClass }}">{{ __('mainLang.passwordRepeat') }}:</label>
                        <div class="col-8">
                            {!! Form::password('passwordDouble', ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="unlockDate" class="{{ $labelClass }}">{{ __('mainLang.unlockDate') }}:</label>
                        <div class="col-8 date">
                            {!! Form::datetimeLocal('unlockDate', $createClubEvent ? '' : $event->unlock_date, [
                                'class' => 'form-control ',
                                'id' => 'unlockDateInput',
                            ]) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5 p-0-xs">
            <div class="card col-12 border-warning mb-3">
                <div class="card-header bg-warning">
                    <h4 class="card-title">{{ __('mainLang.moreInfos') }}:</h4>({{ __('mainLang.public') }})
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="col-md-12">
                            @if (is_null($info))
                                {!! Form::textarea('publicInfo', '', [
                                    'class' => 'form-control',
                                    'rows' => '8',
                                    'placeholder' => Lang::get('mainLang.placeholderPublicInfo'),
                                ]) !!}
                            @else
                                {!! Form::textarea('publicInfo', $info, [
                                    'class' => 'form-control',
                                    'rows' => '8',
                                    'placeholder' => Lang::get('mainLang.placeholderPublicInfo'),
                                ]) !!}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card col-12 border-secondary my-3">
                <div class="card-header">
                    <h4 class="card-title">{{ __('mainLang.details') }}:</h4>({{ __('mainLang.showOnlyIntern') }})
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="col-md-12">
                            @if (is_null($details))
                                {!! Form::textarea('privateDetails', '', [
                                    'class' => 'form-control',
                                    'rows' => '5',
                                    'placeholder' => Lang::get('mainLang.placeholderPrivateDetails'),
                                ]) !!}
                            @else
                                {!! Form::textarea('privateDetails', $details, [
                                    'class' => 'form-control',
                                    'rows' => '5',
                                    'placeholder' => Lang::get('mainLang.placeholderPrivateDetails'),
                                ]) !!}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            @include('partials.editSchedule')
        </div>
    </div>
    <div class="row">
        {!! Form::submit('Veranstaltung mit Dienstplan erstellen', [
            'class' => 'd-none',
            'id' => 'button-create-submit',
        ]) !!}
        <input class="d-none" name="evntIsPublished" type="text" value="0" />

        {{--

	    Disabling iCal until fully functional -> remove "Publish" button, rename "create unpublished" to just "create"


	    @is( 'marketing'
	     , 'clubleitung'
	     , 'admin')
			<button class="btn btn-primary" id="createAndPublishBtn">{{__('mainLang.createAndPublish')}}</button>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<br class="d-block d-sm-none">
	    @endis

	    --}}
        <div class="row justify-content-md-center my-2">
            @if ($createClubEvent)
                <button class="btn btn-primary me-md-3 col-md-5"
                    id="createUnpublishedBtn">{{ __('mainLang.createNewEvent') }}</button>
            @else
                {!! Form::submit(__('mainLang.update'), [
                    'class' => 'btn btn-success me-md-3 col-md-5',
                    'id' => 'button-edit-submit',
                ]) !!}
            @endif
            <a class="btn btn-secondary col-md-5 mt-sm-3 mt-md-0"
                href="javascript:confirm('Sure?')?history.back():null;">{{ __('mainLang.backWithoutChange') }}</a>
        </div>
        {!! Form::close() !!}

    </div>
@stop
