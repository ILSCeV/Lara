<div class="card border-light rounded">

    {{-- Check if the event is still going on --}}
    @php
        /** @var \Lara\ClubEvent $clubEvent */
        $createPersonLdapId = $clubEvent->creator ? $clubEvent->creator->person->prsn_ldap_id : null;
        $isAllowedToSee = (\Auth::hasUser() && \Auth::user()->hasPermissionsInSection($clubEvent->section, \Lara\utilities\RoleUtility::PRIVILEGE_CL)) || Lara\Person::isCurrent($createPersonLdapId);
        $isUnBlocked = is_null($clubEvent->unlock_date) || \Carbon\Carbon::now()->greaterThanOrEqualTo($clubEvent->unlock_date) || $isAllowedToSee;
        $classString = 'card-header rounded-top';
        $clubEventClass = \Lara\utilities\ViewUtility::getEventPaletteClass($clubEvent);
    @endphp
    {{-- Set card color --}}
    <div class="{{ $classString }} {{ $clubEventClass }}">
        <h4 @class(['event-cancelled' => $clubEvent->canceled == 1, 'card-title'])>
            @include('partials.event-marker')
            &nbsp;
            <a class="{{ $clubEventClass }}" href="{{ URL::route('event.show', $clubEvent->id) }}">
                <span class="name">{{ $clubEvent->evnt_title }}</span>
            </a>
        </h4>

        {{ utf8_encode(strftime('%a, %d. %b', strtotime($clubEvent->evnt_date_start))) }}
        &nbsp;
        DV: {{ date('H:i', strtotime($clubEvent->getSchedule->schdl_time_preparation_start)) }}
        &nbsp;
        <i class="far fa-clock"></i> {{ date('H:i', strtotime($clubEvent->evnt_time_start)) }}
        -
        {{ date('H:i', strtotime($clubEvent->evnt_time_end)) }}
        &nbsp;
        <i class="fa-solid  fa-map-marker">&nbsp;</i>{{ $clubEvent->section->title }}

    </div>

    {{-- Show password input if schedule needs one --}}
    @if ($clubEvent->getSchedule->schdl_password != '')
        <div class="{{ $classString }} hidden-print">
            {!! Form::password('password' . $clubEvent->getSchedule->id, [
                'required',
                'class' => 'col-md-12 col-12',
                'id' => 'password' . $clubEvent->getSchedule->id,
                'placeholder' => Lang::get('mainLang.enterPasswordHere'),
            ]) !!}
            <br />
        </div>
    @endif

    <div class="card-body p-2">
        @if ($isUnBlocked)
            @if (!is_null($clubEvent->getSchedule))
                {{-- Show shifts --}}
                @include('partials.shifts.takeShiftTable', [
                    'shifts' => $clubEvent->getSchedule->shifts,
                    'hideComments' => true,
                    'commentsInSeparateLine' => true,
                ])
            @endif
        @else
            <div class="text-center">
                {{ __('mainLang.availableAt') }} {{ $clubEvent->unlock_date->isoFormat('DD.MM.YYYY hh:mm') }}
            </div>
        @endif
    </div>

</div>
