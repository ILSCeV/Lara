@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="panel panel-body no-padding">
            <table class="table info table-hover table-condensed" data-search="true">
                <thead>
                    <tr class="active">
                        <th class="col-md-1 col-xs-1 padding-left-15">
                            ModelType
                        </th>
                        <th class="col-md-3 col-xs-3">
                            User
                        </th>
                        <th class="col-md-2 col-xs-2">
                            Action
                        </th>
                        <th class="col-md-2 col-xs-2">
                            Old value
                        </th>
                        <th class="col-md-2 col-xs-2">
                            New value
                        </th>
                        <th class="col-md-2 col-xs-2">
                            When
                        </th>
                    </tr>
                </thead>
                <tbody class="container" id="logsTable">
                @foreach($logs as $log) 
                        <tr>
                            <td class="padding-left-15">
                                {{ $log->loggable_type }}
                            </td>
                            <td>
                                {{ $log->user ? $log->user->nickNameAndFullName() : "" }}
                            </td>
                            <td>
                                {{ trans($log->action) }}
                            </td>
                            <td>
                                {{ $log->old_value }}
                            </td>
                            <td> 
                                {{ $log->new_value }}
                            </td>
                            <td>
                                {{ $log->created_at }}
                            </td>
                        </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{ $logs->links() }}
@stop
