@extends('layouts.master')

@section('title')
        @if($createClubEvent)
        	{{ trans('mainLang.createNewVEvent') }}
        @else
            {{ trans('mainLang.changeEventJob') }}
        @endif
@stop
@section('moreScripts')
    <script src="{{asset(WebpackBuiltFiles::$jsFiles['autocomplete'])}}" ></script>
@endsection

@section('content')
    @php
        $labelClass = 'col-12 col-md-3';
        $inputClass = 'form-control col-12 col-md-8';
    @endphp

    <div class="row">
        <div class="card col p-0">
            @if($createClubEvent)
                {!! Form::open(['method' => 'POST', 'class'=>'form-inline','id'=>'templateSelectorForm']) !!}
                <div class="form-group col-12 p-0">
                    <label for="templateSelector"
                           class="col-form-label col-12 col-md-2">{{ trans('mainLang.template') }}:
                        &nbsp;</label>
                    <div class="col-12 col-md-6">
                        <select name="template" id="templateSelector" class="selectpicker" data-live-search="true">
                            <optgroup label="">
                                <option value="-1"></option>
                            </optgroup>
                            @foreach($sections as $tSection)
                                <optgroup label="{{ $tSection->title }}">
                                    @foreach( $templates->filter(function ($template) use ($tSection) { return $template->section_id == $tSection->id; }) as $template )
                                        <option
                                            value="{{ Request::getBasePath() }}/event/{{ substr($date, 6, 4) }}/{{ substr($date, 3, 2) }}/{{ substr($date, 0, 2) }}/{{ $template->id }}/create"
                                            @if($template->id == $templateId )
                                            selected
                                            @endif
                                            data-content="<span> {{  $template->title }} {{trans('mainLang.begin')}}: {{$template->time_start}}
                                            {{trans('mainLang.end')}}: {{$template->time_end}}  </span>"
                                            title="{{ $template->title }}"
                                        >{{  $template->title }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        <button class="d-none" type="submit"></button>
                    </div>
                </div>
                {!! Form::close() !!}
            @elseIf(isset($baseTemplate) && !is_null($baseTemplate))
                <div class="form-group col-12 p-0">
                    <label class="col-form-label col-12 col-md-2">{{ trans('mainLang.template') }}:
                        &nbsp;</label>
                    <div class="col-12 col-md-6">
                        <div class="row">
                            {{ $baseTemplate->title }}
                        </div>
                        <div class="row">
                            {{ trans('mainLang.begin') }}: {{ $baseTemplate->time_start }}
                        </div>
                        <div class="row">
                            {{ trans('mainLang.end') }}: {{$baseTemplate->time_end}}
                        </div>
                    </div>

                </div>
            @endif
        </div>
    </div>
    @if($createClubEvent)
	    {!! Form::open(['method' => 'POST', 'route' => ['event.store'], 'class' => 'form-inline']) !!}
        <input class="d-none" id="templateValue" name="template" />
    @else
        {!! Form::open(['method' => 'PUT', 'route' => ['event.update', $event->id], 'class' => 'form-inline']) !!}
    @endif

	<div class="row">
		<div class="card col-12 col-md-6 p-0">

			<div class="card-header ">
				<h4 class="card-title">
                    @if($createClubEvent)
                        {{ trans('mainLang.createNewEvent') }}
                    @else
                        {{ trans('mainLang.changeEventJob') }}
                    @endif
                    :
                </h4>
			</div>

			<br>

			<div class="card-body p-0">
			   	<div class="form-group col-12 ">
			   		<span class="col-2">&nbsp;</span>
					{!! Form::checkbox('saveAsTemplate', '1', false, array('class'=>'col-2 form-check')) !!}
					<span class="col-7">{{ trans('mainLang.templateNewSaveQ') }}</span>
			   	</div>
                <br>
				<div class="form-group col-12 ">
			      	<label for="title" class="{{ $labelClass }}">{{ trans('mainLang.title') }}:</label>
		      		@if(is_null($title))
			      		{!! Form::text( 'title', '', array('placeholder'=>Lang::get('mainLang.placeholderTitleWineEvening'),
			      										   'style'=>'cursor: auto',
			      										   'class'=>$inputClass,
			      										   'required') ) !!}
			      	@else
			      		{!! Form::text( 'title', $title, array('placeholder'=>Lang::get('mainLang.placeholderTitleWineEvening'),
		      										   'style'=>'cursor: auto',
		      										   'class'=>$inputClass,
		      										   'required') ) !!}
		      		@endif
			    </div>
                <br>
			    <div class="form-group col-12">
					<label for="subtitle" class="{{ $labelClass }}">{{ trans('mainLang.subTitle') }}:</label>
					@if(is_null($subtitle))
						{!! Form::text('subtitle', '', array('placeholder'=>Lang::get('mainLang.placeholderSubTitleWineEvening'),
															 'class'=>$inputClass,
			      										     'style'=>'cursor: auto') ) !!}
			      	@else
			      		{!! Form::text('subtitle', $subtitle, array('placeholder'=>Lang::get('mainLang.placeholderSubTitleWineEvening'),
															 'class'=>$inputClass,
			      										     'style'=>'cursor: auto') ) !!}
			      	@endif
			    </div>
                <br>
			    @is('marketing', 'clubleitung', 'admin')
					<div class="form-group col-12 p-0">
						<label for="facebookDone" class="col-4">{{trans('mainLang.faceDone')}}?</label>
                        <select class="selectpicker" name="facebookDone" id="facebookDone">
                            <option value="-1" @if($facebookNeeded === null) selected @endif> {{ trans('mainLang.=FREI=') }} </option>
                            <option value="0" @if($facebookNeeded !== null && $facebookNeeded === 0) selected @endif > {{ trans('mainLang.no') }} </option>
                            <option value="1" @if($facebookNeeded) selected @endif > {{ trans('mainLang.yes') }} </option>
                        </select>
					</div>
                    <br>
					<div class="form-group col-12">
						<label for="eventUrl" class="{{ $labelClass }}">{{trans('mainLang.eventUrl')}}:</label>
						{!! Form::text('eventUrl', $eventUrl, ['class'=>$inputClass,'style'=>'cursor: auto']) !!}
					</div>
                    <br>
				    <div class="form-group col-12 p-0">
                        <label for="evnt_type" class="col-3">{{ trans('mainLang.type') }}:</label>
                        <div class="col-8">
                            @for($i = 0;$i<12;$i++)
                                <div class="form-check float-left">
                                    <label >
                                        {{ Form::radio('type', $i, $type == $i) }}
                                        {{ \Lara\Utilities::getEventTypeTranslation($i) }}
                                    </label>
                                </div>
                                <br/>
                            @endfor
                        </div>
				    </div>
                <div class="clearfix"></div>
                <div class="form-group col-12 p-0 ">
                    <div class=" form-control form-check" >
                        <label for="evnt_private" class="form-check-label"> {{ trans('mainLang.showExtern') }} </label>
                    <label class="float-right pr-5">
                        {!! Form::checkbox('isPrivate', '1', ($private + 1)%2,['class'=>'form-check-input ', 'id'=> 'evnt_private']) !!}
                    </label>
                    </div>
                </div>
				@else
					<div class="form-group col-12 p-0">
				     	<label for="evnt_type" class="col-form-label {{$labelClass}}">Typ:</label>
				     	<div class="col-9">
				            &nbsp;<i class="fas fa-times-circle form-checked" aria-hidden="true"></i>&nbsp;&nbsp;
							{{ trans('mainLang.normalProgramm') }}
				            <br>
				            &nbsp;<i class="fas fa-times-circle form-checked" aria-hidden="true"></i>&nbsp;&nbsp;
							{{ trans('mainLang.special') }}
				            <br>
				            &nbsp;<i class="fas fa-times-circle form-checked" aria-hidden="true"></i>&nbsp;&nbsp;
							{{ trans('mainLang.LiveBandDJ') }}
				            <br>
				            &nbsp;<i class="fas fa-times-circle form-checked" aria-hidden="true"></i>&nbsp;&nbsp;
							{{ trans('mainLang.utilization') }}
				            <br>
                            &nbsp;{!! Form::radio('type', "4", ["checked"=>"checked" , "class"=>'form-check']) !!}
							{{ trans('mainLang.internalEvent') }}
				            <br>
				            &nbsp;<i class="fas fa-times-circle form-checked" aria-hidden="true"></i>&nbsp;&nbsp;
							{{ trans('mainLang.flooding') }}
				            <br>
				            &nbsp;<i class="fas fa-times-circle form-checked" aria-hidden="true"></i>&nbsp;&nbsp;
							{{ trans('mainLang.flyersPlacard') }}
				            <br>
				            &nbsp;<i class="fas fa-times-circle form-checked" aria-hidden="true"></i>&nbsp;&nbsp;
							{{ trans('mainLang.preSale') }}
				            <br>
                            &nbsp;{!! Form::radio('type', "9", [$type == 9 ? "checked" : "", "class"=>'form-check']) !!}
							{{ trans('mainLang.others') }}
				            <br>
                            &nbsp;<i class="fas fa-times-circle form-checked" aria-hidden="true"></i>&nbsp;&nbsp;
                            {{ trans('mainLang.outsideEvent') }}
                            <br>
                            &nbsp;<i class="fas fa-times-circle form-checked" aria-hidden="true"></i>&nbsp;&nbsp;
                            {{ trans('mainLang.buffet') }}
                            <br>
                            &nbsp;{!! Form::radio('type', "1", [$type == 1 ? "checked" : "", "class"=>'form-check'] ) !!}
							{{ trans('mainLang.information') }}
				            <br>
							&nbsp;<i class="fas fa-times-circle form-checked" aria-hidden="true"></i>&nbsp;&nbsp;
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
				@endis
                <div class="clearfix"></div>
				<div class="form-group col-12 p-0">
                    <label for="section" class="col-form-label col-3">{{ trans('mainLang.section') }}: &nbsp;</label>
                    <select id="section" class="selectpicker" name="section" >
                        @php
                            if(Auth::user()->isAn(\Lara\utilities\RoleUtility::PRIVILEGE_ADMINISTRATOR)){
                            $allowedSections = $sections;
                            }
                            else {
                            $allowedSections = Auth::user()->roles->filter(function (\Lara\Role $r){
                            return $r->name == \Lara\utilities\RoleUtility::PRIVILEGE_MARKETING || $r->name == \Lara\utilities\RoleUtility::PRIVILEGE_MEMBER;
                            })->map(function (\Lara\Role $r){
                            return $r->section;
                            });
                            }
                        @endphp
                        @foreach($allowedSections->unique()->sortBy('title') as $eventSection)
                            <option value="{{$eventSection->id}}"
                                    data-content="<span class='palette-{!! $eventSection->color !!}-900 bg'> {{$eventSection->title}} </span>"
                                    @if($eventSection->id == $section->id) selected @endif >{{ $eventSection->title }}</option>
                        @endforeach
                    </select>
			   	</div>
                <div class="clearfix"></div>
			   	<div class="form-group col-12 p-0" id="filter-checkboxes">
                    <label for="filter" class="col-form-label {{$labelClass}}">{{ trans('mainLang.showFor') }}: &nbsp;</label>
                    <div id="filter" class="input-group form-check {{$inputClass}}">

                        @if(isset($templateId) && $templateId == null && $createClubEvent)
                            @foreach($sections->sortBy('title') as $filterSection)
                                <label>
                                    {{ Form::checkbox("filter[" . $filterSection->title ."]", $filterSection->id, $filterSection->id === Auth::user()->section_id )}}
                                    {{ $filterSection->title }}
                                </label>
                            @endforeach
                        @else
                            @foreach($sections->sortBy('title') as $filterSection)
                                <label>
                                    {{ Form::checkbox("filter[" . $filterSection->title ."]", $filterSection->id, in_array( $filterSection->title, $filter)) }}
                                    {{ $filterSection->title }}
                                </label>
                            @endforeach
                        @endif
                    </div>
			   	</div>
                <br>
				<div class="form-group col-12" id="filter-checkboxes">
					<label for="priceTickets" class="col-form-label {{$labelClass}}">
						{{ trans('mainLang.priceTickets') }}:  <br>
						({{ trans('mainLang.studentExtern') }})</label>
					<div id="priceTickets" class="input-group col-8">
						<input class="form-control" type="number" name="priceTicketsNormal" step="0.1" min="0" placeholder="Student" value="{{$priceTicketsNormal}}"/>
						<span class="input-group-addon">€</span>
						<span class="input-group-addon">/</span>
						<input class="form-control" type="number" name="priceTicketsExternal" step="0.1" min="0" placeholder="Extern" value="{{$priceTicketsExternal}}"/>
						<span class="input-group-addon">€</span>
					</div>
				</div>

				<div class="form-group col-md-12 col-sm-12 col-12" id="filter-checkboxes">
					<label for="price" class="col-form-label {{$labelClass}}">
						{{ trans('mainLang.price') }}:   <br>
						({{ trans('mainLang.studentExtern') }})</label>
					<div id="price" class="input-group col-8">
						<input class="form-control" type="number" name="priceNormal" step="0.1" min="0" placeholder="Student" value="{{$priceNormal}}"/>
						<span class="input-group-addon">€</span>
						<span class="input-group-addon">/</span>
						<input class="form-control" type="number" name="priceExternal" step="0.1" min="0"  placeholder="Extern" value="{{$priceExternal}}"/>
						<span class="input-group-addon">€</span>
					</div>
				</div>

			    <div class="form-group col-12">
					<label for="preparationTime" class="col-form-label {{$labelClass}}">{{ trans('mainLang.DV-Time') }}:</label>
                    <div class="col-8">
					    {!! Form::input('time', 'preparationTime', $dv,['class'=>'form-control']) !!}
                    </div>
			    </div>
                <br>
			    <div class="form-group col-12">
					<label for="beginDate" class="col-form-label {{$labelClass}}">{{ trans('mainLang.begin') }}:</label>
					<div class="col-8">
						{!! Form::input('date', 'beginDate', date("Y-m-d", strtotime($date)),['class'=>'form-control']) !!}
						{{ trans('mainLang.um') }} {!! Form::input('time', 'beginTime', $timeStart,['class'=>'form-control']) !!}
					</div>
			    </div>
                <br>
				<div class="form-group col-12">
					<label for="endDate" class="col-form-label {{$labelClass}}">{{ trans('mainLang.end') }}:</label>
					<div class="col-8">
                        @if($createClubEvent)
						{!! Form::input('date', 'endDate', date("Y-m-d", strtotime($timeStart) < strtotime($timeEnd) ? strtotime($date) : strtotime("+1 day", strtotime($date))),['class'=>'form-control']) !!}
                        @else
                            {!! Form::input('date', 'endDate', $event->evnt_date_end,['class'=>'form-control']) !!}
                        @endif
						{{ trans('mainLang.um') }} {!! Form::input('time', 'endTime', $timeEnd,['class'=>'form-control']) !!}
					</div>
			    </div>

			    <div class="col-12">
			    	&nbsp;
			    </div>

			    <div class="form-group col-12">
			    	<label for="password" class="col-form-label {{$labelClass}}">{{ trans('mainLang.passwordEntry') }}:</label>
			    	<div class="col-8">
			    		{!! Form::password('password', ['class'=>'form-control','aria-describedby'=>"passwordHelpBlock"]) !!}
                        <small id="passwordHelpBlock" class="form-text text-muted">
                            {{ trans('mainLang.passwordDeleteMessage') }}
                        </small>
			    	</div>
			    </div>

			    <div class="form-group col-12 ">
			    	<label fro="passwordDouble" class="{{$labelClass}}">{{ trans('mainLang.passwordRepeat') }}:</label>
			    	<div class="col-8">
			    		{!! Form::password('passwordDouble', ['class'=>'form-control']) !!}
			    	</div>
			    </div>

			    <div class="col-md-12 col-sm-12 col-12">
			    	&nbsp;
			    </div>

		    </div>
		</div>

		<div class="container col p-0-xs">
			<br class="d-block d-sm-none d-none d-sm-block d-md-none">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">{{ trans('mainLang.moreInfos') }}:</h4>({{ trans('mainLang.public') }})
				</div>
				<div class="card-body">
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

			<div class="card">
				<div class="card-header">
					<h4 class="card-title">{{ trans('mainLang.details') }}:</h4>({{ trans('mainLang.showOnlyIntern') }})
				</div>
				<div class="card-body">
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

	<div class="row col-12">
        <div class="col-12 col-md-8">
		    @include('partials.editSchedule')
        </div>
    </div>
    <div class="row">
		{!! Form::submit('Veranstaltung mit Dienstplan erstellen', array('class'=>'d-none', 'id'=>'button-create-submit')) !!}
	    <input class="d-none" name="evntIsPublished" type="text" value="0" />

	    {{--

	    Disabling iCal until fully functional -> remove "Publish" button, rename "create unpublished" to just "create"


	    @is( 'marketing'
	     , 'clubleitung'
	     , 'admin')
			<button class="btn btn-primary" id="createAndPublishBtn">{{trans('mainLang.createAndPublish')}}</button>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<br class="d-block d-sm-none">
	    @endis

	    --}}
        @if($createClubEvent)
		    <button class="btn btn-primary" id="createUnpublishedBtn">{{trans('mainLang.createNewEvent')}}</button>
        @else
            {!! Form::submit( trans('mainLang.update') , array('class'=>'btn btn-success', 'id'=>'button-edit-submit')) !!}
        @endif

		&nbsp;&nbsp;&nbsp;&nbsp;
		<br class="d-block d-sm-none">
		<br class="d-block d-sm-none">
		<a href="javascript:history.back()" class="btn btn-secondary">{{ trans('mainLang.backWithoutChange') }}</a>

		{!! Form::close() !!}

	</div>
@stop
