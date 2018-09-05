@extends('layouts.master')
@php
    /**
    *   @var \Lara\User $user
    *   @var \Illuminate\Support\Collection|\Lara\Shift $shifts
    */
@endphp
@section('title')
    {{ trans('mainLang.userPersonalPage') }} - {{ $user->name }} ({{ $user->section->title }})
@endsection

@section('content')
    <div class="container-fluid">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h4 class="panel-title">
                    {{ trans('mainLang.userPersonalPage') }}:
                    {{ $user->name }} ({{ $user->section->title }})
                </h4>
            </div>
            <div class="panel-body no-padding">
                <div class="all-sides-padding-16">
                    {{Form::open(['route' => 'user.updatePersonalSettings'])}}
                        <label for="privateClubName">
                            <strong>{{ trans('mainLang.privateClubName') }}</strong>
                        </label>
                        <br class="show-xs">
                        <div class="radio-inline">
                            <label>
                                {!! Form::radio( 'is_name_private','null', is_null($user->is_name_private )) !!}
                                {{ trans('mainLang.privateClubNameNull') }}
                            </label>
                        </div>
                        <br>
                        <div class="radio-inline">
                            <label>
                                {!! Form::radio( 'is_name_private','true', !is_null($user->is_name_private ) && $user->is_name_private == 1 ) !!}
                                {{ trans('mainLang.privateClubNameYes') }}
                            </label>
                        </div>
                        <br>
                        <div class="radio-inline">
                            <label>
                                {!! Form::radio( 'is_name_private','false', !is_null($user->is_name_private ) && $user->is_name_private == 0) !!}
                                {{ trans('mainLang.privateClubNameNo') }}
                            </label>
                        </div>
                        <br>
                        <br>
                        <div class="btn-group">
                            <button type="reset"
                                    class="btn btn-default">{{ trans('mainLang.reset') }}</button>
                            <button type="submit"
                                    class="btn btn-success">{{ trans('mainLang.update') }}</button>
                        </div>
                    {{Form::close()}}
                </div>

                <hr class="no-padding no-margin">

                <div class="all-sides-padding-16">
                    @auth
                        @noLdapUser
                            <a href="{{route('password.change')}}">
                                <i class="fa fa-key fa-rotate-90" aria-hidden="true"></i>
                                {{ trans('auth.changePassword') }}
                            </a>
                        @endnoLdapUser
                    @endauth
                </div>

                <hr class="no-padding no-margin">

                <h5 class="left-padding-16">
                    {{trans('mainLang.upcomingShifts')}}:
                </h5>

                <table class="table table-hover col-md-12">
                    <caption class="text-center"></caption>
                    <thead>
                        <tr>
                            <th class="text-center col-md-3">{{trans('mainLang.shift')}}</th>
                            <th class="text-center col-md-3">{{trans('mainLang.event')}}</th>
                            <th class="text-center col-md-3">{{trans('mainLang.date')}}</th>
                            <th class="text-center col-md-3">{{trans('mainLang.begin')}}-{{trans('mainLang.end')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($shifts as $shift )
                            <tr>
                                <td class="text-center">
                                    {{ $shift->type->title }}
                                </td>
                                <td class="text-center">
                                    <a href="/event/{{$shift->schedule->event->id}}">{{ $shift->schedule->event->evnt_title }}</a>
                                </td>
                                <td class="text-center">
                                    {{ (new DateTime($shift->schedule->event->evnt_date_start))->format('D, d.m.Y') }}
                                </td>
                                <td class="text-center">
                                    {{ (new DateTime($shift->start))->format('H:i') }}-{{ (new DateTime($shift->end))->format('H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
