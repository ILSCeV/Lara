@extends('layouts.master')

<div class="container">
    <div class="row">
        <div class="col-md-8 offset-2">
            <div class="card panel-default">
                <div class="card-header">Zweifaktorauthentifizierung</div>

                <div class="card-body">
                    {{ Form::open(['class'=>"form-horizontal","method"=>"POST","route"=>'verify.2fa']) }}
                        <div class="form-group">
                            <label for="code">code</label>
                            <div class="col-md-6">
                                {{Form::number('code', null, ['class'=>'form-control', 'id'=>'code'])}}
                            </div>
                        </div>
                    {{ Form::close()  }}
                </div>
            </div>
        </div>
    </div>
</div>
