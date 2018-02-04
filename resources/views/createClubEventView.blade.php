@extends('layouts.master')

@section('title')
        @if($createClubEvent)
        	{{ trans('mainLang.createNewVEvent') }}
        @else
            {{ trans('mainLang.changeEventJob') }}
        @endif
@stop

@section('content')

@if(Session::has('userId'))
    @if($createClubEvent)
	    {!! Form::open(['method' => 'POST', 'route' => ['event.store']]) !!}
    @else
        {!! Form::open(['method' => 'PUT', 'route' => ['event.update', $event->id]]) !!}
    @endif

	<div class="row">
		<div class="panel col-md-6 col-sm-12 col-xs-12 no-padding">

			<div class="panel-heading">
				<h4 class="panel-title">{{ trans('mainLang.createNewEvent') }}:</h4>
			</div>

			<br>

			<div class="panel-body no-padding">
                @if($createClubEvent)
				<div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
					<label for="templateSelector" class="control-label col-md-2 col-sm-2 col-xs-4">{{ trans('mainLang.template') }}: &nbsp;</label>
                    <div class="col-md-6 col-sm-6 col-xs-8">
                        <select id="templateSelector" class="selectpicker" data-live-search="true">
                            <option value="-1" ></option>
                            @foreach($templates as $template)
                                <option value="{{ Request::getBasePath() }}/event/{{ substr($date, 6, 4) }}/{{ substr($date, 3, 2) }}/{{ substr($date, 0, 2) }}/{{ $template->id }}/create"
                                @if($template->id == $templateId )
                                    selected
                                @endif
                                >{{  $template->title }}</option>
                            @endforeach
                        </select>
                    </div>
			   	</div>
                @endif
			   	<div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
			   		<span class="col-md-1 col-sm-1 col-xs-1">&nbsp;</span>
					{!! Form::checkbox('saveAsTemplate', '1', false, array('class'=>'col-md-1 col-sm-1 col-xs-1')) !!}
					<span class="col-md-9 col-sm-9 col-xs-9">{{ trans('mainLang.templateNewSaveQ') }}</span>
			   	</div>

				<div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
			      	<label for="title" class="col-md-2 col-sm-2 col-xs-3">{{ trans('mainLang.title') }}:</label>
		      		@if(is_null($title))
			      		{!! Form::text( 'title', '', array('placeholder'=>Lang::get('mainLang.placeholderTitleWineEvening'),
			      										   'style'=>'cursor: auto',
			      										   'class'=>'col-md-9 col-sm-9 col-xs-8',
			      										   'required') ) !!}
			      	@else
			      		{!! Form::text( 'title', $title, array('placeholder'=>Lang::get('mainLang.placeholderTitleWineEvening'),
		      										   'style'=>'cursor: auto',
		      										   'class'=>'col-md-9 col-sm-9 col-xs-8',
		      										   'required') ) !!}
		      		@endif
			    </div>

			    <div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
					<label for="subtitle" class="col-md-2 col-sm-2 col-xs-3">{{ trans('mainLang.subTitle') }}:</label>
					@if(is_null($subtitle))
						{!! Form::text('subtitle', '', array('placeholder'=>Lang::get('mainLang.placeholderSubTitleWineEvening'),
															 'class'=>'col-md-9 col-sm-9 col-xs-8',
			      										     'style'=>'cursor: auto') ) !!}
			      	@else
			      		{!! Form::text('subtitle', $subtitle, array('placeholder'=>Lang::get('mainLang.placeholderSubTitleWineEvening'),
															 'class'=>'col-md-9 col-sm-9 col-xs-8',
			      										     'style'=>'cursor: auto') ) !!}
			      	@endif
			    </div>

			    @if(Session::get('userGroup') == 'marketing' OR Session::get('userGroup') == 'clubleitung' OR Session::get('userGroup') == 'admin')
					<div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
						<label for="facebookDone" class="col-md-4 col-sm-4 col-xs-7">{{trans('mainLang.faceDone')}}?</label>
                        <select class="selectpicker" name="facebookDone" id="facebookDone">
                            <option value="-1" @if(!$facebookNeeded) selected @endif> {{ trans('mainLang.=FREI=') }} </option>
                            <option value="0" @if($facebookNeeded) selected @endif > {{ trans('mainLang.no') }} </option>
                            <option value="1"> {{ trans('mainLang.yes') }} </option>
                        </select>
					</div>
					<div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
						<label for="eventUrl" class="col-md-3 col-sm-3 col-xs-5">{{trans('mainLang.eventUrl')}}:</label>
						{!! Form::text('eventUrl', '', array('class'=>'col-md-8 col-sm-8 col-xs-6','style'=>'cursor: auto')) !!}
					</div>
				    <div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
				     	<label for="evnt_type" class="col-md-2 col-sm-2 col-xs-2">Typ:</label>
				     	<div class="col-md-10 col-sm-10 col-xs-10">
				            {!! Form::radio('evnt_type', "0", array("checked")) !!}
							{{ trans('mainLang.normalProgramm') }}
				            <br>
				            {!! Form::radio('evnt_type', "2", $type == 2 ? array("checked") : "") !!}
							{{ trans('mainLang.special') }}
				            <br>
				            {!! Form::radio('evnt_type', "3", $type == 3 ? array("checked") : "") !!}
							{{ trans('mainLang.LiveBandDJ') }}
				            <br>
				            {!! Form::radio('evnt_type', "5", $type == 5 ? array("checked") : "") !!}
							{{ trans('mainLang.utilization') }}
				            <br>
				            {!! Form::radio('evnt_type', "4", $type == 4 ? array("checked") : "") !!}
							{{ trans('mainLang.internalEvent') }}
				            <br>
				            {!! Form::radio('evnt_type', "6", $type == 6 ? array("checked") : "") !!}
							{{ trans('mainLang.flooding') }}
				            <br>
				            {!! Form::radio('evnt_type', "7", $type == 7 ? array("checked") : "") !!}
							{{ trans('mainLang.flyersPlacard') }}
				            <br>
				            {!! Form::radio('evnt_type', "8", $type == 8 ? array("checked") : "") !!}
							{{ trans('mainLang.preSale') }}
				            <br>
				            {!! Form::radio('evnt_type', "9", $type == 9 ? array("checked") : "") !!}
							{{ trans('mainLang.others') }}
				            <br>
				            {!! Form::radio('evnt_type', "1", $type == 1 ? array("checked") : "") !!}
							{{ trans('mainLang.information') }}
				            <br>
				            <div>
								{!! Form::checkbox('isPrivate', '1', $private ? false : true) !!}
								{{ trans('mainLang.showExtern') }}
							</div>
				            <br>

						</div>
				    </div>
				@else
					<div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
				     	<label for="evnt_type" class="control-label col-md-2 col-sm-2 col-xs-2">Typ:</label>
				     	<div class="col-md-10 col-sm-10 col-xs-10">
				            &nbsp;<i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;
							{{ trans('mainLang.normalProgramm') }}
				            <br>
				            &nbsp;<i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;
							{{ trans('mainLang.special') }}
				            <br>
				            &nbsp;<i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;
							{{ trans('mainLang.LiveBandDJ') }}
				            <br>
				            &nbsp;<i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;
							{{ trans('mainLang.utilization') }}
				            <br>
				            {!! Form::radio('evnt_type', "4", array("checked")) !!}
							{{ trans('mainLang.internalEvent') }}
				            <br>
				            &nbsp;<i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;
							{{ trans('mainLang.flooding') }}
				            <br>
				            &nbsp;<i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;
							{{ trans('mainLang.flyersPlacard') }}
				            <br>
				            &nbsp;<i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;
							{{ trans('mainLang.preSale') }}
				            <br>
				            {!! Form::radio('evnt_type', "9", $type == 9 ? array("checked") : "") !!}
							{{ trans('mainLang.others') }}
				            <br>
				            {!! Form::radio('evnt_type', "1", $type == 1 ? array("checked") : "") !!}
							{{ trans('mainLang.information') }}
				            <br>
							&nbsp;<i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;&nbsp;
							{{ trans('mainLang.survey') }}
							<br>

				            <br>
				            <div>
				            	{!! Form::checkbox('isPrivate', '1', false, array('hidden')) !!}
								<span style="color: red;">
									<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
									{{ trans('mainLang.showForLoggedInMember') }}<br>
									{{ trans('mainLang.showForExternOrChangeType') }} <br>
									{{ trans('mainLang.askTheCLOrMM') }}
								</span>
				            </div>
						</div>
				    </div>
				@endif

				<div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
					<label for="section" class="control-label col-md-2 col-sm-2 col-xs-12">{{ trans('mainLang.section') }}: &nbsp;</label>
					<span class="col-md-10 col-sm-10 col-xs-12">
						@if (!is_null($section))
							{!! Form::text('section', $section->title, array('id'=>'section', 'readonly') ) !!}
						@elseif(!is_null(Session::get('userClub')))
							{!! Form::text('section', Session::get('userClub'), array('id'=>'section', 'readonly')) !!}
						@endif
					 	<a class="btn-small btn-primary dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);">
					        <span class="caret"></span>
					    </a>
					    <ul class="dropdown-menu">
						    @foreach($sections as $section)
						        <li>
						        	<a href="javascript:void(0);"
						        	   onClick="document.getElementById('section').value='{{$section}}'">{{ $section }}</a>
						        </li>
							@endforeach
					    </ul>
				    </span>
			   	</div>

			   	<div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding" id="filter-checkboxes">
					<label for="filter" class="control-label col-md-2 col-sm-2 col-xs-12">{{ trans('mainLang.showFor') }}: &nbsp;</label>
					<div class="col-md-10 col-sm-10 col-xs-12">
						@if(is_null($filter) OR $filter == "")
							{{-- Set default values to the club the user is a member in.--}}
							@foreach(Lara\Section::all() as $section)
                                {{ Form::checkbox("filter[" . $section->title ."]", $section->id, $section->title === Session::get("userClub")) }}
									{{ $section->title }}
                                	&nbsp;
							@endforeach
						@else
							@foreach(Lara\Section::all() as $section)
								{{ Form::checkbox("filter[" . $section->title ."]", $section->id, in_array($section->title, $filter)) }}
								{{ $section->title }}
								&nbsp;
							@endforeach
						@endif
				    </div>
			   	</div>

				<div class="form-group col-md-12 col-sm-12 col-xs-12" id="filter-checkboxes">
					<label for="priceTickets" class="control-label col-md-4 col-sm-4 col-xs-12 no-padding">
						{{ trans('mainLang.priceTickets') }}:  <br>
						({{ trans('mainLang.studentExtern') }})</label>
					<div id="priceTickets" class="input-group col-md-5 col-sm-5 col-xs-12">
						<input class="form-control" type="number" name="priceTicketsNormal" step="0.1" placeholder="Student" value=""/>
						<span class="input-group-addon">€</span>
						<span class="input-group-addon">/</span>
						<input class="form-control" type="number" name="priceTicketsExternal" step="0.1" placeholder="Extern" value=""/>
						<span class="input-group-addon">€</span>
					</div>
				</div>

				<div class="form-group col-md-12 col-sm-12 col-xs-12" id="filter-checkboxes">
					<label for="price" class="control-label col-md-4 col-sm-4 col-xs-12 no-padding">
						{{ trans('mainLang.price') }}:   <br>
						({{ trans('mainLang.studentExtern') }})</label>
					<div id="price" class="input-group col-md-5 col-sm-5 col-xs-12">
						<input class="form-control" type="number" name="priceNormal" step="0.1" placeholder="Student" value=""/>
						<span class="input-group-addon">€</span>
						<span class="input-group-addon">/</span>
						<input class="form-control" type="number" name="priceExternal" step="0.1" placeholder="Extern" value=""/>
						<span class="input-group-addon">€</span>
					</div>
				</div>

			    <div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
					<label for="preparationTime" class="control-label col-md-2 col-sm-2 col-xs-4">{{ trans('mainLang.DV-Time') }}:</label>
					<div class="col-md-3 col-sm-3 col-xs-3">
						<span class="hidden-xs">&nbsp;&nbsp;</span>
						{!! Form::input('time', 'preparationTime', $dv) !!}
					</div>
			    </div>

			    <div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
					<label for="beginDate" class="control-label col-md-2 col-sm-2 col-xs-12">{{ trans('mainLang.begin') }}:</label>
					<div class="col-md-10 col-sm-10 col-xs-12">
						{!! Form::input('date', 'beginDate', date("Y-m-d", strtotime($date))) !!}
						{{ trans('mainLang.um') }} {!! Form::input('time', 'beginTime', $timeStart) !!}
					</div>
			    </div>

				<div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
					<label for="endDate" class="control-label col-md-2 col-sm-2 col-xs-12">{{ trans('mainLang.end') }}:</label>
					<div class="col-md-10 col-sm-10 col-xs-12">
						{!! Form::input('date', 'endDate', date("Y-m-d", strtotime($timeStart) < strtotime($timeEnd) ? strtotime($date) : strtotime("+1 day", strtotime($date)))) !!}
						{{ trans('mainLang.um') }} {!! Form::input('time', 'endTime', $timeEnd) !!}
					</div>
			    </div>

			    <div class="col-md-12 col-sm-12 col-xs-12">
			    	&nbsp;
			    </div>

			    <div class="form-group col-md-12 col-sm-12 col-xs-12 no-padding">
			    	<label for="password" class="control-label col-md-5 col-sm-5 col-xs-12">{{ trans('mainLang.passwordEntry') }}:</label>
			    	<div class="col-md-7 col-sm-7 col-xs-12">
			    		{!! Form::password('password', [''] ) !!}
			    	</div>
			    </div>

			    <div class="form-groupcol-md-12 col-sm-12 col-xs-12 no-padding">
			    	<label fro="passwordDouble" class="control-label col-md-5 col-sm-5 col-xs-12">{{ trans('mainLang.passwordRepeat') }}:</label>
			    	<div class="col-md-7 col-sm-7 col-xs-12">
			    		{!! Form::password('passwordDouble', ['']) !!}
			    	</div>
			    </div>

			    <div class="col-md-12 col-sm-12 col-xs-12">
			    	&nbsp;
			    </div>

		    </div>
		</div>

		<div class="container col-xs-12 col-sm-12 col-md-6 no-padding-xs">
			<br class="visible-xs visible-sm">
			<div class="panel">
				<div class="panel-heading">
					<h4 class="panel-title">{{ trans('mainLang.moreInfos') }}:</h4>({{ trans('mainLang.public') }})
				</div>
				<div class="panel-body">
				    <div class="form-group">
						<div class="col-md-12">
							@if(is_null($info))
								{!! Form::textarea('publicInfo', '', array('class'=>'form-control',
																		  'rows'=>'8',
																		  'placeholder'=>Lang::get('mainLang.placeholderPublicInfo')) ) !!}
							@else
								{!! Form::textarea('publicInfo', $info, array('class'=>'form-control',
																	  'rows'=>'8',
																	  'placeholder'=>Lang::get('mainLang.placeholderPublicInfo')) ) !!}
							@endif
						</div>
					</div>
				</div>
			</div>

			<br>

			<div class="panel">
				<div class="panel-heading">
					<h4 class="panel-title">{{ trans('mainLang.details') }}:</h4>({{ trans('mainLang.showOnlyIntern') }})
				</div>
				<div class="panel-body">
				    <div class="form-group">
						<div class="col-md-12">
							@if(is_null($details))
								{!! Form::textarea('privateDetails', '', array('class'=>'form-control',
																			  'rows'=>'5',
																			  'placeholder'=>Lang::get('mainLang.placeholderPrivateDetails')) ) !!}
							@else
								{!! Form::textarea('privateDetails', $details, array('class'=>'form-control',
																		  'rows'=>'5',
																		  'placeholder'=>Lang::get('mainLang.placeholderPrivateDetails')) ) !!}
							@endif
						</div>
				    </div>
				</div>
			</div>
		</div>
	</div>

	<br>

	<div class="row">
		@include('partials.editSchedule')
		<br>
		{!! Form::submit('Veranstaltung mit Dienstplan erstellen', array('class'=>'hidden', 'id'=>'button-create-submit')) !!}
	    <input class="hidden" name="evntIsPublished" type="text" value="0" />

	    {{--

	    Disabling iCal until fully functional -> remove "Publish" button, rename "create unpublished" to just "create"


	    @if(Session::get('userGroup') == 'marketing'
	     OR Session::get('userGroup') == 'clubleitung'
	     OR Session::get('userGroup') == 'admin')
			<button class="btn btn-primary" id="createAndPublishBtn">{{trans('mainLang.createAndPublish')}}</button>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<br class="visible-xs">
	    @endif

	    --}}
        @if($createClubEvent)
		    <button class="btn btn-primary" id="createUnpublishedBtn">{{trans('mainLang.createNewEvent')}}</button>
        @else
            {!! Form::submit( trans('mainLang.update') , array('class'=>'btn btn-success', 'id'=>'button-edit-submit')) !!}
        @endif

		&nbsp;&nbsp;&nbsp;&nbsp;
		<br class="visible-xs">
		<br class="visible-xs">
		<a href="javascript:history.back()" class="btn btn-default">{{ trans('mainLang.backWithoutChange') }}</a>

		{!! Form::close() !!}

	</div>

@else
	@include('partials.accessDenied')
@endif

@stop



