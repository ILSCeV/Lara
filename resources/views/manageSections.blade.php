{{-- Needs variables: sections --}}

@extends('layouts.master')

@section('title')
    {{ trans('mainLang.manageSections') }}
@stop

@section('content')

    <div class="card  col-12 p-0">
        <div class="card-header text-white bg-info">
            <h4 class="card-title">{{ trans('mainLang.management') }}: {{ trans('mainLang.manageSections') }}</h4>
        </div>
        <div class="card-body p-0">
            <table class="table info table-hover table-sm">
                <thead>
                <tr class="active">
                    <th class="">
                        #
                    </th>
                    <th class="">
                        {{ trans('mainLang.section') }}
                    </th>
                    <th class="">
                        {{ trans("mainLang.color") }}
                    </th>
                </tr>
                </thead>
                <tbody class="">
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



