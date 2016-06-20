<!-- useable variables:
$survey
$questions
$questionCount
$answers
$clubs
$userId
$userGroup
$userCanEditDueToRole
--!>
@extends('layouts.master')
@section('title')
    {{$survey->title}}
@stop
@section('moreStylesheets')
    <link rel="stylesheet" media="all" type="text/css" href="{{ asset('/css/surveyViewStyles.css') }}"/>
    <script src="js/surveyView-scripts.js"></script>
@stop
@section('moreScripts')
    <script src="{{ asset('js/surveyView-scripts.js') }}"></script>
@stop
@section('content')


    <div class="panel no-padding">
        <div class="panel-title-box">
            <h4 class="panel-title">
                {{ $survey->title }}
            </h4>
        </div>
        <div class="panel-body">
            Beschreibung:
            @if($survey->description == null)
                keine Beschreibung vorhanden
            @else
                {{$survey->description }}
            @endif
            <br>
            {!! $survey->description!!}
            Die Umfrage läuft noch bis: {{ strftime("%a, %d %b", strtotime($survey->deadline)) }} um
            {{ date("H:i", strtotime($survey->deadline)) }}
        </div>
    </div>

    <br>
    <br>




    <!-- /////////////////////////////////////////// Start of desktop View /////////////////////////////////////////// -->


    <!--
