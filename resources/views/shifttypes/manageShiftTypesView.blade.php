{{-- Needs variables: shiftTypes --}}

@extends('layouts.master')

@section('title')
	{{ trans('mainLang.management') }}: {{ trans('mainLang.shiftTypes') }}
@stop

@section('content')

@is('marketing', 'clubleitung', 'admin')

	<div class="card card.text-white.bg-info col-xs-12 p-0">
		<div class="card-header">
				<h4 class="card-title">{{ trans('mainLang.management') }}: {{ trans('mainLang.shiftTypes') }}</h4>
		</div>
		<div class="card card-body p-0">
            <div class="clearfix pt-2"></div>
            <div>
                <table data-toggle="table" data-search="true">
                    <thead>
                    <tr class="active">
                        <th data-sortable="true">
                            #
                        </th>
                        <th data-searchable="true" data-field="shift">
                            {{ trans('mainLang.shift') }}
                        </th>
                        <th data-searchable="true">
                            {{ trans('mainLang.start') }}-{{ trans('mainLang.end') }}
                        </th>
                        <th data-searchable="true">
                            {{ trans('mainLang.weight') }}
                        </th>
                        <th>
                            {{ trans("mainLang.actions") }}
                        </th>
                        <th data-searchable="false">
                            {{ trans("mainLang.replaceAll") }}
                        </th>
                    </tr>
                    </thead>
                    <tbody>

                    <div class="container">
                        @foreach($shiftTypes as $shiftType)
                            <tr>
                                <td >
                                    {!! $shiftType->id !!}
                                </td>
                                <td >
                                    <a href="../shiftType/{{ $shiftType->id }}">
                                        {!! $shiftType->title !!}
                                    </a>
                                </td>
                                <td >
                                    {!! date("H:i", strtotime($shiftType->start)) !!}
                                    -
                                    {!! date("H:i", strtotime($shiftType->end)) !!}
                                </td>
                                <td >
                                    {!! $shiftType->statistical_weight !!}
                                </td>
                                <td >
                                    <a href="../shiftType/{{ $shiftType->id }}"
                                       class="btn btn-small btn-success"
                                       rel="nofollow">
                                        {{ trans('mainLang.editDetails') }}
                                    </a>
                                    &nbsp;&nbsp;
                                    <a href="../shiftType/{{ $shiftType->id }}"
                                       class="btn btn-small btn-danger"
                                       data-method="delete"
                                       data-token="{{csrf_token()}}"
                                       rel="nofollow"
                                       data-confirm="{{ trans('mainLang.deleteConfirmation') }} &#39;&#39;{!! $shiftType->title !!}&#39;&#39; (#{{ $shiftType->id }})? {{ trans('mainLang.warningNotReversible') }}">
                                        {{ trans('mainLang.deleteThisShiftType') }}
                                    </a>
                                </td>
                                <td >
                                    @include('shifttypes.shiftTypeSelect',['shift'=>$shiftType,'shiftTypes' => $shiftTypes,'route'=>'completeOverrideShiftType','shiftTypeId'=>$shiftType->id, 'selectorClass'=>'shiftTypeReplaceSelector'])
                                </td>
                            </tr>
                        @endforeach
                    </div>
                    </tbody>
                </table>
            </div>
		</div>
	</div>

	<br/>
@else
	@include('partials.accessDenied')
@endis
@stop



