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
    $numberQuestions = 0;
    $userCanEditOrDeleteAnswer = false;
    $userGroup == 'admin' OR $userGroup == 'marketing' OR $userGroup == 'clubleitung' ? $userCanEditDueToRole = true : $userCanEditDueToRole = false;
    $tableNot100Percent = false;
    ?>
    @foreach($answers as $answer)
    @foreach($answer->getAnswerCells as $cell)
    @if($answer->creator_id == $userId or $userGroup == 'admin' or  $userGroup == 'marketing' or $userGroup == 'clubleitung')
            <!--if creator of one of the the questions is currently loged in, display edit / delete buttons -->
    <?php $userCanEditOrDeleteAnswer = true; ?>
    @endif
    @endforeach
            <!--
        also add delete / edit buttons at the end of the line
    -->
    @endforeach
    @foreach($questions as $question)
    <?php $numberQuestions = $numberQuestions + 1;  ?>
    @endforeach
    <?php
    /* columnWidth = width of answerToQuestion */
    $columnWidth = 20;
    /* number of columns * width */
    $numberQuestions *= $columnWidth;
    /* Club Column is added as a default + width for edid, save, delete buttons */
    $numberQuestions += 2 * $columnWidth;
    $alternatingColor = 0;
    ?>
            <!--table min 100% width of page-->
    @if($numberQuestions < 100)
    <?php $tableNot100Percent = true ?>
    @endif
            <!--

    userID: {{$userId}}
            <br>
            @foreach($answers as $answer)
            answersUSerID: {{$answer->creator_id}}
    @endforeach
            <br>
            userGroup: {{$userGroup}}<br>
            questions: {{$questions}}
            -->




    <div class="displayDesktop">
        {!! Form::open(['action' => ['SurveyAnswerController@store', $survey->id]]) !!}
        <div class="panel panel-warning">
        @if( $survey->password != '')
            <div class="hidden-print panel-heading">
                {!! Form::password('password', array('required',
                                                     'class'=>'col-md-4 col-xs-12 black-text',
                                                     'id'=>'password' . $survey->id,
                                                     'placeholder'=>'Passwort hier eingeben')) !!}
                <br>
            </div>
        @endif
            <div class="panel-body">
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
                    <div class="Name<?php echo $countNames; ?> rowNoPadding nameToQuestion color{{$alternatingColor}}">
                        {{$answer->name}}
                    </div>
                    <?php $alternatingColor == 0 ? $alternatingColor = 1 : $alternatingColor = 0; ?>
                @endforeach
            </div>
            <?php
            $alternatingColor = 0;
            ?>
            <div class="col-md-10 answers rowNoPadding">
                <div id="rightPart" style="width: {{$numberQuestions}}vw;" class="displayDesktop">
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
                                @foreach($clubs as $club)
                                    <option>{{$club->clb_title}}</option>
                                @endforeach
                            </select>
                        </div>
                        @foreach($questions as $question)
                            <div class="answerToQuestion">
                                @if($question->is_required == 0)
                                        <!--Answer not required-->
                                {!! Form::text('answers[]', null, ['rows' => 2, 'class' => 'form-control', 'placeholder' => 'Antwort hier hinzufügen']) !!}
                                @else
                                        <!--Answer* required-->
                                {!! Form::text('answers[]', null, ['required' => 'true', 'rows' => 2, 'class' => 'form-control', 'placeholder' => 'Antwort hier hinzufügen', 'oninvalid' => 'setCustomValidity(\'Bitte gib eine Antwort\')', 'oninput' => 'setCustomValidity(\'\')']) !!}
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
                    @foreach($answers as $answer)
                        <?php $countAnswersRow += 1; ?>
                        <div class="rowNoPadding">
                            <!--Color 1-->
                            <div class="answerToQuestion color{{$alternatingColor}}">
                                @if($club = $clubs->find($answer->club_id))
                                    club: {{$club->clb_title}}
                                @else
                                    club: kein Club
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
    </div>
    <div class="displayDesktop">
    </div>
    </div>






    <!-- /////////////////////////////////////////// Start of mobile View /////////////////////////////////////////// -->



    <?php
    $alternatingColor = 0;
    ?>
    <div class="panel displayMobile">
        {!! Form::open() !!}
        <div class="panel-body">
            <h4 class="panel-title">
                mobile
            </h4>
            @foreach($answers as $answer)
                    <!--First Line-->
            <label for="name">Name *</label>
            <br>
            {!! Form::text('answer[]', null, ['class' => 'form-control', 'placeholder' => 'Bitte gib deinen Namen ein', 'required' => true, 'oninvalid' => 'setCustomValidity(\'Bitte gib deinen Namen ein\')', 'oninput' => 'setCustomValidity(\'\')']) !!}
            <br>
            <label for="organization">Verein</label>
            <br>
            <label for="sel1">Select list:</label>
            <select class="form-control" id="sel1">
                @foreach($clubs as $club)
                    <option>{{$club->clb_title}}</option>
                @endforeach
            </select>
            @foreach($questions as $question)
                <br>
                @if($question->is_required == 0)
                        <!--Answer not required-->
                <label for="answer">{{$question->question}}</label>
                {!! Form::text('answer[]', null, ['rows' => 2, 'class' => 'form-control', 'placeholder' => 'Antwort hier hinzufügen']) !!}
                @else
                        <!--Answer* required-->
                <label for="answer">{{$question->question}} *</label>
                {!! Form::text('answer[]', null, ['required' => 'true', 'rows' => 2, 'class' => 'form-control', 'placeholder' => 'Antwort hier hinzufügen', 'oninvalid' => 'setCustomValidity(\'Bitte gib eine Antwort\')', 'oninput' => 'setCustomValidity(\'\')']) !!}
                @endif
            @endforeach
            <div class="line"></div>
            <!--All other Answers / Lines-->
            Name: {{$answer->name}}
            <br>
            Verein:
            @if($club = $clubs->find($answer->club_id))
                club: {{$club->clb_title}}
            @else
                club: kein Club
            @endif
            @foreach($answer->getAnswerCells as $cell)
                <br>
                Antwort:    {{$cell->answer}}
                @endforeach
                @if($userCanEditOrDeleteAnswer)
                        <!--Edid Delete Buttons-->



                <br>
                <a href="#"
                   class="btn btn-primary"
                   data-toggle="tooltip"
                   data-placement="bottom"
                   title="Veranstaltung ändern">
                    <i class="fa fa-pencil"></i>
                </a>
                &nbsp;&nbsp;
                <a href="#"
                   class="btn btn-default"
                   data-toggle="tooltip"
                   data-placement="bottom"
                   title="Veranstaltung löschen"
                   data-method="delete"
                   data-token="{{csrf_token()}}"
                   rel="nofollow"
                   data-confirm="Möchtest Du diese Antwort wirklich löschen?">
                    <i class="fa fa-trash"></i>
                </a>
                <br>
                <br>
                @endif
                <div class="line"></div>
            @endforeach
        </div>
        <!--Speichern mobile -->
        {!! Form::submit('Speichern', ['class' => 'btn btn-primary btn-margin']) !!}
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
        {!! Form::close() !!}
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