Calculate width of row in answers
-->
    <?php
    $tableNot100Percent = false;

    /* columnWidth = width of answerToQuestion */
    $columnWidth = 20;
    /* number of columns * width */
    $questionCount *= $columnWidth;
    /* Club Column is added as a default + width for edid, save, delete buttons */
    $questionCount += 2 * $columnWidth;
    $alternatingColor = 0;
    ?>
            <!--table min 100% width of page-->
    @if($questionCount < 100)
    <?php $tableNot100Percent = true ?>
    @endif

    <div class="panel displayDesktop">
        {!! Form::open(['action' => ['SurveyAnswerController@store', $survey->id]]) !!}
        <div class="row rowNoPadding">
            <div class="col-md-2 rowNoPadding shadow">
                <div class=" rowNoPadding nameToQuestion">
                    Name *
                </div>
                <div class=" rowNoPadding nameToQuestion">
                    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Bitte gib deinen Namen ein', 'required' => true, 'oninvalid' => 'setCustomValidity(\'Bitte gib deinen Namen ein\')', 'oninput' => 'setCustomValidity(\'\')']) !!}
                </div>
                <?php $countNames = 0 ?>
                @foreach($answers as $answer)
                    <?php $countNames += 1 ?>
                    <div class="Name{{$countNames}} rowNoPadding nameToQuestion color{{$alternatingColor}}">
                        {{$answer->name}}
                    </div>
                    <?php $alternatingColor == 0 ? $alternatingColor = 1 : $alternatingColor = 0; ?>
                @endforeach
            </div>
            <?php
            $alternatingColor = 0;
            ?>
            <div class="col-md-10 answers rowNoPadding">
                <div id="rightPart" style="width: {{$questionCount}}vw;" class="displayDesktop">
                    <div class="rowNoPadding ">
                        <div class="answerToQuestion">
                            Club
                        </div>
                        @foreach($questions as $question)
                            <div class="answerToQuestion">
                                {{$question->question}}
                                @if($question->is_required == 1)
                                    *
                                @endif
                            </div>
                        @endforeach
                        <div class="answerToQuestion ">
                        </div>
                    </div>
                    <div class="rowNoPadding">
                        <div class="answerToQuestion">
                            <select class="form-control" id="sel1" name="club">
                                <option>kein Club</option>
                                @foreach($clubs as $club)
                                    <option>{{$club->clb_title}}</option>
                                @endforeach
                            </select>
                        </div>
                        @foreach($questions as $key => $question)
                            <div class="answerToQuestion">
                                @if($question->field_type == 1)
                                <!-- Freitext -->
                                    @if(!$question->is_required)
                                    <!--Answer not required-->
                                        {!! Form::text('answers['.$key.']', null, ['rows' => 2, 'class' => 'form-control', 'placeholder' => 'Antwort hier hinzufügen']) !!}
                                    @else
                                    <!--Answer* required-->
                                        {!! Form::text('answers['.$key.']', null, ['required' => 'true', 'rows' => 2, 'class' => 'form-control', 'placeholder' => 'Antwort hier hinzufügen', 'oninvalid' => 'setCustomValidity(\'Bitte gib eine Antwort\')', 'oninput' => 'setCustomValidity(\'\')']) !!}
                                    @endif
                                @elseif($question->field_type == 2)
                                <!-- Ja/Nein -->
                                    {{ Form::radio('answers['.$key.']', 1) }} Ja
                                    @if(!$question->is_required)
                                    <!--Answer not required-->
                                        {{ Form::radio('answers['.$key.']', 0) }} Nein
                                        {{ Form::radio('answers['.$key.']', -1, true)}} keine Angabe
                                    @else
                                    <!--Answer* required-->
                                        {{ Form::radio('answers['.$key.']', 0, true) }} Nein
                                    @endif
                                @elseif($question->field_type == 3)
                                <!-- Dropdown -->
                                        <?php
                                        $answerOptionsDatabase = $question->getAnswerOptions;
                                        $answerOptions = [];
                                        foreach($answerOptionsDatabase as $answerOptionDatabase) {
                                            $answerOptions[$answerOptionDatabase->answer_option] = $answerOptionDatabase->answer_option;
                                        }
                                        if(!$question->is_required){
                                            $answerOptions[-1] = "keine Angabe";
                                        }
                                    ?>
                                    @if(!$question->is_required)
                                    <!--Answer not required-->
                                        {{ Form::select('answers['.$key.']', $answerOptions, -1) }}
                                    @else
                                    <!--Answer* required-->
                                        {{ Form::select('answers['.$key.']', $answerOptions) }}
                                    @endif
                                @endif
                            </div>
                            @endforeach
                                    <!-- Antwort speichern desktop -->
                            <div class="answerToQuestion ">
                                {!! Form::submit('Speichern', ['class' => 'btn btn-primary btn-margin', 'style' => 'display: inline-block;']) !!}
                                {!! Form::close() !!}
                            </div>
                    </div>
                    <?php $countAnswersRow = 0; ?>
                    @foreach($answers as $key => $answer)
                        <?php $countAnswersRow += 1; ?>
                        <div class="rowNoPadding">
                            <!--Color 1-->
                            <div class="answerToQuestion color{{$alternatingColor}}">
                                @if($club = $clubs->find($answer->club_id))
                                    {{$club->clb_title}}
                                @else
                                    kein Club
                                @endif
                            </div>
                            @foreach($answer->getAnswerCells as $cell)
                                <div class="answerToQuestion color{{$alternatingColor}} answerRow<?php echo $countAnswersRow; ?>">
                                    {{$cell->answer}}
                                </div>
                                @endforeach
                                @if($userId == $answer->creator_id OR $userCanEditDueToRole)
                                        <!--Edid Delete Buttons-->
                                <div class="marginLeft15 answerToQuestion color{{$alternatingColor}} editDelete">
                                    <a
                                       class="editButton btn btn-primary editRow<?php echo $countAnswersRow; ?>"
                                       data-toggle="tooltip"
                                       data-placement="bottom">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a href="{{$survey->id}}/answer/{{$answer->id}}"
                                       class="btn btn-default deleteRow<?php echo $countAnswersRow; ?>"
                                       data-toggle="tooltip"
                                       data-placement="bottom"
                                       data-method="delete"
                                       data-token="{{csrf_token()}}"
                                       rel="nofollow"
                                       data-confirm="Möchtest Du diese Antwort wirklich löschen?">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>
                                @else
                                    <div class="answerToQuestion ">
                                    </div>
                                @endif
                                <?php $alternatingColor == 0 ? $alternatingColor = 1 : $alternatingColor = 0; ?>
                        </div>
                    @endforeach
                </div>
                @if($tableNot100Percent )
                    <div class="ifTableNotfullPage">&nbsp;</div>
                    <div class="ifTableNotfullPage">&nbsp;</div>
                    @foreach($answers as $answer)
                        <div class="ifTableNotfullPage">&nbsp;</div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    <div class="displayDesktop">
    </div>


    <script>
        $(document).ready(function () {
            $('#surveyAnswerForm').formValidation();
        });
        $(document).ready(function () {
            $('#surveyAnswerFormMobile').formValidation();
        });
    </script>

@stop