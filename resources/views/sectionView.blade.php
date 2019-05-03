{{-- Needs variable: $current_section --}}
@extends('layouts.master')

@section('title')
    {{ trans('mainLang.management') }}: #{{ $current_section->id }} - {!! $current_section->title !!}
@stop

@section('content')
    @php
        /** @var \Lara\Section $current_section */
    $labelClass='col-6 w-25';
    $inputClass = 'col-6';
    @endphp
    <div class="col-md-8 col-sm-auto">
        <div class="card ">
            <div class="card-header bg-info text-white">
                <h4 class="card-title">#{{ $current_section->id }}: "{!! $current_section->title !!}" </h4>
            </div>

            <div class="card-body ">

                @if(!isset($current_section->id))
                    {!! Form::open(  [ 'route' => ['section.store', ],
                                            'method' => 'POST',
                                            'class' => 'section ']  ) !!}
                @else
                    {!! Form::open(  [ 'route' => ['section.update', $current_section->id],
                                            'id' => $current_section->id,
                                            'method' => 'PUT',
                                            'class' => 'section ']  ) !!}
                @endif
                {{-- Title --}}

                <div class="form-group row">
                    <label class="col-form-label {{$labelClass}}" for="title"> <i>{{ trans('mainLang.section') }}:</i></label>
                    <div class="{{$inputClass}}">
                        {!!Form::hidden('id',
                           $current_section->id,
                           array('id'=>'id')) !!}

                        {!! Form::text('title',
                           $current_section->title,
                           array('id'=>'title', 'class'=>'form-control')) !!}
                    </div>
                </div>
                <div class="w-100"></div>

                {{-- Color --}}
                <div class="form-group row">
                    <label class="col-form-label {{$labelClass}}" for="color">
                          <i>{{ trans('mainLang.color') }}:</i>
                    </label>
                    <div class="{{$inputClass}}">
                        <select
                            id="color" class="selectpicker {{ $errors->has('color') ? ' has-error' : '' }}"
                            @if(isset($current_section->id)) form="{{$current_section->id}}" @endif name="color">
                            @foreach(config('color.colors') as $color)
                                <option
                                    data-content="<span class='palette-{{$color}}-500-Primary bg' >{{$color}}</span>"
                                    @if($color == $current_section->color)selected @endif value="{{$color}}" > {{$color}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="w-100"></div>
                {{-- Event defaults --}}
                <div class="form-group row col-form-label">
                    {{ trans('mainLang.eventDefaults')}}
                </div>
                <div class="w-100"></div>
                {{-- Event DV time --}}
                <div class="form-group row">
                        <label class="col-form-label {{$labelClass}}" for="preparationTime">
                            <i>{{ trans('mainLang.DV-Time') }}:</i>
                        </label>

                    <div class="{{$inputClass}}">
                        {!! Form::input('time', 'preparationTime', $current_section->preparationTime,['class'=>'form-control']) !!}
                    </div>
                </div>
                <div class="w-100"></div>
                {{-- Event start time --}}
                <div class="form-group row">
                    <div class="{{$labelClass}}">
                        <label class="col-form-label" for="startTime">
                            <i>{{ trans('mainLang.begin') }}:</i>
                        </label>
                    </div>
                    <div class="{{$inputClass}}">
                        {!! Form::input('time', 'startTime', $current_section->startTime,['class'=>'form-control']) !!}
                    </div>
                </div>
                <div class="w-100"></div>
                {{-- Event end time --}}
                <div class="form-group row">
                    <div class="{{$labelClass}}">
                        <label class="col-form-label" for="endTime">
                            <i>{{ trans('mainLang.end') }}:</i>
                        </label>
                    </div>
                    <div class="{{$inputClass}}">
                        {!! Form::input('time', 'endTime', $current_section->endTime, ['class'=>'form-control']) !!}
                    </div>
                </div>
                <div class="w-100"></div>
                {{-- is club name public --}}
                <div class="form-group row">
                    <div class="{{$labelClass}}">
                    <label class="col-form-label" for="private_name">
                        <i>{{ trans('mainLang.privateClubName') }}:</i>
                    </label>
                    </div>
                    <div class="{{$inputClass}}">
                    <div class="radio-inline" id="private_name">
                        <label class="form-check">
                            {!! Form::radio( 'is_name_private','true', !is_null($current_section->is_name_private ) && $current_section->is_name_private == 1) !!}
                            {{ trans('mainLang.privateClubNameYes') }}
                        </label>
                    </div>
                    <div class="radio-inline">
                        <label class="form-check">
                            {!! Form::radio( 'is_name_private','false', !is_null($current_section->is_name_private ) && $current_section->is_name_private == 0) !!}
                            {{ trans('mainLang.privateClubNameNo') }}
                        </label>
                    </div>
                    </div>
                </div>
                <div class="w-100"></div>
                {{-- CRUD --}}
                <div class="form-group row align-content-center">
                    <div class="btn-group" role="group" aria-label="submit">
                        @is('admin')
                        @if(isset($current_section->id))
                            <a href="{!! action('SectionController@destroy',$current_section->id) !!}"
                               class="btn btn-small btn-danger"
                               data-toggle="tooltip"
                               data-placement="bottom"
                               title="&#39;&#39;{!! $current_section->title !!}&#39;&#39; (#{{ $current_section->id }}) löschen"
                               data-method="delete"
                               data-token="{{csrf_token()}}"
                               rel="nofollow"
                               data-confirm="{{ trans('mainLang.deleteConfirmation') }} &#39;&#39;{!! $current_section->title !!}&#39;&#39; (#{{ $current_section->id }})? {{ trans('mainLang.warningNotReversible') }}">
                                {{ trans('mainLang.delete') }}?
                            </a>
                        @endif
                        @endis
                        <button type="reset" class="btn btn-small btn-secondary">{{ trans('mainLang.reset') }}</button>
                        @if(!isset($current_section->id))
                            <button type="submit"
                                    class="btn btn-small btn-success">{{ trans('mainLang.createSection') }}</button>
                        @else
                            <button type="submit"
                                    class="btn btn-small btn-success">{{ trans('mainLang.update') }}</button>
                        @endif
                    </div>
                </div>
                @foreach ($errors->all() as $message)
                    <div class="form-group row alert alert-danger">
                        <div class="col">
                            Error:
                        </div>
                        <div class="col">
                            {{ $message }}
                        </div>
                    </div>
                @endforeach
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@stop



