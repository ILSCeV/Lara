@extends('layouts.master')

@section('title')
    {{ __('mainLang.manageTemplates') }}
@stop
@section('moreScripts')
    <script src="{{asset(WebpackBuiltFiles::$assets['templates.js'])}}"></script>
@endsection

@section('content')

    <div class="card col-12">
        <div class="card-header bg-info text-white">
            <h4 class="card-title">{{ __('mainLang.management') }}: {{ __('mainLang.manageTemplates') }}</h4>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-4 has-feedback me-auto">
                    <label for="templateOverviewFilter" class="form-label"> {{ __('mainLang.search') }}: </label>
                    <input type="text" class="form-control form-control-sm" id="templateOverviewFilter" autofocus>
                </div>
                <div class="col-3">
                    <a class="btn btn-success btn-sm" href="{{route('template.create')}}">
                        {{ __('mainLang.createTemplate') }}
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
                            {{ __('mainLang.section') }}
                        </th>
                        <th class="">
                            {{ __('mainLang.type') }}
                        </th>
                        <th class="">
                            {{ __('mainLang.title') }}
                        </th>
                        <th class="">
                            {{ __('mainLang.start') }}
                        </th>
                        <th class="">
                            {{ __('mainLang.end') }}
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
                            <td class="ps-3">
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
                            <td class="pe-3">
                                <button data-id="{{$template->id}}"
                                        data-templatename="{{$template->title}}"
                                        class="btn btn-danger delete-template">
                                    <i class="fa-solid  fa-trash"></i>
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

    <div class="w-100"></div>

@stop
