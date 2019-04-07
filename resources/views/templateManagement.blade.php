@extends('layouts.master')

@section('title')
    {{ trans('mainLang.manageTemplates') }}
@stop

@section('content')

    <div class="card col-12 p-0">
        <div class="card-header bg-info text-white">
            <h4 class="card-title">{{ trans('mainLang.management') }}: {{ trans('mainLang.manageTemplates') }}</h4>
        </div>

        <div class="card-body p-0">
            <div class="d-inline-block">
                <div class="col-4 has-feedback d-inline-block ">
                    <label for="templateOverviewFilter" class="text-primary"> {{ trans('mainLang.search') }}: </label>
                    <input type="text" class="form-control form-control-sm" id="templateOverviewFilter" autofocus>
                </div>
                <div class="col-1 float-right">
                    <a class="btn btn-success btn-sm" href="{{route('template.create')}}">
                        {{ trans('mainLang.createTemplate') }}
                    </a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table info table-hover table-sm">
                    <thead>
                    <tr class="active">
                        <th>
                            #
                        </th>
                        <th class="">
                            {{ trans('mainLang.section') }}
                        </th>
                        <th class="">
                            {{ trans('mainLang.type') }}
                        </th>
                        <th class="">
                            {{ trans('mainLang.title') }}
                        </th>
                        <th class="">
                            {{ trans('mainLang.start') }}
                        </th>
                        <th class="">
                            {{ trans('mainLang.end') }}
                        </th>
                        <th class="">
                            &nbsp;
                        </th>
                    </tr>
                    </thead>
                    <tbody class="container" id="templateOverviewTable">

                    @foreach($templates as $index=>$template)
                        <tr>
                            <td>
                                {{ $index }}
                            </td>
                            <td class="pl-3">
                                {{ $template->section->title }}
                            </td>
                            <td>
                                {{ \Lara\Utilities::getEventTypeTranslation($template->type)  }}
                            </td>
                            <td>
                                <a href="{{ route('template.edit', $template) }}"> {{ $template->title }} </a>
                            </td>
                            <td>
                                {{ $template->time_start }}
                            </td>
                            <td>
                                {{ $template->time_end }}
                            </td>
                            <td class="pr-3">
                                <button data-id="{{$template->id}}"
                                        data-templatename="{{$template->title}}"
                                        class="btn btn-danger delete-template">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-template-{{$template->id}}"
                                      method="POST"
                                      class="d-none"
                                      action="{{route('template.delete', $template->id)}}">
                                    {{ csrf_field() }}
                                    <button class="hidden" type="submit"></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <br/>

@stop
