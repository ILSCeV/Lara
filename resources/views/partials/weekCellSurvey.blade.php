@if(!Auth::user() && $survey->is_private === 1)
    {{-- Hide internal surveys from guests --}}
    <div class="card border-warning word-break section-filter section-survey">

        <div class="palette-Grey-500 bg text-white"
             style="padding: 15px 15px 8px 15px;">
            <h4 class="card-title">
                <i class="fas fa-chart-bar text-white"></i>
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
    <div class="card border-warning word-break section-filter section-survey">

        <div class="card-header palette-Purple-900 bg text-white">
            <h4 class="card-title">
                <a class="text-white" href="{{ URL::route('survey.show', $survey->id) }}">
                    <i class="fas fa-chart-bar text-white"></i>
                    &nbsp;
                    <span class="name">{{ $survey->title }}</span>
                </a>
            </h4>
            <i class="fas fa-times" aria-hidden="true"></i>
            {{ utf8_encode(strftime("%a, %d. %b", strtotime($survey->deadline))) }}
        </div>

        <div class="card-body no-padding">
            {{-- gives a session from privileged users the option to hide the event--}}
            @is('marketing', 'clubleitung', 'admin')

                <hr class="col-md-12 col-xs-12">
                <div class="float-right hidden-print">
                    <small><a href="#" class="hide-event">{{ trans('mainLang.hide') }}</a></small>
                </div>

            @endis
        </div>
    </div>
@endif
