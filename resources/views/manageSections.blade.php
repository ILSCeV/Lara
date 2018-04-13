{{-- Needs variables: sections --}}

@extends('layouts.master')

@section('title')
	{{ trans('mainLang.manageSections') }} 
@stop

@section('content')

@is('admin')

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
				<tbody class="container">
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
									<span class="palette-{{$section->color}}-500-Primary bg">
										&nbsp;&nbsp;&nbsp;&nbsp;{!! $section->color !!}&nbsp;&nbsp;&nbsp;&nbsp;
									</span>
								</td>
							</tr>
						@endforeach
				<tr>
					<td></td>
					<td> <a class="btn btn-success" href="{!! action('SectionController@create') !!}">{{ trans('mainLang.createSection') }}</a> </td>
					<td></td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>

		<div class="text-center">
		{{ $sections->links() }}
		</div>

	<br/>
@else
	@include('partials.accessDenied')
@endis
@stop



