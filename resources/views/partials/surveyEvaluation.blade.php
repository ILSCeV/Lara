<div class="btn-group col-md-6">
    <div class="row">
        <br class="visible-xs visible-sm">
        <div class="panel">
            <div class="panel-heading">
                <h4 class="panel-title">Bisherige Abstimmungen:</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">

                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Antworten</th>
                        </tr>
                        </thead>
                        <tbody>
                        {{--@foreach($survey_answer as $survey_answerid)--}}
                            {{--@if($survey_answerid->survey_question_id == $survey->id)--}}
                            {{--<tr>--}}
                                {{--<td>{{ $survey_answerid->content }}</td>--}}
                            {{--</tr>--}}
                            {{--@endif--}}
                        {{--@endforeach--}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>