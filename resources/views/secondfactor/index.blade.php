@extends('layouts.master')

<div class="container">
    <div class="row">
        <div class="col-md-8 offset-2">
            <div class="card panel-default">
                <div class="card-header">{{__('mainLang.2fa')}}</div>

                <div class="card-body">
                    {{ Form::open(['class'=>"form-horizontal","method"=>"POST","route"=>'verify.2fa']) }}
                    <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                        <label for="code">code</label>
                        <div class="col-md-6">
                            {{Form::number('code', null, ['class'=>'form-control', 'id'=>'code'])}}
                            @if ($errors->has('code'))
                                <span class="form-text">
                                        <strong>{{ $errors->first('code') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <button type="submit"
                                    class="btn btn-small btn-success"> {{__('mainLang.submit') }}
                            </button>
                        </div>
                    </div>
                    {{ Form::close()  }}
                </div>
            </div>
        </div>
    </div>
</div>
