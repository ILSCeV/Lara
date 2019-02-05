@foreach($surveys as $survey)
    @php
        $elementClass = 'element-item section-filter section-survey flex-grow-1 ';

    if ( date('W', strtotime($survey->deadline)) === $date['week']
     &&  date('N', strtotime($survey->deadline)) < 3 ) {
        $elementClass .= "week-mo-so ";
        }
    elseif ( date("W", strtotime($survey->deadline) ) === date("W", strtotime("next Week".$weekStart))
     &&      date('N', strtotime($survey->deadline)) < 3 ){
        $elementClass.= "week-mi-di hide";
        }
    @endphp
    <div class="p-2 mb-3 {{ $elementClass }}">
        @include('partials.weekCellSurvey')
    </div>
@endforeach
