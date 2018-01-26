@extends('layouts.master')

@section('title')
    {{ trans('mainLang.manageTemplate') }}
@stop

@section('content')

    <div class="col-md-12">
        <div class="form-inline fa-border">
            <input type="text" class="form-control" id="templateOverviewFilter">
        </div>
        <table class="table table-hover table-striped">
            <thead>
            <tr>
                <th class="text-center"> {{ trans('mainLang.type') }} </th>
                <th class="text-center"> {{ trans('mainLang.title') }} </th>
                <th class="text-center"> {{ trans('mainLang.start') }} </th>
                <th class="text-center"> {{ trans('mainLang.end') }} </th>
            </tr>
            </thead>
            <tbody id="templateOverviewTable">
            @foreach($templates as $template)
                <tr>
                    <td class="text-center" > {{ \Lara\Utilities::getEventTypeTranslation($template->type)  }} </td>
                    <td class="text-center" > {{ $template->title }} </td>
                    <td class="text-center" > {{ $template->time_start }} </td>
                    <td class="text-center" > {{ $template->time_end }} </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@stop
