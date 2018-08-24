{{-- Needs variable: $current_section --}}
@extends('layouts.master')

@section('title')
    {{ trans('mainLang.management') }}: #{{ $current_section->id }} - {!! $current_section->title !!}
@stop

@section('content')
    @php
        /** @var \Lara\Section $current_section */
    @endphp

    <div class="panel panel-info">
        <div class="panel-heading">
            <h4 class="panel-title">#{{ $current_section->id }}: "{!! $current_section->title !!}" </h4>
        </div>

        <div class="panel panel-body no-padding">
            <table class="table table-hover">
                @if(!isset($current_section->id))
                    {!! Form::open(  array( 'route' => ['section.store', $current_section->id],
                                            'id' => $current_section->id,
                                            'method' => 'POST',
                                            'class' => 'section')  ) !!}
                @else
                    {!! Form::open(  array( 'route' => ['section.update', $current_section->id],
                                            'id' => $current_section->id,
                                            'method' => 'PUT',
                                            'class' => 'section')  ) !!}
                @endif
                {{-- Title --}}
                <tr>
                    <td width="20%" class="left-padding-16">
                        <i>{{ trans('mainLang.section') }}:</i>
                    </td>
                    <td>
                        {!! Form::text('id',
                           $current_section->id,
                           array('id'=>'id', 'hidden')) !!}

                        {!! Form::text('title',
                           $current_section->title,
                           array('id'=>'title')) !!}
                    </td>
                </tr>
                {{-- Color --}}
                <tr class="form-group">
                    <td width="20%" class="left-padding-16">
                        <label for="color">
                            <i>{{ trans('mainLang.color') }}:</i>
                        </label>
                    </td>
                    <td>
							<span class="col-md-12 col-sm-12 col-xs-12 no-padding">
								{!! Form::text('color', $current_section->color, array('id'=>'color', 'readonly', 'class'=>'palette-'.$current_section->color.'-500-Primary bg') ) !!}
                                <a class="btn-small btn-primary dropdown-toggle"
                                   data-toggle="dropdown"
                                   href="javascript:void(0);">
							        <span class="caret"></span>
							    </a>
							    <ul class="dropdown-menu">
								    @foreach(config('color.colors') as $color)
                                        <li>
								        	<a href="javascript:void(0);"
                                               class="palette-{{$color}}-500-Primary bg"
                                               onClick="document.getElementById('color').value='{{$color}}'">
								        		{{ $color }}
								        	</a>
								        </li>
                                    @endforeach
							    </ul>  	
						    </span>
                    </td>
                </tr>
                {{-- Event defaults --}}
                <tr>
                    <td colspan="2">
                        {{ trans('mainLang.eventDefaults')}}
                    </td>
                </tr>
                {{-- Event DV time --}}
                <tr class="form-group">
                    <td width="20%" class="left-padding-16">
                        <label for="preparationTime">
                            <i>{{ trans('mainLang.DV-Time') }}:</i>
                        </label>
                    </td>
                    <td>
							<span class="col-md-12 col-sm-12 col-xs-12 no-padding">
								{!! Form::input('time', 'preparationTime', $current_section->preparationTime) !!}
						    </span>
                    </td>
                </tr>
                {{-- Event start time --}}
                <tr class="form-group">
                    <td width="20%" class="left-padding-16">
                        <label for="startTime">
                            <i>{{ trans('mainLang.begin') }}:</i>
                        </label>
                    </td>
                    <td>
							<span class="col-md-12 col-sm-12 col-xs-12 no-padding">
								{!! Form::input('time', 'startTime', $current_section->startTime) !!}
						    </span>
                    </td>
                </tr>
                {{-- Event end time --}}
                <tr class="form-group">
                    <td width="20%" class="left-padding-16">
                        <label for="endTime">
                            <i>{{ trans('mainLang.end') }}:</i>
                        </label>
                    </td>
                    <td>
							<span class="col-md-12 col-sm-12 col-xs-12 no-padding">
								{!! Form::input('time', 'endTime', $current_section->endTime) !!}
						    </span>
                    </td>
                </tr>
                {{-- is club name public --}}
                <tr class="form-group">
                    <td width="20%" class="left-padding-16">
                        <label for="endTime">
                            <i>{{ trans('mainLang.privateClubName') }}:</i>
                        </label>
                    </td>
                    <td>
							<span class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                <div class="radio-inline">
                                    <label>
                                        {!! Form::radio( 'is_name_private','true', $current_section->is_name_private === 1) !!}
                                        {{ trans('mainLang.privateClubNameYes') }}
                                    </label>
                                </div>
                                <div class="radio-inline">
                                    <label>
                                        {!! Form::radio( 'is_name_private','false', $current_section->is_name_private === 0) !!}
                                        {{ trans('mainLang.privateClubNameNo') }}
                                    </label>
                                </div>
                            </span>
                    </td>
                </tr>
                {{-- CRUD --}}
                <tr>
                    <td>
                        &nbsp;
                    </td>
                    <td>
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
                        <button type="reset" class="btn btn-small btn-default">{{ trans('mainLang.reset') }}</button>
                        @if(!isset($current_section->id))
                            <button type="submit"
                                    class="btn btn-small btn-success">{{ trans('mainLang.createSection') }}</button>
                        @else
                            <button type="submit"
                                    class="btn btn-small btn-success">{{ trans('mainLang.update') }}</button>
                        @endif
                    </td>
                </tr>
                @foreach ($errors->all() as $message)
                    <tr>
                        <td>
                            Error:
                        </td>
                        <td>
                            {{ $message }}
                        </td>
                    </tr>
                @endforeach
                {!! Form::close() !!}
            </table>
        </div>
    </div>


@stop



