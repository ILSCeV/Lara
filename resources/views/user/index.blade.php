
@extends('layouts.master')
@section('title')
    {{ "User Overview" }}
@stop

@section('content')
    <div class="panel panel-info col-xs-12 no-padding">
        <div class="panel-heading">
            <h4 class="panel-title">{{ trans('mainLang.management') }}: {{ trans('users.users') }}</h4>
        </div>

        <div class="panel panel-body no-padding">
            <table class="table info table-hover table-condensed">
                <thead>
                <tr class="active">
                    <th class="col-md-1 col-xs-1 padding-left-15">
                        {{ trans('mainLang.section') }}
                    </th>
                    <th class="col-md-3 col-xs-3">
                        Name
                    </th>
                    <th class="col-md-6 col-xs-6">
                        E-Mail
                    </th>
                    <th class="col-md-1 col-xs-1">
                        Status
                    </th>
                </tr>
                </thead>
                <tbody class="container" id="userOverviewTable">
                <div class="table-control">
                    <span class="table-control__search fa-pull-left form-inline has-feedback">
                        <label for="userOverviewFilter" class="test"> {{ trans('mainLang.search') }}: </label>
                        <input type="text" class="form-control" id="userOverviewFilter">
                    </span>
                    <span class="table-control__create-new-user fa-pull-right">
                            <a class="btn btn-success" href="{{route('register')}}">
                                <i class="fa fa-user-plus"></i>
                            </a>
                        </span>
                </div>

                @foreach($users as $user)
                    <tr>
                        <td class="padding-left-15">
                           {{ $user->section->title }}
                        </td>
                        <td>
                            <a href="{{route("user.edit",["id"=>$user->id])}}">
                                {{ $user->name }}
                            </a>
                        </td>
                        <td>
                            {{ $user->email }}
                        </td>
                        <td>
                            @canEditUser($user)
                                {{ Form::model($user, ['route' => ['user.update', $user->id], 'id' => 'change-user-status-' . $user->id, 'class'=>'change-user-status-form', 'method' => 'PUT']) }}
                                <select name="status" class="selectpicker" data-id="{{$user->id}}" data-name="{{$user->name}}">
                                    @foreach(Lara\Status::ALL as $status)
                                        <option value="{{ $status }}" {{ $status === $user->status ? "selected" : "" }}>
                                            {{ trans($user->section->title . "." . $status) }}
                                        </option>
                                    @endforeach
                                </select>
                                {{ Form::close() }}
                            @endcanEditUser
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <br/>
@stop
