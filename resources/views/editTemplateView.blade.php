@extends('layouts.master')

@section('title')
    {{ trans('mainLang.editDetails') }}
@stop

@section('content')
    {!! Form::open(['method' => 'POST', 'route' => ['template.update', $template->id], 'class'=> 'form-inline']) !!}
    <div class="row">
        <div class="panel col-md-6 col-sm-12 col-xs-12 no-padding">
            <div class="panel-heading">
                <h4 class="panel-title">{{ trans('mainLang.changeEventJob') }}:</h4>
            </div>

            <br>

            <div class="panel-body no-padding">
                <br>

                <div class="panel-body no-padding">
                    <div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
                        <label for="title" class="col-md-2 col-sm-2 col-xs-3">{{ trans('mainLang.title') }}:</label>
                        {!! Form::text('title',
                                        $template->title,
                                        array('class'=>'form-control',
                                              'placeholder'=>trans('mainLang.placeholderTitleWineEvening'),
                                              'style'=>'cursor: auto',
                                              'required') ) !!}
                    </div>
                    <div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
                        <label for="subtitle" class="col-md-2 col-sm-2 col-xs-3">{{ trans('mainLang.subTitle') }}
                            :</label>
                        {!! Form::text('subtitle',
                                        $template->subtitle,
                                        array('class'=>'form-control',
                                                'placeholder'=>trans('mainLang.placeholderSubTitleWineEvening'),
                                                'style'=>'cursor: auto') ) !!}
                    </div>
                    <div class="form-group ">
                        <label for="evnt_type" class="col-md-2 col-sm-2 col-xs-2">{{ trans('mainLang.type') }}:</label>
                        <div class="col-md-10 col-sm-10 col-xs-10">
                            @for($i = 0;$i<10;$i++)
                                <div class="radio">
                                    <label>
                                        {{ Form::radio('type', $i, $template->type == $i) }}
                                        {{ \Lara\Utilities::getEventTypeTranslation($i) }}
                                    </label>
                                </div>
                                <br/>
                            @endfor
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="evnt_type" class="col-md-2 col-sm-2 col-xs-2"> &nbsp; </label>
                        <label class="col-md-8 col-sm-8 col-xs-8">
                            {!! Form::checkbox('isPrivate', '1', ($template->is_private + 1)%2,['class'=>'form-control']) !!}
                            {{ trans('mainLang.showExtern') }}
                        </label>
                    </div>
                    <br/>
                    <div class="form-group">
                        <label for="evnt_type" class="col-md-3 col-sm-3 col-xs-3">{{ trans('mainLang.faceNeeded') }}: </label>
                        <div class="input-group">
                            <span class="input-group-addon">
                                <label>
                            {{ Form::radio('facebookNeeded',true,$template->facebook_needed == true, ['class'=>'form-control']) }}
                                    {{ trans('mainLang.yes')  }}
                                </label>
                            </span>
                            <span class="input-group-addon">
                                <label>
                            {{ Form::radio('facebookNeeded',false,$template->facebook_needed == false, ['class'=>'form-control']) }}
                                {{ trans('mainLang.no')  }}
                                </label>
                            </span>
                        </div>
                    </div>
                    <br>
                    <div class="form-group ">
                        <label for="section" class="control-label">{{ trans('mainLang.section') }}: &nbsp;</label>
                        <select id="section" class="selectpicker" name="section"
                                @if(!\Lara\Utilities::requirePermission("admin")) disabled @endif>
                            @foreach($sections as $section)
                                <option value="{{$section->id}}"
                                        data-content="<span class='palette-{!! $section->color !!}-900 bg'> {{$section->title}} </span>"
                                        @if($template->section->id == $section->id) selected @endif >{{ $section->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <br>
                    <div class="form-group " id="filter-checkboxes">
                        <label for="filter" class="control-label ">{{ trans('mainLang.showFor') }}: &nbsp;</label>
                        <div id="filter" class="input-group">
                            @foreach($sections as $section)
                                <label>
                                    {{ Form::checkbox("filter[" . $section->title ."]", $section->id, in_array( $section->title, $template->showToSectionNames())) }}
                                    {{ $section->title }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">

                        <div id="priceTickets" class="input-group">
                            <span for="priceTickets" class="control-label input-group-addon">
                                {{ trans('mainLang.priceTickets') }}:
                                ({{ trans('mainLang.studentExtern') }})
                            </span>
                            <input class="form-control" type="number" name="priceTicketsNormal" step="0.1"
                                   placeholder="Student" value="{{$template->price_tickets_normal}}"/>
                            <span class="input-group-addon">€</span>
                            <span class="input-group-addon">/</span>
                            <input class="form-control" type="number" name="priceTicketsExternal" step="0.1"
                                   placeholder="Extern" value="{{$template->price_tickets_external}}"/>
                            <span class="input-group-addon">€</span>
                        </div>
                    </div>
                    <br>
                    <div class="form-group ">
                        <div id="price" class="input-group">
                                <span for="price" class="control-label input-group-addon" >
                                    {{ trans('mainLang.price') }}:
                                    ({{ trans('mainLang.studentExtern') }})</span>
                                <input class="form-control" type="number" name="priceNormal" step="0.1"
                            placeholder="Student" value="{{$template->price_normal}}"/>
                            <span class="input-group-addon">€</span>
                            <span class="input-group-addon">/</span>
                            <input class="form-control" type="number" name="priceExternal" step="0.1"
                                   placeholder="Extern" value="{{$template->price_external}}"/>
                            <span class="input-group-addon">€</span>
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="preparationTime" class="control-label ">{{ trans('mainLang.DV-Time') }}:</label>
                        {!! Form::input('time', 'preparationTime', $template->time_preparation_start, ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="container col-xs-12 col-sm-12 col-md-6 no-padding-xs">
            <br class="visible-xs visible-sm">
            <div class="panel">
                <div class="panel-heading">
                    <h4 class="panel-title">{{ trans('mainLang.moreInfos') }}:</h4>
                    ({{ trans('mainLang.public') }})
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="col-md-12">
                            {!! Form::textarea('publicInfo', $template->public_info, array('class'=>'form-control',
                                                                      'rows'=>'8',
                                                                      'placeholder'=>trans('mainLang.placeholderPublicInfo')) ) !!}
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="panel">
                <div class="panel-heading">
                    <h4 class="panel-title">{{ trans('mainLang.details') }}:</h4>
                    ({{ trans('mainLang.showOnlyIntern') }})
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="col-md-12">
                            {!! Form::textarea('privateDetails', $template->private_details, array('class'=>'form-control',
                                                                          'rows'=>'5',
                                                                          'placeholder'=>trans('mainLang.placeholderPrivateDetails')) ) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @include('partials.editSchedule')
        <br>
        {!! Form::submit( trans('mainLang.update') , array('class'=>'btn btn-success', 'id'=>'button-edit-submit')) !!}
        &nbsp;&nbsp;&nbsp;&nbsp;
        <br class="visible-xs">
        <br class="visible-xs">
        <a href="javascript:history.back()" class="btn btn-default">{{ trans('mainLang.backWithoutChange') }} </a>

    </div>
    {!! Form::close() !!}

@stop
