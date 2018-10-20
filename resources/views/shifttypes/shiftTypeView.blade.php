{{-- Needs variable: $current_shiftType, $shiftTypes, $shifts --}}
@extends('layouts.master')

@section('title')
    {{ trans('mainLang.management') }}: #{{ $current_shiftType->id }} - {!! $current_shiftType->title !!}
@stop

@section('content')
    @php $inputClass="form-control "; @endphp
    @is(Roles::PRIVILEGE_ADMINISTRATOR, Roles::PRIVILEGE_CL, Roles::PRIVILEGE_MARKETING)
    <div class="card">
        <div class="card-header text-white bg-info">
            <h4 class="card-title">#{{ $current_shiftType->id }}: "{!! $current_shiftType->title !!}" </h4>
        </div>
        <div class="card-body p-0">
            <div class="row">
                {!! Form::open(  array( 'route' => ['shiftType.update', $current_shiftType->id],
                                        'id' => $current_shiftType->id,
                                        'method' => 'PUT',
                                        'class' => 'shiftType form-inline')  ) !!}

                <div class="pl-3 col">
                    <i>{{ trans('mainLang.shiftType') }}:</i>
                </div>
                <div class="col">
                    {!! Form::text('title' . $current_shiftType->id,
                       $current_shiftType->title,
                       array('id'=>'title' . $current_shiftType->id, 'class'=>$inputClass)) !!}
                </div>
                <div class="w-100"></div>
                <div class="pl-3 w-25 col">
                    <i>{{ trans('mainLang.begin') }}:</i>
                </div>
                <div class="col">
                    {!! Form::input('time','start' . $current_shiftType->id,
                       $current_shiftType->start,
                       array('id'=>'start' . $current_shiftType->id, 'class'=>$inputClass)) !!}
                </div>
                <div class="w-100"></div>

                <div class="pl-3 w-25 col">
                    <i>{{ trans('mainLang.end') }}:</i>
                </div>
                <div class="col">
                    {!! Form::input('time','end' . $current_shiftType->id,
                       $current_shiftType->end,
                       array('id'=>'end' . $current_shiftType->id, 'class'=>$inputClass)) !!}
                </div>
                <div class="w-100"></div>
                <div class="pl-3 w-25 col">
                    <i>{{ trans('mainLang.weight') }}:</i>
                </div>
                <div class="col">
                    {!! Form::text('statistical_weight' . $current_shiftType->id,
                       $current_shiftType->statistical_weight,
                       array('id'=>'statistical_weight' . $current_shiftType->id, 'class'=>$inputClass)) !!}
                    <br/>
                </div>
                <div class="w-100"></div>
                <div class="btn-group btn-group-sm">
                    <button type="reset" class="btn btn-sm btn-secondary">{{ trans('mainLang.reset') }}</button>
                    <button type="submit" class="btn btn-sm btn-success">{{ trans('mainLang.update') }}</button>
                </div>

                {!! Form::close() !!}
                <div class="w-100"></div>
                @if( $current_shiftType->shifts->count() == 0 )
                    <div class="pl-3 col-2">
                        {{ trans('mainLang.shiftTypeNeverUsed') }}
                        <a href="../shiftType/{{ $current_shiftType->id }}"
                           class="btn btn-small btn-danger"
                           data-toggle="tooltip"
                           data-placement="bottom"
                           title="&#39;&#39;{!! $current_shiftType->title !!}&#39;&#39; (#{{ $current_shiftType->id }}) löschen"
                           data-method="delete"
                           data-token="{{csrf_token()}}"
                           rel="nofollow"
                           data-confirm="{{ trans('mainLang.deleteConfirmation') }} &#39;&#39;{!! $current_shiftType->title !!}&#39;&#39; (#{{ $current_shiftType->id }})? {{ trans('mainLang.warningNotReversible') }}">
                            {{ trans('mainLang.delete') }}
                        </a>
                        ?
                    </div>

                @else


                    <table class="table table-hover table-sm" id="events-rows">
                        <caption
                            class="caption text-center caption-top">{{ trans('mainLang.shiftTypeUsedInFollowingEvents') }}</caption>
                        <thead>
                        <tr class="active">
                            <th class=" text-center">
                                #
                            </th>
                            <th class="text-center">
                                {{ trans('mainLang.event') }}
                            </th>
                            <th class="text-center">
                                {{ trans('mainLang.section') }}
                            </th>
                            <th class=" text-center">
                                {{ trans('mainLang.date') }}
                            </th>
                            <th class=" text-center">
                                {{ trans('mainLang.actions') }}
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($shifts as $shift)
                            {{-- ignore shifts without an event, example: shifts from bd-template  --}}
                            @if(is_null($shift->schedule) || is_null($shift->schedule->evnt_id))
                                @continue
                            @endif
                            @php
                                $isAllowedToEdit=\Lara\Utilities::requirePermission("admin") || $shift->schedule->event->section->title == Session::get('userClub');
                            @endphp

                            <tr class="{!! "shiftType-event-row" . $shift->id !!} @if(!$isAllowedToEdit) active @endif"
                                name="{!! "shiftType-event-row" . $shift->id !!}">
                                <td class="text-center">
                                    {!! $shift->schedule->event->id !!}
                                </td>
                                <td class="text-center">
                                    @if($isAllowedToEdit)
                                        <a href="/event/{!! $shift->schedule->event->id !!}">{!! $shift->schedule->event->evnt_title !!}</a>
                                    @else
                                        {{ $shift->schedule->event->evnt_title }}
                                    @endif
                                </td>
                                <td class="text-center">
                                    {!! $shift->schedule->event->section->title !!}
                                </td>
                                <td class="text-center">
                                    {!! strftime("%a, %d. %b %Y", strtotime($shift->schedule->event->evnt_date_start)) !!}
                                    um
                                    {!! date("H:i", strtotime($shift->schedule->event->evnt_time_start)) !!}
                                </td>
                                <td class="text-center">
                                    @if($isAllowedToEdit)
                                        @include('shifttypes.shiftTypeSelect',['shift'=>$shift,'shiftTypes' => $shiftTypes,'route'=>'shiftTypeOverride','shiftTypeId'=>$current_shiftType->id,'selectorClass'=>'shiftTypeSelector'])
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                    <div class="w-100"></div>
                    <table class="table table-hover table-sm" id="events-rows">
                        <caption class="caption text-center caption-top">
                            {{ trans('mainLang.shiftTypeUsedInFollowingTemplates') }}
                        </caption>
                        <thead>
                        <tr class="active">
                            <th class="text-center">
                                #
                            </th>
                            <th class="text-center">
                                {{ trans('mainLang.template') }}
                            </th>
                            <th class="text-center">
                                {{ trans('mainLang.section') }}
                            </th>
                            <th class="text-center">
                                {{ trans('mainLang.actions') }}
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($templates as $template)
                            @foreach($template->shifts as $shift)
                                @if($shift->shifttype_id != $current_shiftType->id)
                                    @continue
                                @endif
                                @php
                                    $isAllowedToEdit=\Lara\Utilities::requirePermission("admin") ||  Auth::user()->getSectionsIdForRoles(Roles::PRIVILEGE_MARKETING)->contains($template->section->id);
                                @endphp
                                <tr class="@if(!$isAllowedToEdit) active @endif">
                                    <td class="text-center">
                                        {{ $shift->id}}
                                    </td>
                                    <td class="text-center">
                                        @if($isAllowedToEdit)
                                            <a href="{{ route('template.edit', $template->id) }}">
                                                {{ $template->title }}
                                            </a>
                                        @else
                                            {{ $template->title }}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{ $template->section->title }}
                                    </td>
                                    <td class="text-center">
                                        @if($isAllowedToEdit)
                                            @include('shifttypes.shiftTypeSelect',['shift'=>$shift,'shiftTypes' => $shiftTypes, 'route'=>'shiftTypeOverride','shiftTypeId'=>$current_shiftType->id, 'selectorClass'=>'shiftTypeSelector'])
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>
                    <div class="w-100"></div>
                @endif
            </div>
        </div>
    </div>

    <div class="text-center">
        {{ $shifts->links() }}
    </div>

    <br/>
    @else
        @include('partials.accessDenied')
    @endis
@stop



