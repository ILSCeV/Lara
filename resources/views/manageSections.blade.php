{{-- Needs variables: sections --}}

@extends('layouts.master')

@section('title')
    {{ trans('mainLang.manageSections') }}
@stop

@section('content')

    <div class="card card.text-white.bg-info col-xs-12 no-padding">
        <div class="card-header">
            <h4 class="card-title">{{ trans('mainLang.management') }}: {{ trans('mainLang.manageSections') }}</h4>
        </div>
        <div class="card card-body no-padding">
            <table class="table info table-hover table-sm">
                <thead>
                <tr class="active">
                    <th class="col-md-1 col-xs-1">
                        #
                    </th>
                    <th class="col-md-2 col-xs-2">
                        {{ trans('mainLang.section') }}
                    </th>
                    <th class="col-md-4 col-xs-4">
                        {{ trans("mainLang.color") }}
                    </th>
                </tr>
                </thead>
                <tbody class="container">
                @foreach($sections as $section)
                    <tr>
                        <td>
                            {!! $section->id !!}
                        </td>
                        <td>
                            <a href="../section/{{ $section->id }}">
                                {!! $section->title !!}
                            </a>
                        </td>
                        <td>
									<span class="palette-{{$section->color}}-500-Primary bg">
										&nbsp;&nbsp;&nbsp;&nbsp;{!! $section->color !!}&nbsp;&nbsp;&nbsp;&nbsp;
									</span>
                        </td>
                    </tr>
                @endforeach
                @is('admin')
                <tr>
                    <td></td>
                    <td><a class="btn btn-success"
                           href="{!! action('SectionController@create') !!}">{{ trans('mainLang.createSection') }}</a>
                    </td>
                    <td></td>
                </tr>
                @endis
                </tbody>
            </table>
        </div>
    </div>

    <br/>

@stop



