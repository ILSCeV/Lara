@extends('layouts.master')
@php
    /**
    *   @var \Lara\User $user
    *   @var \Illuminate\Support\Collection|\Lara\Shift $shifts
    */
@endphp
@section('title')
    {{ trans('mainLang.userPersonalPage') }} - {{ $user->name }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h4 class="panel-title">{{ trans('mainLang.userPersonalPage') }}: {{ $user->name }}
                    - {{ $user->section->title }}</h4>
            </div>
            <div class="panel-body">
                <div class="col-md-12 ">
                    {{Form::open(['route' => 'user.updatePersonalSettings'])}}
                    <table class="table table-hover">
                        {{-- is club name public --}}
                        <tr class="form-group">
                            <td width="20%" class="left-padding-16">
                                <label for="endTime">
                                    <i>{{ trans('mainLang.privateClubName') }}</i>
                                </label>
                            </td>
                            <td>
							<span class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                <div class="radio-inline">
                                    <label>
                                        {!! Form::radio( 'is_name_private','null', $user->is_name_private === null) !!}
                                        {{ trans('mainLang.privateClubNameNull') }}
                                    </label>
                                </div>
                                <div class="radio-inline">
                                    <label>
                                        {!! Form::radio( 'is_name_private','true', $user->is_name_private === 1) !!}
                                        {{ trans('mainLang.privateClubNameYes') }}
                                    </label>
                                </div>
                                <div class="radio-inline">
                                    <label>
                                        {!! Form::radio( 'is_name_private','false', $user->is_name_private === 0) !!}
                                        {{ trans('mainLang.privateClubNameNo') }}
                                    </label>
                                </div>
                            </span>
                            </td>
                        </tr>
                    </table>
                    <div>
                        <div class="btn-group">
                            <button type="reset"
                                    class="btn btn-small btn-default">{{ trans('mainLang.reset') }}</button>
                            <button type="submit"
                                    class="btn btn-small btn-success">{{ trans('mainLang.update') }}</button>
                        </div>
                    </div>
                    {{Form::close()}}
                </div>
                <div>
                    <table class="col-md-12">
                        <caption class="text-center">{{trans('mainLang.upcomingShifts')}}:</caption>
                        <thead>
                        <tr>
                            <th class="text-center col-md-3">{{trans('mainLang.shift')}}</th>
                            <th class="text-center col-md-3">{{trans('mainLang.event')}}</th>
                            <th class="text-center col-md-3">{{trans('mainLang.date')}}</th>
                            <th class="text-center col-md-3">{{trans('mainLang.begin')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            /** @var \Lara\Shift $shift */
                        @endphp
                        @foreach($shifts as $shift )
                            <tr>
                                <td class="text-center">
                                    {{ $shift->type->title }}
                                </td>
                                <td class="text-center">
                                    {{ $shift->schedule->event->evnt_title }}
                                </td>
                                <td class="text-center">
                                    {{ (new DateTime($shift->schedule->event->evnt_date_start))->format('d.m.Y') }}
                                </td>
                                <td class="text-center">
                                    {{ (new DateTime($shift->start))->format('H:i') }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
