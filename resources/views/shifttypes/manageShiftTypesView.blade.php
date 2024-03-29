{{-- Needs variables: shiftTypes --}}

@extends('layouts.master')

@section('title')
	{{ __('mainLang.management') }}: {{ __('mainLang.shiftTypes') }}
@stop

@section('content')

@is('marketing', 'clubleitung', 'admin')

	<div class="card col-12 p-0">
		<div class=" text-white bg-info card-header">
				<h4 class="card-title">{{ __('mainLang.management') }}: {{ __('mainLang.shiftTypes') }}</h4>
		</div>
		<div class="card-body p-0">
            <div class="clearfix pt-2"></div>
            <div class="row justify-content-end">
            <div class="col-2 align-self-end">
                {{Form::open(['url'=> route('searchShiftType'), 'method'=>'POST' ])}}
                <div class="form-group form-group-sm">
                    <input type="text" name="filter" placeholder="{{__('mainLang.filter')}}" class="form-control form-control-sm form-control-plaintext">
                </div>
                <div class="form-group form-group-sm">
                    {{Form::submit('',['class'=>'d-none'])}}
                </div>
                {{Form::close()}}
            </div>
            </div>
            <div>
                <table class="table table-hover">
                    <thead>
                    <tr class="active">
                        <th data-sortable="true">
                            #
                        </th>
                        <th data-searchable="true" data-field="shift">
                            {{ __('mainLang.shift') }}
                        </th>
                        <th data-searchable="true">
                            {{ __('mainLang.start') }}-{{ __('mainLang.end') }}
                        </th>
                        <th data-searchable="true">
                            {{ __('mainLang.weight') }}
                        </th>
                        <th class="text-center">
                            {{ __("mainLang.actions") }}
                        </th>
                        <th data-searchable="false" class="text-center">
                            {{ __("mainLang.replaceAll") }}
                        </th>
                    </tr>
                    </thead>
                    <tbody>
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
                                <td class="text-center">
                                    <a href="../shiftType/{{ $shiftType->id }}"
                                       class="btn btn-sm btn-success"
                                       rel="nofollow">
                                        {{ __('mainLang.editDetails') }}
                                    </a>
                                    &nbsp;&nbsp;
                                    <a href="../shiftType/{{ $shiftType->id }}"
                                       class="btn btn-sm btn-danger"
                                       data-method="delete"
                                       data-token="{{csrf_token()}}"
                                       rel="nofollow"
                                       data-confirm="{{ __('mainLang.deleteConfirmation') }} &#39;&#39;{!! $shiftType->title !!}&#39;&#39; (#{{ $shiftType->id }})? {{ __('mainLang.warningNotReversible') }}">
                                        {{ __('mainLang.deleteThisShiftType') }}
                                    </a>
                                </td>
                                <td class="text-center">
                                    @include('shifttypes.shiftTypeSelect',['shift'=>$shiftType,'shiftTypes' => $allShiftTypes,'route'=>'completeOverrideShiftType','shiftTypeId'=>$shiftType->id, 'selectorClass'=>'shiftTypeReplaceSelector'])
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
		</div>
        <div class="card-footer"> {{ $shiftTypes->links() }} </div>
	</div>

	<br/>
@else
	@include('partials.accessDenied')
@endis
@stop



