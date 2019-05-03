
@extends('layouts.master')
@section('title')
    {{ "User Overview" }}
@stop

@section('content')
    <div class="card card.text-white.bg-info col-12 p-0">
        <div class="card-header">
            <h4 class="card-title">{{ trans('mainLang.management') }}: {{ trans('users.users') }}</h4>
        </div>

        <div class="card card-body p-0">
            <table class="table info table-hover table-sm">
                <thead>
                <tr class="active">
                    <th class=" pl-3">
                        {{ trans('auth.section') }}
                    </th>
                    <th class="">
                        {{ trans('auth.nickname') }}
                    </th>
                    <th class="">
                        {{ trans('auth.givenname') }}
                    </th>
                    <th class="">
                        {{ trans('auth.lastname') }}
                    </th>
                    <th class="">
                        {{ trans('auth.email') }}
                    </th>
                    <th class="">
                        {{ trans('auth.status') }}
                    </th>
                </tr>
                </thead>
                <tbody class="container" id="userOverviewTable">
                <div class="px-3 py-2">
                    <span class="pb-2 fa-pull-left form-inline has-feedback">
                        <label for="userOverviewFilter" class="test"> {{ trans('mainLang.search') }}: </label>
                        <input type="text" class="form-control" id="userOverviewFilter">
                    </span>
                @noLdapUser
                    <span class="table-control__create-new-user fa-pull-right">
                            <a class="btn btn-success" href="{{route('register')}}">
                                <i class="fa fa-user-plus"></i>
                            </a>
                     </span>
                @endnoLdapUser
                </div>

                @foreach($users as $user)
                    <tr>
                        <td class="pl-3">
                           {{ $user->section->title }}
                        </td>
                        <td>
                            <a href="{{route("user.edit",["id"=>$user->id])}}">
                                {{ $user->name }}
                            </a>
                        </td>
                        <td>
                            {{ $user->givenname }}
                        </td>
                        <td>
                            {{ $user->lastname }}
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
