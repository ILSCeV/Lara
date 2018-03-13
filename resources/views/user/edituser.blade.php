@extends('layouts.master')
@section('title')
    {{ "User Overview" }}
@stop

@section('content')
    <div class="container-fluid">
        <div class="col-md-8 col-md-offset-2">
            {{ Form::open(['class'=>'form-inline ','route'=>['user.updateData',$user]])  }}
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('mainLang.editUser') }}
                </div>
                <div class="panel-body">
                    @canEditUser($user)
                        <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="control-label" for="userName"> Name </label>
                            {{ Form::text('name',$user->name,['class'=>"form-control" ,'id'=>'userName','required'=>"",'autofocus'=>'']) }}
                            @if ($errors->has('name'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="control-label" for="email"> Email </label>
                            {{ Form::email('email',$user->email,['class'=>"form-control" ,'id'=>'email']) }}
                            @if ($errors->has('email'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group{{ $errors->has('section') ? ' has-error' : '' }}">
                            <label for="section" class=" control-label">{{trans('mainLang.section')}}</label>
                            <select name="section" id="section" class="editUserFormselectpicker">
                                @foreach(Lara\Section::all() as $section)
                                    <option value="{{$section->id}}" {{ Gate::denies('createUserOfSection', $section->id) ? "disabled" : "" }}>{{$section->title}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('section'))
                                <span class="help-block">
                                   <strong>{{ $errors->first('section') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                            <label for="status" class="control-label">Status</label>
                                <select name="status" id="status" class="editUserFormselectpicker">
                                    @foreach(['candidate', 'member', 'veteran'] as $status)
                                        <option value="{{$status}}" >{{trans(Auth::user()->section->title . "." . $status) }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('status'))
                                    <span class="help-block">
                                         <strong>{{ $errors->first('status') }}</strong>
                                    </span>
                                @endif
                        </div>
                    @else
                        <div class="form-group">
                            <label class="control-label" for="userName"> Name </label>
                            <div id="username" class="form-control"> {{ $user->name }} </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group">
                            <label class="control-label" for="email"> Email </label>
                            <div id="email" class="form-control"> {{ $user->email }} </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group">
                            <label class="control-label" for="section"> {{ trans('mainLang.section') }} </label>
                            <div id="section" class="form-control"> {{ $user->section->title }} </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group">
                            <label class="control-label" for="status"> {{ trans('mainLang.status') }} </label>
                            <div id="status" class="form-control"> {{ trans(Auth::user()->section->title . "." . $user->status) }} </div>
                        </div>
                    @endcanEditUser
                        @foreach($permissionsPersection as $sectionId=>$roles)
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    {{ \Lara\Section::findOrFail($sectionId)->title }}
                                </div>
                                <div class="panel-body">
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
                                                            <div data-value="{{ $role->id }}">
                                                                {{ $role->name }}
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if(Auth::user()->is(Roles::PRIVILEGE_ADMINISTRATOR) || Auth::user()->hasPermission($role))
                                                        <button type="button" class="btn btn-sm btn-primary addRoleBtn" data-src="src-{{$role->id}}" data-target="target-{{$role->id}}"> > </button>
                                                        <br/>
                                                        <button type="button" class="btn btn-sm btn-primary removeRoleBtn" data-target="src-{{$role->id}}" data-src="target-{{$role->id}}"> < </button>
                                                        @endif
                                                    </td>
                                                    <td class="text-center assignedRoles" id="target-{{$role->id}}">
                                                        @if($user->hasPermission($role))
                                                            <div data-value="{{ $role->id }}">
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
                            </div>
                        @endforeach
                        <div class="clearfix"></div>
                        <div class="form-group">
                            <button type="submit" id="updateUserData" class="btn btn-success"> {{ trans('mainLang.update') }} </button>
                        </div>
                        <div class="form-group">
                            <a href="javascript:history.back()" class="btn btn-default">{{ trans('mainLang.backWithoutChange') }}</a>
                        </div>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
@stop
