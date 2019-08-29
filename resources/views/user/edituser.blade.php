@extends('layouts.master')
@section('title')
    {{ trans('mainLang.editUser') }}
@stop
@php
$labelClass = 'col-md-2 col-sm-auto';
@endphp

@section('content')
    <div class="container-fluid">

            {{ Form::open(['class'=>'form-inline ','route'=>['user.updateData',$user]])  }}
            <div class="col-md-7 col-auto">
                <div class="card bg-secondary ">
                    <div class="card-header ">
                        <h4 class="card-title">
                            {{ trans('mainLang.editUser') }}
                            <a data-toggle="collapse" href="#userInformation" class="text-dark-grey float-right">
                                <i class="fas fa-chevron-down"></i>
                            </a>
                        </h4>
                    </div>
                    <div id="userInformation" class="card-body show collapse in">

                        @ldapSection($user->section)
                        <div class="form-group input-group">
                            <label class="col-form-label {{$labelClass}}" for="clubNumber"> {{ trans('mainLang.clubNumber') }} </label>
                            {{ Form::text('clubnumber', $user->person->prsn_ldap_id,['class'=>"form-control" ,'id'=>'clubNumber','required'=>"",'autofocus'=>'','disabled'=>''])  }}
                        </div>
                        @endldapSection

                        @canEditUser($user)
                        <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }} ">
                            <label class="col-form-label {{$labelClass}}" for="userName"> Name </label>
                            {{ Form::text('name',$user->name,['class'=>"form-control" ,'id'=>'userName','required'=>"",'autofocus'=>'']) }}
                            @if ($errors->has('name'))
                                <span class="form-text">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('givenname') ? ' has-error' : '' }} ">
                            <label class="col-form-label {{$labelClass}}" for="givenname"> {{ trans('auth.givenname') }} </label>
                            {{ Form::text('givenname',$user->givenname,['class'=>"form-control" ,'id'=>'givenname','required'=>"",'autofocus'=>'']) }}
                            @if ($errors->has('givenname'))
                                <span class="form-text">
                                        <strong>{{ $errors->first('givenname') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('lastname') ? ' has-error' : '' }} ">
                            <label class="col-form-label {{$labelClass}}" for="lastname"> {{ trans('auth.lastname') }} </label>
                            {{ Form::text('lastname',$user->lastname,['class'=>"form-control" ,'id'=>'lastname','required'=>"",'autofocus'=>'']) }}
                            @if ($errors->has('lastname'))
                                <span class="form-text">
                                        <strong>{{ $errors->first('lastname') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }} ">
                            <label class="col-form-label {{$labelClass}}" for="email"> Email </label>
                            {{ Form::email('email',$user->email,['class'=>"form-control" ,'id'=>'email']) }}
                            @if ($errors->has('email'))
                                <span class="form-text">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('on_leave') ? ' has-error' : '' }} ">
                            <label class="col-form-label {{$labelClass}}" for="on_leave"> {{ trans('auth.on_leave_until') }} </label>
                            {{ Form::date('on_leave',$user->on_leave,['class'=>"form-control" ,'id'=>'on_leave']) }}
                            @if ($errors->has('on_leave'))
                                <span class="form-text">
                                        <strong>{{ $errors->first('on_leave') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('section') ? ' has-error' : '' }} ">
                            <label for="section" class=" col-form-label {{$labelClass}}">{{trans('mainLang.section')}}</label>
                            <div>
                                <select name="section" id="section" class="editUserFormselectpicker">
                                    @foreach(Lara\Section::all()->sortBy('title') as $section)
                                        <option
                                            value="{{$section->id}}" {{ Gate::denies('createUserOfSection', $section->id) ? "disabled" : "" }} {{$section->id === $user->section->id ? "selected" : ""}} >
                                            {{$section->title}}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('section'))
                                    <span class="form-text">
                                   <strong>{{ $errors->first('section') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('status') ? ' has-error' : '' }} ">
                            <label for="status" class="col-form-label {{$labelClass}}">Status</label>
                            <div>
                                <select name="status" id="status" class="editUserFormselectpicker">
                                    @foreach(Lara\Status::ALL as $status)
                                        <option value="{{$status}}" {{$status === $user->status ? "selected" : ""}}>
                                            {{trans(Auth::user()->section->title . "." . $status) }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('status'))
                                    <span class="form-text">
                                         <strong>{{ $errors->first('status') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        @else
                            <div class="form-group ">
                                <label class="col-form-label {{$labelClass}}" for="userName"> Name </label>
                                {{ Form::text('name',$user->name,['class'=>"form-control" ,'id'=>'userName','required'=>"",'autofocus'=>'', 'disabled']) }}
                            </div>
                            <div class="form-group">
                                <label class="col-form-label {{$labelClass}}" for="email"> Email </label>
                                {{ Form::email('email',$user->email,['class'=>"form-control" ,'id'=>'email', 'disabled']) }}
                            </div>
                            <div class="form-group ">
                                <label for="section" class=" col-form-label {{$labelClass}}">{{trans('mainLang.section')}}</label>
                                {{ Form::text('section',$user->section->title,['class'=>"form-control" ,'id'=>'section', 'disabled']) }}
                            </div>
                            <div class="form-group ">
                                <label for="status" class="col-form-label {{$labelClass}}">Status</label>
                                {{ Form::text('section',trans(Auth::user()->section->title . "." . $user->status) ,['class'=>"form-control" ,'id'=>'status', 'disabled']) }}
                            </div>
                            @endcanEditUser
                    </div>
                </div>
            </div>
            <div class="w-100"></div>
            <div class="col-md-7 col-auto">
                <div class="card bg-secondary">
                    <div class="card-header">
                        <h4 class="card-title">
                            {{ trans('mainLang.roleManagement') }}
                            <a data-toggle="collapse" href="#roleInformation"
                               class="float-right text-dark-grey collapse-toggle">
                                <i class="fas fa-chevron-down"></i>
                            </a>
                        </h4>
                    </div>
                    <div id="roleInformation" class="card-body show collapse in">
                        @if (count($permissionsPersection) > 0)
                            <div class="card bg-secondary">
                                <nav class="nav nav-tabs" role="tablist">
                                    @foreach($permissionsPersection as $sectionId => $roles)
                                            <a class="permissionsPicker nav-item nav-link {{Auth::user()->section_id == $sectionId ? 'active': ''}}" aria-expanded="{{Auth::user()->section_id == $sectionId? 'active': ''}}"
                                               href="#Permissions{{$sectionId}}"
                                               data-toggle="tab" id="{{$sectionId}}Nav" role="tab"
                                               aria-expanded="{{Auth::user()->section_id == $sectionId}}"
                                            >
                                                {{Lara\Section::find($sectionId)->title}}
                                            </a>
                                    @endforeach
                                </nav>
                                <div class="tab-content">

                                    @foreach($permissionsPersection as $sectionId => $roles)
                                        <div
                                            class="tab-pane fade {{ Auth::user()->section_id == $sectionId? 'show active': '' }}"
                                            id="Permissions{{$sectionId}}" role="tabpanel" aria-labelledby="{{$sectionId}}Nav">
                                            <table class="table table-striped permissiontable table-bordered"
                                                   data-section="{{$sectionId}}">
                                                <thead>
                                                <tr>
                                                    <th class="text-center">
                                                        {{ trans('mainLang.availableRoles') }}
                                                    </th>
                                                    <th class="text-center">

                                                    </th>
                                                    <th class="text-center">
                                                        {{ trans('mainLang.activeRoles') }}
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($roles as $role)
                                                    <tr>
                                                        <td class="text-center unassignedRoles" id="src-{{$role->id}}">
                                                            @if(!$user->hasPermission($role))
                                                                <div class="text-capitalize"
                                                                     data-value="{{ $role->id }}">
                                                                    {{ $role->name }}
                                                                </div>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            @hasRole($role)
                                                            <button type="button"
                                                                    class="btn btn-sm btn-primary toggleRoleBtn"
                                                                    data-target="{{$user->hasPermission($role) ? 'src' : 'target'}}-{{$role->id}}"
                                                                    data-src="{{$user->hasPermission($role) ? 'target' : 'src'}}-{{$role->id}}">
                                                                {{$user->hasPermission($role) ? '<' : '>' }}
                                                            </button>
                                                            @endhasRole
                                                        </td>
                                                        <td class="text-center assignedRoles" id="target-{{$role->id}}">
                                                            @if($user->hasPermission($role))
                                                                <div class="text-capitalize"
                                                                     data-value="{{ $role->id }}">
                                                                    {{ $role->name }}
                                                                </div>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                            <input class="d-none" name="role-assigned-section-{{$sectionId}}" value="">
                                            <input class="d-none" name="role-unassigned-section-{{$sectionId}}"
                                                   value="">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="w-100"></div>
            <div class="col-md-7 col-sm-auto">
                <div class="btn-group btn-group-lg text-center">
                    <button type="submit" id="updateUserData"
                            class="btn btn-success"> {{ trans('mainLang.update') }} </button>
                    <a class="btn btn-secondary"
                       href="javascript:history.back()">{{ trans('mainLang.backWithoutChange') }}</a>

                </div>
            </div>
            {{ Form::close() }}
        </div>

@stop
