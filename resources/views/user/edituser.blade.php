@extends('layouts.master')
@section('title')
    {{ trans('mainLang.editUser') }}
@stop

@section('content')
    <div class="container-fluid">
        <div class="col-md-12">
            {{ Form::open(['class'=>'form-inline ','route'=>['user.updateData',$user]])  }}
            <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading ">
                        <h4 class="panel-title">
                            {{ trans('mainLang.editUser') }}
                            <a data-toggle="collapse" href="#userInformation" class="collapse-toggle">
                            </a>
                        </h4>
                    </div>
                    <div id="userInformation" class="panel-body panel-collapse collapse in">
                        @canEditUser($user)
                        <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }} input-group" >
                            <label class="control-label" for="userName"> Name </label>
                            {{ Form::text('name',$user->name,['class'=>"form-control" ,'id'=>'userName','required'=>"",'autofocus'=>'']) }}
                            @if ($errors->has('name'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }} input-group">
                            <label class="control-label" for="email"> Email </label>
                            {{ Form::email('email',$user->email,['class'=>"form-control" ,'id'=>'email']) }}
                            @if ($errors->has('email'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('section') ? ' has-error' : '' }} input-group">
                            <label for="section" class=" control-label">{{trans('mainLang.section')}}</label>
                            <div>
                                <select name="section" id="section" class="editUserFormselectpicker">
                                    @foreach(Lara\Section::all() as $section)
                                        <option value="{{$section->id}}" {{ Gate::denies('createUserOfSection', $section->id) ? "disabled" : "" }} {{$section->id === $user->section->id ? "selected" : ""}} >
                                            {{$section->title}}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('section'))
                                    <span class="help-block">
                                   <strong>{{ $errors->first('section') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('status') ? ' has-error' : '' }} input-group">
                            <label for="status" class="control-label">Status</label>
                            <div>
                                <select name="status" id="status" class="editUserFormselectpicker">
                                    @foreach(Lara\Status::ALL as $status)
                                        <option value="{{$status}}" {{$status === $user->status ? "selected" : ""}}>
                                            {{trans(Auth::user()->section->title . "." . $status) }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('status'))
                                    <span class="help-block">
                                         <strong>{{ $errors->first('status') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        @else
                            <div class="form-group input-group" >
                                <label class="control-label" for="userName"> Name </label>
                                {{ Form::text('name',$user->name,['class'=>"form-control" ,'id'=>'userName','required'=>"",'autofocus'=>'', 'disabled']) }}
                            </div>
                            <div class="form-group input-group">
                                <label class="control-label" for="email"> Email </label>
                                {{ Form::email('email',$user->email,['class'=>"form-control" ,'id'=>'email', 'disabled']) }}
                            </div>
                            <div class="form-group input-group">
                                <label for="section" class=" control-label">{{trans('mainLang.section')}}</label>
                                {{ Form::text('section',$user->section->title,['class'=>"form-control" ,'id'=>'section', 'disabled']) }}
                            </div>
                            <div class="form-group input-group">
                                <label for="status" class="control-label">Status</label>
                                {{ Form::text('section',trans(Auth::user()->section->title . "." . $user->status) ,['class'=>"form-control" ,'id'=>'status', 'disabled']) }}
                            </div>
                            @endcanEditUser
                    </div>
                </div>
            <div class="panel panel-default">
                <div class="panel panel-heading">
                    <h4 class="panel-title">
                        {{ trans('mainLang.roleManagement') }}
                        <a data-toggle="collapse" href="#roleInformation" class="collapse-toggle">
                        </a>
                    </h4>
                </div>
                <div id="roleInformation" class="panel-body panel-collapse collapse in">
                    @if (count($permissionsPersection) > 0)
                        <div class="panel panel-default">
                            <ul class="nav nav-tabs">
                                @foreach($permissionsPersection as $sectionId => $roles)
                                    <li class="{{Auth::user()->section_id == $sectionId ? 'active': ''}} permissionsPicker">
                                        <a aria-expanded="{{Auth::user()->section_id == $sectionId? 'active': ''}}"
                                           href="#{{$sectionId}}Permissions"
                                           data-toggle="tab">
                                            {{Lara\Section::find($sectionId)->title}}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="tab-content">

                            @foreach($permissionsPersection as $sectionId => $roles)
                                <div class="tab-pane fade in {{ Auth::user()->section_id == $sectionId? 'active': '' }}" id="{{$sectionId}}Permissions">
                                    <table class="table table-striped permissiontable table-bordered" data-section="{{$sectionId}}">
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
                                                        <div class="text-capitalize" data-value="{{ $role->id }}">
                                                            {{ $role->name }}
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if(Auth::user()->is(Roles::PRIVILEGE_ADMINISTRATOR) || Auth::user()->hasPermission($role))
                                                        <button type="button"
                                                                class="btn btn-sm btn-primary toggleRoleBtn"
                                                                data-target="{{$user->hasPermission($role) ? 'src' : 'target'}}-{{$role->id}}"
                                                                data-src="{{$user->hasPermission($role) ? 'target' : 'src'}}-{{$role->id}}">
                                                            {{$user->hasPermission($role) ? '<' : '>' }}
                                                        </button>
                                                    @endif
                                                </td>
                                                <td class="text-center assignedRoles" id="target-{{$role->id}}">
                                                    @if($user->hasPermission($role))
                                                        <div class="text-capitalize" data-value="{{ $role->id }}">
                                                            {{ $role->name }}
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <input class="hidden" name="role-assigned-section-{{$sectionId}}" value="">
                                    <input class="hidden" name="role-unassigned-section-{{$sectionId}}" value="">
                                </div>
                            @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="btn-group btn-group-lg centered">
            <button type="submit" id="updateUserData" class="btn btn-success"> {{ trans('mainLang.update') }} </button>
            <a class="btn btn-default" href="javascript:history.back()">{{ trans('mainLang.backWithoutChange') }}</a>

        </div>
        {{ Form::close() }}
        </div>
    </div>
@stop
