@extends('layouts.master')
@section('title')
    {{ __('auth.register') }}
@stop

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-2">
            <div class="card panel-default">
                <div class="card-header">{{ __('auth.register') }}</div>

                <div class="card-body">
                    {{ Form::open(['class'=>"form-horizontal","method"=>"POST","route"=>'register']) }}
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 col-form-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="form-text">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('givenname') ? ' has-error' : '' }}">
                            <label for="givenname" class="col-md-4 col-form-label">{{ __('auth.givenname') }}</label>

                            <div class="col-md-6">
                                <input id="givenname" type="text" class="form-control" name="givenname" value="{{ old('givenname') }}" required autofocus>

                                @if ($errors->has('givenname'))
                                    <span class="form-text">
                                            <strong>{{ $errors->first('givenname') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
                            <label for="lastname" class="col-md-4 col-form-label">{{ __('auth.lastname') }}</label>

                            <div class="col-md-6">
                                <input id="lastname" type="text" class="form-control" name="lastname" value="{{ old('lastname') }}" required autofocus>

                                @if ($errors->has('lastname'))
                                    <span class="form-text">
                                            <strong>{{ $errors->first('lastname') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 col-form-label">{{ __('auth.email') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="form-text">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('section') ? ' has-error' : '' }}">
                            <label for="section" class="col-md-4 col-form-label"> {{ __('auth.section') }}</label>

                            <div class="col-md-6">
                                <select name="section" id="section" class="form-select selectpicker">
                                    @foreach(Lara\Section::all() as $section)
                                        <option value="{{$section->id}}" {{ Gate::denies('createUserOfSection', $section->id) ? "disabled" : "" }}>{{$section->title}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('section'))
                                    <span class="form-text">
                                        <strong>{{ $errors->first('section') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                            <label for="status" class="col-md-4 col-form-label">{{ __('auth.status') }}</label>

                            <div class="col-md-6">
                                <select name="status" id="status" class="form-select">
                                    @foreach(Lara\Status::ACTIVE as $status)
                                        <option value="{{$status}}" >{{__(Auth::user()->section->title . "." . $status) }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('status'))
                                    <span class="form-text">
                                        <strong>{{ $errors->first('status') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 col-form-label">{{ __('auth.password') }}</label>

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
                            <label for="password-confirm" class="col-md-4 col-form-label">{{ __('auth.confirmPassword') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 offset-2">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('auth.register_submit') }}
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
