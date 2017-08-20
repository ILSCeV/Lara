{{-- Needs variables: sections --}}

@extends('layouts.master')

@section('title')
	{{ trans('mainLang.management') }}: {{ trans('mainLang.nanageSections') }} 
@stop

@section('content')

@if(Session::has('userGroup') AND Session::get('userGroup') == 'admin')

	<div class="panel panel-info col-xs-12 no-padding">
		<div class="panel-heading">
				<h4 class="panel-title">{{ trans('mainLang.management') }}: {{ trans('mainLang.manageSections') }}</h4>
		</div>	
		<div class="panel panel-body no-padding">	
			<table class="table info table-hover table-condensed">
				<thead>
					<tr class="active">
						<th class="col-md-1 col-xs-1">
							#
						</th>
						<th class="col-md-2 col-xs-2">
							{{ trans('mainLang.section') }}
						</th>
						<th class="col-md-4 col-xs-4">
							{{ trans("mainLang.color") }} 
						</th>
					</tr>
				</thead>
				<tbody>
					<div class="container">
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
									{!! $section->color !!}
								</td>
							</tr>
						@endforeach
					</div>
				</tbody>
			</table>
		</div>
	</div>
		
	<center>
		{{ $sections->links() }}
	</center>

	<br/>
@else
	@include('partials.accessDenied')
@endif
@stop



