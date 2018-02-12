{{-- Needs variables: shiftTypes --}}

@extends('layouts.master')

@section('title')
	{{ trans('mainLang.management') }}: {{ trans('mainLang.shiftTypes') }} 
@stop

@section('content')

@is(['marketing', 'clubleitung', 'admin')

	<div class="panel panel-info col-xs-12 no-padding">
		<div class="panel-heading">
				<h4 class="panel-title">{{ trans('mainLang.management') }}: {{ trans('mainLang.shiftTypes') }}</h4>
		</div>	
		<div class="panel panel-body no-padding">	
			<table class="table info table-hover table-condensed">
				<thead>
					<tr class="active">
						<th class="col-md-1 col-xs-1">
							#
						</th>
						<th class="col-md-3 col-xs-3">
							{{ trans('mainLang.shift') }}
						</th>
						<th class="col-md-2 col-xs-2">
							{{ trans('mainLang.start') }}-{{ trans('mainLang.end') }}
						</th>
						<th class="col-md-1 col-xs-1">
							{{ trans('mainLang.weight') }}
						</th>
						<th class="col-md-4 col-xs-4">
							{{ trans("mainLang.actions") }}
						</th>
					</tr>
				</thead>
				<tbody>

					<div class="container">
						@foreach($shiftTypes as $shiftType)
							<tr>
								<td>
									{!! $shiftType->id !!}
								</td>
								<td>
							      	<a href="../shiftType/{{ $shiftType->id }}">
							      		{!! $shiftType->title !!}
							      	</a>
								</td>
								<td>						
									{!! date("H:i", strtotime($shiftType->start)) !!}
									-
									{!! date("H:i", strtotime($shiftType->end)) !!}
								</td>
								<td>
									{!! $shiftType->statistical_weight !!}
								</td>
								<td>
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
							</tr>
						@endforeach
					</div>
				</tbody>
			</table>
		</div>
	</div>
		
	<center>
		{{ $shiftTypes->links() }}
	</center>

	<br/>
@else
	@include('partials.accessDenied')
@endis
@stop



