@extends('layouts.master')
@section('title')
    {{ trans('auth.changePassword') }}
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ trans('auth.changePassword') }}</div>

                    <div class="panel-body">
                        {{ Form::open(['class'=>"form-horizontal","method"=>"POST","route"=>'password.change.post']) }}

                        <div class="form-group{{ $errors->has('old-password') ? ' has-error' : '' }}">
                            <label for="old-password" class="col-md-4 control-label">{{ trans('auth.oldPassword') }}</label>

                            <div class="col-md-6">
                                <input id="old-password" type="password" class="form-control" name="old-password" required>

                                @if ($errors->has('old-password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('old-password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label"> {{ trans('auth.newPassword') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label"> {{ trans('auth.confirmNewPassword') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

                                @if ($errors->has('new-password-confirm'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('new-password-confirm') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ trans('auth.changePassword') }}
                                </button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
