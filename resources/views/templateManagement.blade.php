@extends('layouts.master')

@section('title')
    {{ trans('mainLang.manageTemplate') }}
@stop

@section('content')

    <div class="col-md-12 panel">
        <div class="form-inline fa-border has-feedback">
           <label for="templateOverviewFilter"> {{ trans('mainLang.filter') }} </label>
           <input type="text" class="form-control" id="templateOverviewFilter">
        </div>
        <table class="table table-hover table-striped">
            <thead>
            <tr>
                <th class="text-center"> {{ trans('mainLang.section') }} </th>
                <th class="text-center"> {{ trans('mainLang.type') }} </th>
                <th class="text-center"> {{ trans('mainLang.title') }} </th>
                <th class="text-center"> {{ trans('mainLang.start') }} </th>
                <th class="text-center"> {{ trans('mainLang.end') }} </th>
                <th></th>
            </tr>
            </thead>
            <tbody id="templateOverviewTable">
            @foreach($templates as $template)
                <tr>
                    <td class="text-center"> {{ $template->section->title }} </td>
                    <td class="text-center" > {{ \Lara\Utilities::getEventTypeTranslation($template->type)  }} </td>
                    <td class="text-center" > <a href="{{ route('template.edit', $template) }}"> {{ $template->title }} </a> </td>
                    <td class="text-center" > {{ $template->time_start }} </td>
                    <td class="text-center" > {{ $template->time_end }} </td>
                    <td class="text-center" > <button data-id="{{$template->id}}" data-templatename="{{$template->title}}" class="btn btn-danger delete-template"> <span class="glyphicon glyphicon-trash"></span> </button>
                        <form id="delete-template-{{$template->id}}" method="POST" class="hidden" action="{{route('template.delete', $template->id)}}">
                            {{ csrf_field() }}
                        <button  class="hidden" type="submit"></button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@stop
