@extends('layouts.master')
@php
    /**
    *   @var \Lara\User $user
    *   @var \Illuminate\Support\Collection|\Lara\Shift $shifts
    *   @var String $secret
    *   @var String $qrImage
    *   @var \LaravelWebauthn\Models\WebauthnKey $webautnKey
    */
@endphp
@section('title')
    {{ trans('mainLang.userPersonalPage') }} - {{ $user->name }} ({{ $user->section->title }})
@endsection
@section('moreScripts')
    <script>
        var publicKey = {!! json_encode($publicKey) !!};
    </script>
    <script src="{{asset(WebpackBuiltFiles::$assets['webauthn.js'])}}"></script>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card ">
            <div class="card-header text-white bg-info">
                <h4 class="card-title ">
                    {{ trans('mainLang.userPersonalPage') }}:
                    {{ $user->name }} ({{ $user->section->title }})
                </h4>
            </div>
            <div class="card-body p-0">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a href="#shifts" class="nav-link active"
                           data-toggle="tab">{{trans('mainLang.upcomingShifts')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#settings" data-toggle="tab">{{trans('mainLang.settings')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#secondFa" data-toggle="tab"> {{trans('mainLang.2fa')}} </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#webauth" data-toggle="tab"> {{trans('mainLang.2fa')}} </a>
                    </li>
                </ul>
                <div id="tabContent" class="tab-content">
                    <div class="tab-pane fade active in show" id="shifts">
                        <table class="table table-hover col-md-12">
                            <caption class="text-center"></caption>
                            <thead>
                            <tr>
                                <th class="text-center col-md-3">{{trans('mainLang.shift')}}</th>
                                <th class="text-center col-md-3">{{trans('mainLang.event')}}</th>
                                <th class="text-center col-md-3">{{trans('mainLang.date')}}</th>
                                <th class="text-center col-md-3">{{trans('mainLang.begin')}}
                                    -{{trans('mainLang.end')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($shifts as $shift )
                                <tr>
                                    <td class="text-center">
                                        {{ $shift->type->title }}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('event.show',$shift->schedule->event->id) }}">{{ $shift->schedule->event->evnt_title }}</a>
                                    </td>
                                    <td class="text-center">
                                        {{ (new DateTime($shift->schedule->event->evnt_date_start))->format('D, d.m.Y') }}
                                    </td>
                                    <td class="text-center">
                                        {{ (new DateTime($shift->start))->format('H:i') }}
                                        - {{ (new DateTime($shift->end))->format('H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="tab-pane fade" id="settings">
                        <div class="p-3">
                            {{Form::open(['route' => 'user.updatePersonalSettings'])}}
                            <label for="privateClubName">
                                <strong>{{ trans('mainLang.privateClubName') }}</strong>
                            </label>
                            <br class="d-block d-md-none d-lg-none">
                            <div class="form-check-inline">
                                <label class="form-check">
                                    {!! Form::radio( 'is_name_private','null', is_null($user->is_name_private )) !!}
                                    {{ trans('mainLang.privateClubNameNull') }}
                                </label>
                            </div>
                            <br class="d-block d-md-none d-lg-none">
                            <div class="form-check-inline">
                                <label class="form-check">
                                    {!! Form::radio( 'is_name_private','true', !is_null($user->is_name_private ) && $user->is_name_private == 1 ) !!}
                                    {{ trans('mainLang.privateClubNameYes') }}
                                </label>
                            </div>
                            <br class="d-block d-md-none d-lg-none">
                            <div class="form-check-inline">
                                <label class="form-check">
                                    {!! Form::radio( 'is_name_private','false', !is_null($user->is_name_private ) && $user->is_name_private == 0) !!}
                                    {{ trans('mainLang.privateClubNameNo') }}
                                </label>
                            </div>
                            <br>
                            <br>
                            <div class="btn-group btn-group-sm">
                                <button type="reset"
                                        class="btn btn-sm btn-secondary">{{ trans('mainLang.reset') }}</button>
                                <button type="submit"
                                        class="btn btn-sm btn-success">{{ trans('mainLang.update') }}</button>
                            </div>
                            {{Form::close()}}
                        </div>

                        <hr class="p-0 m-0">

                        <div class="p-3">
                            @auth
                                @noLdapUser
                                <a href="{{route('password.change')}}">
                                    <i class="fa fa-key fa-rotate-90" aria-hidden="true"></i>
                                    <strong>{{ trans('auth.changePassword') }}</strong>
                                </a>
                                @endnoLdapUser
                            @endauth
                        </div>
                    </div>
                    <div class="tab-pane fade" id="secondFa">
                        @if(empty($user->google2fa_secret))
                            {{ Form::open(['method' => 'POST', 'route' => ['user.registerGoogleAuth'], 'class' => 'form-inline      ']) }}
                            <div class="p-3 ">
                                <p>{{trans('mainLang.2fa.setup')}} {{ $secret }}</p>
                                <img src="{{ $qrImage }}">
                                <p> {{trans('mainLang.2fa.verifyWorking')}} </p>
                                <div class="form-group ">
                                    <label for="currentCode">Code</label>
                                    <div class="p-2">
                                        {{ Form::number('currentCode','', ['class'=>'form-control','id'=>'currentCode']) }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="btn-group btn-group-sm">
                                        <button type="reset"
                                                class="btn btn-sm btn-secondary">{{ trans('mainLang.reset') }}</button>
                                        <button type="submit"
                                                class="btn btn-sm btn-success">{{ trans('mainLang.update') }}</button>
                                    </div>
                                </div>
                                {{ Form::text("secret",$secret,['class'=>'hide']) }}
                            </div>
                            {{ Form::close() }}
                        @else
                            {{ Form::open(['method' => 'POST', 'route' => ['user.unregisterGoogleAuth'], 'class' => 'form-inline      ']) }}
                            <div class="p-3 ">
                                <p>{{trans('mainLang.2fa.unregister')}}</p>
                                <div class="form-group">
                                    <div class="btn-group btn-group-sm">
                                        <button type="submit"
                                                class="btn btn-sm btn-danger"><i
                                                class="fas fa-trash-alt"></i> {{ trans('mainLang.delete') }}</button>
                                    </div>
                                </div>
                                {{ Form::text("secret",$secret,['class'=>'hide']) }}
                            </div>
                            {{ Form::close() }}
                        @endif
                    </div>
                    <div class="tab-pane fade" id="webauth">
                        <div class="p-3">
                            <!-- form to send datas to -->
                            <form class="hide" method="POST" action="{{ route('user.registerWebauthnKey') }}"
                                  id="webauth-form">
                                @csrf
                                <input type="hidden" name="register" id="webauth-register"/>
                                <input type="hidden" name="name" id="webauth-name"/>
                            </form>
                            <form class="form-inline" id="webauth-submit">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="webauth-input-name">Schlüsselname: </label>
                                        <input type="text" name="webauth-input-name" id="webauth-input-name">
                                    </div>
                                    <div class="input-group-addon">
                                        <button type="submit" class="btn btn-success btn-small">
                                            <i class="far fa-plus-square"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <div class="table table-striped">
                                <table>
                                    <thead>
                                    <th>{{ trans('mainLang.name') }}</th>
                                    <th></th>
                                    </thead>
                                    <tbody>
                                    @foreach($webauthnKeys as $webautnKey )
                                        <tr>
                                            <td>{{$webautnKey->getKeyName()}}</td>
                                            <td>
                                                <button class="deleteKey btn btn-danger" data-key="{{$webautnKey->id}}">
                                                    <i class="fas fa-trash-alt"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
