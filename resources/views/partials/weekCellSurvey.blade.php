@if(!Auth::user() && $survey->is_private === 1)
    {{-- Hide internal surveys from guests --}}
    <div class="card bg-warning word-break section-filter section-survey">

        <div class="card palette-Grey-500 bg white-text"
             style="padding: 15px 15px 8px 15px;">
            <h4 class="card-title">
                <i class="fa fa-bar-chart-o white-text"></i>
                &nbsp;
                <span class="name">{{ trans('mainLang.internalSurvey') }}</span>
            </h4>
            <i class="fa fa-times" aria-hidden="true"></i>
            {{ utf8_encode(strftime("%a, %d. %b", strtotime($survey->deadline))) }}
        </div>

        <div class="card card-body no-padding">
        </div>
    </div>
@else
    {{-- Show everything to memebers --}}
    <div class="card bg-warning word-break section-filter section-survey">

        <div class="card card-header palette-Purple-900 bg white-text">
            <h4 class="card-title">
                <a href="{{ URL::route('survey.show', $survey->id) }}">
                    <i class="fa fa-bar-chart-o white-text"></i>
                    &nbsp;
                    <span class="name">{{ $survey->title }}</span>
                </a>
            </h4>
            <i class="fa fa-times" aria-hidden="true"></i>
            {{ utf8_encode(strftime("%a, %d. %b", strtotime($survey->deadline))) }}
        </div>

        <div class="card card-body no-padding">
            {{-- gives a session from privileged users the option to hide the event--}}
            @is('marketing', 'clubleitung', 'admin')

                <hr class="col-md-12 col-xs-12 top-padding no-margin no-padding">
                <div class="padding-right-16 bottom-padding float-right hidden-print">
                    <small><a href="#" class="hide-event">{{ trans('mainLang.hide') }}</a></small>
                </div>

            @endis
        </div>
    </div>
@endif
