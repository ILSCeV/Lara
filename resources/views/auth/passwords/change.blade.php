@extends('layouts.master')
@section('title')
    {{ trans('auth.changePassword') }}
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-2">
                <div class="card panel-default">
                    <div class="card-header">{{ trans('auth.changePassword') }}</div>

                    <div class="card-body">
                        {{ Form::open(['class'=>"form-horizontal","method"=>"POST","route"=>'password.change.post']) }}

                        <div class="form-group{{ $errors->has('old-password') ? ' has-error' : '' }}">
                            <label for="old-password" class="col-md-4 col-form-label">{{ trans('auth.oldPassword') }}</label>

                            <div class="col-md-6">
                                <input id="old-password" type="password" class="form-control" name="old-password" required>

                                @if ($errors->has('old-password'))
                                    <span class="form-text">
                                        <strong>{{ $errors->first('old-password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 col-form-label"> {{ trans('auth.newPassword') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="form-text">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 col-form-label"> {{ trans('auth.confirmNewPassword') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

                                @if ($errors->has('new-password-confirm'))
                                    <span class="form-text">
                                        <strong>{{ $errors->first('new-password-confirm') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 offset-2">
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
