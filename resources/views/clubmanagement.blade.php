@extends('layouts.master')

@section('title')
    {{ trans('clubmanagement.title') }}
@stop

@section('content')
    <div class="panel panel-info col-xs-12 no-padding">
        <div class="panel-heading">
            <h4 class="panel-title">{{ trans('mainLang.management') }}: {{ trans('clubmanagement.clubs') }}</h4>
        </div>

        <div class="panel panel-body no-padding">
            <table class="table info table-hover table-condensed">
                <thead>
                    <tr class="active">
                        <th class="col-md-2 col-xs-2">
                            {{ trans('clubmanagement.club') }}
                        </th>
                        <th class="col-md-5 col-xs-5">
                            {{ trans('clubmanagement.section') }}
                        </th>
                        <th class="col-md-5 col-xs-5">
                            {{ trans('clubmanagement.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="container" id="clubmanagementtable">
                    @foreach($clubs as $club)
                        <tr>
                            <td>
                                {{ $club->clb_title }}
                            </td>
                            <td>
                                {{ $club->section ? $club->section->title : "" }}
                            </td>
                            <td>
                                @unless ($club->section)
                                    <a href="{{ $event->id }}"
                                       class="btn btn-warning"
                                       data-toggle="tooltip"
                                       data-placement="bottom"
                                       title="{{ trans('clubmanagement.deleteClub') }}"
                                       data-method="delete"
                                       data-token="{{csrf_token()}}"
                                       rel="nofollow"
                                       data-confirm="{{ trans('clubmanagement.confirmDeletion') }}">
                                       <i class="fa fa-trash"></i>
                                    </a>
                                @endunless
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <br/>
@stop
