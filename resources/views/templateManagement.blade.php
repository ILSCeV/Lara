@extends('layouts.master')

@section('title')
    {{ trans('mainLang.manageTemplates') }}
@stop

@section('content')

    <div class="panel panel-info col-xs-12 no-padding">
        <div class="panel-heading">
            <h4 class="panel-title">{{ trans('mainLang.management') }}: {{ trans('mainLang.manageTemplates') }}</h4>
        </div>

        <div class="panel panel-body no-padding">
            <div class="table-responsive">
                <table class="table info table-hover table-condensed">
                    <thead>
                    <tr class="active">
                        <th>
                            #
                        </th>
                        <th class="col-md-1 col-xs-1 padding-left-15">
                            {{ trans('mainLang.section') }}
                        </th>
                        <th class="col-md-3 col-xs-3">
                            {{ trans('mainLang.type') }}
                        </th>
                        <th class="col-md-6 col-xs-6">
                            {{ trans('mainLang.title') }}
                        </th>
                        <th class="col-md-1 col-xs-1">
                            {{ trans('mainLang.start') }}
                        </th>
                        <th class="col-md-1 col-xs-1">
                            {{ trans('mainLang.end') }}
                        </th>
                        <th class="col-md-1 col-xs-1 padding-right-15">
                            &nbsp;
                        </th>
                    </tr>
                    </thead>
                    <tbody class="container" id="templateOverviewTable">

                    <div class="table-control form-inline">
                        <div class="form-group has-feedback">
                            <label for="templateOverviewFilter" class="text-primary"> {{ trans('mainLang.search') }}: </label>
                            <input type="text" class="form-control" id="templateOverviewFilter" autofocus>
                        </div>
                        <div class="form-group pull-right">
                           <a class="btn btn-success" href="{{route('template.create')}}">
                              {{ trans('mainLang.createTemplate') }}
                           </a>
                        </div>
                    </div>

					@if( ! empty($templates['title']) )
    
						@foreach($templates as $index=>$template)
							<tr>
								<td>
									{{ $index }}
								</td>
								<td class="padding-left-15">
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
								<td class="padding-right-15">
									<button data-id="{{$template->id}}"
											data-templatename="{{$template->title}}"
											class="btn btn-danger delete-template">
										<span class="glyphicon glyphicon-trash"></span>
									</button>
									<form id="delete-template-{{$template->id}}"
										  method="POST"
										  class="hidden"
										  action="{{route('template.delete', $template->id)}}">
										{{ csrf_field() }}
										<button class="hidden" type="submit"></button>
									</form>
								</td>
							</tr>
						@endforeach
					
					@endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <br/>

@stop
