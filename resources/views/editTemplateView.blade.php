@extends('layouts.master')

@section('title')
    {{ __('mainLang.editDetails') }}
@stop

@section('moreScripts')
    <script src="{{asset(WebpackBuiltFiles::$assets['autocomplete.js'])}}" ></script>
@endsection

@section('content')
    @if($template->id == null)
        {!! Form::open(['method' => 'POST', 'route' => ['template.update', 0], 'class'=> 'form-inline']) !!}
    @else
        {!! Form::open(['method' => 'POST', 'route' => ['template.update', $template->id], 'class'=> 'form-inline']) !!}
    @endif
    <div class="row">
        <div class="card col-md-6 col-sm-12 col-12 ">
            <div class="card-header">
                <h4 class="card-title">{{ __('mainLang.changeEventJob') }}:</h4>
            </div>
            <div class="card-body">
                <div class="">
                    <div class="form-group  ">
                        <label for="title" class="col-3">{{ __('mainLang.title') }}:</label>
                        {!! Form::text('title',
                                        $template->title,
                                        array('class'=>'form-control col-8',
                                              'placeholder'=>__('mainLang.placeholderTitleWineEvening'),
                                              'style'=>'cursor: auto',
                                              'required') ) !!}
                    </div>
                    <br />
                    <div class="form-group  ">
                        <label for="subtitle" class="col-3">{{ __('mainLang.subTitle') }}:</label>
                        {!! Form::text('subtitle',
                                        $template->subtitle,
                                        array('class'=>'form-control col-8',
                                                'placeholder'=>__('mainLang.placeholderSubTitleWineEvening'),
                                                'style'=>'cursor: auto') ) !!}
                    </div>
                    <br/>
                    <div class="form-group ">
                        <label for="evnt_type" class="col-3">{{ __('mainLang.type') }}:</label>
                        <div class="col-8">
                            @for($i = 0;$i<12;$i++)
                                <div class="form-check float-start">
                                    <label >
                                        {{ Form::radio('type', $i, $template->type == $i) }}
                                        {{ \Lara\Utilities::getEventTypeTranslation($i) }}
                                    </label>
                                </div>
                            <br/>
                            @endfor
                        </div>
                    </div>

                    <div class="form-group ">
                        <label for="evnt_type" class="col-2"> &nbsp; </label>
                        <label class="col-10">
                            {!! Form::checkbox('isPrivate', '1', ($template->is_private + 1)%2,['class'=>'form-control']) !!}
                            {{ __('mainLang.showExtern') }}
                        </label>
                    </div>

                    <div class="form-group ">
                        <label for="evnt_type" class="col-3">{{ __('mainLang.facebookNeeded') }}: </label>
                        <div class="input-group col-8">
                            <span class="input-group-append">
                                <label>
                            {{ Form::radio('facebookNeeded',true,$template->facebook_needed == true, ['class'=>'form-control']) }}
                                    {{ __('mainLang.yes')  }}
                                </label>
                            </span>
                            <span class="input-group-append">
                                <label>
                            {{ Form::radio('facebookNeeded',false,$template->facebook_needed == false, ['class'=>'form-control']) }}
                                {{ __('mainLang.no')  }}
                                </label>
                            </span>
                        </div>
                    </div>

                    <div class="form-group col">
                        <label for="section" class="col-form-label col-3">{{ __('mainLang.section') }}: &nbsp;</label>
                        <select id="section" class="form-select" name="section" >
                            @php
                                if(Auth::user()->isAn(\Lara\utilities\RoleUtility::PRIVILEGE_ADMINISTRATOR)){
                                $allowedSections = $sections;
                                }
                                else {
                                $allowedSections = Auth::user()->roles->filter(function (\Lara\Role $r){
                                return $r->name == \Lara\utilities\RoleUtility::PRIVILEGE_MARKETING;
                                })->map(function (\Lara\Role $r){
                                return $r->section;
                                });
                                }
                            @endphp
                            @foreach($allowedSections as $section)
                                <option value="{{$section->id}}"
                                        data-content="<span class='palette-{!! $section->color !!}-900 bg'> {{$section->title}} </span>"
                                        @if($template->section->id == $section->id) selected @endif >{{ $section->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <br>
                    <div class="form-group col-12" id="filter-checkboxes">
                        <label for="filter" class="col-form-label col-3">{{ __('mainLang.showFor') }}: &nbsp;</label>
                        <div id="filter" class="input-group form-check">
                            @if($template->id == null)
                                @foreach($sections as $section)
                                    <label>
                                        {{ Form::checkbox("filter[" . $section->title ."]", $section->id, $section->id === Auth::user()->section_id )}}
                                        {{ $section->title }}
                                    </label>
                                @endforeach
                            @else
                                @foreach($sections as $section)
                                    <label>
                                        {{ Form::checkbox("filter[" . $section->title ."]", $section->id, in_array( $section->title, $template->showToSectionNames())) }}
                                        {{ $section->title }}
                                    </label>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="priceTickets" class="col-form-label col-3">
                            {{ __('mainLang.priceTickets') }}:
                            ({{ __('mainLang.studentExtern') }})
                        </label>
                        <div id="priceTickets" class="input-group col-8">

                            <input class="form-control" type="number" name="priceTicketsNormal" step="0.1" min="0"
                                   placeholder="Student" value="{{$template->price_tickets_normal}}"/>
                            <span class="input-group-addon">€</span>
                            <span class="input-group-addon">/</span>
                            <input class="form-control" type="number" name="priceTicketsExternal" step="0.1" min="0"
                                   placeholder="Extern" value="{{$template->price_tickets_external}}"/>
                            <span class="input-group-addon">€</span>
                        </div>
                    </div>
                    <br>
                    <div class="form-group col">
                        <label for="price" class="col-form-label col-3 " >
                            {{ __('mainLang.price') }}:
                            ({{ __('mainLang.studentExtern') }})</label>
                        <div id="price" class="input-group col-8">
                                <input class="form-control" type="number" name="priceNormal" step="0.1" min="0"
                            placeholder="Student" value="{{$template->price_normal}}"/>
                            <span class="input-group-addon">€</span>
                            <span class="input-group-addon">/</span>
                            <input class="form-control" type="number" name="priceExternal" step="0.1" min="0"
                                   placeholder="Extern" value="{{$template->price_external}}"/>
                            <span class="input-group-addon">€</span>
                        </div>

                    </div>

                    <div class="form-group col">
                        <label for="preparationTime" class="col-form-label col-3">{{ __('mainLang.DV-Time') }}:</label>
                        <div class="col-8">
                            {!! Form::input('time', 'preparationTime', $template->time_preparation_start, ['class'=>'form-control ']) !!}
                        </div>
                    </div>

                    <div class="form-group col">
                        <label for="beginDate" class="col-form-label col-3">{{ __('mainLang.begin') }}:</label>
                        <div class="col-8">
                            {!! Form::input('time', 'beginTime', $template->time_start, ['class'=>'form-control'] ) !!}
                        </div>
                    </div>

                    <div class="form-group col">
                        <label for="endDate" class="col-form-label col-3">{{ __('mainLang.end') }}:</label>
                        <div class="col-8">
                             {!! Form::input('time', 'endTime', $template->time_end, ['class'=>'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br/>
        <div class="container col ">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('mainLang.moreInfos') }}:</h4>
                    ({{ __('mainLang.public') }})
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="col-md-12">
                            {!! Form::textarea('publicInfo', $template->public_info, array('class'=>'form-control',
                                                                      'rows'=>'8',
                                                                      'placeholder'=>__('mainLang.placeholderPublicInfo')) ) !!}
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('mainLang.details') }}:</h4>
                    ({{ __('mainLang.showOnlyIntern') }})
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="col-md-12">
                            {!! Form::textarea('privateDetails', $template->private_details, array('class'=>'form-control',
                                                                          'rows'=>'5',
                                                                          'placeholder'=>__('mainLang.placeholderPrivateDetails')) ) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row col-12">
        <div class="col-12">
            @include('partials.editSchedule')
        </div>
    </div>
    <div class="row">
        @if($template->id == null)
            {!! Form::submit( __('mainLang.createTemplate') , array('class'=>'btn btn-success', 'id'=>'button-edit-submit')) !!}
        @else
            {!! Form::submit( __('mainLang.update') , array('class'=>'btn btn-success', 'id'=>'button-edit-submit')) !!}
        @endif
        &nbsp;&nbsp;&nbsp;&nbsp;
        <br class="d-block d-sm-none">
        <br class="d-block d-sm-none">
        <a href="javascript:history.back()" class="btn btn-secondary">{{ __('mainLang.backWithoutChange') }} </a>

    </div>
    {!! Form::close() !!}

@stop
