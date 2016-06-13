@extends('layouts.master')
@section('title')
    {{$survey->title}}
@stop
@section('moreStylesheets')
    <link rel="stylesheet" media="all" type="text/css" href="{{ asset('/css/surveyViewStyles.css') }}"/>
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
    $numberQuestions = 0;
    $userCanEditOrDeleteAnswer = false;
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
    /* Club Column is added as a default */
    $numberQuestions += $columnWidth;
    $alternatingColor = 0;
    ?>
    @if($userCanEditOrDeleteAnswer == true)
    <?php $numberQuestions += $columnWidth ?>
    @endif
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

-->
    questions: {{$questions}}


    <br>
    <br>
    <br>
    {!! Form::open() !!}
    <div class=" form-group">
        {!! Form::label('name', 'Name:') !!}
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
    </div>
    {!! Form::close() !!}

    <div class="panel displayDesktop">
        {!! Form::open() !!}
        <div class="row rowNoPadding">
            <div class="col-md-2 rowNoPadding shadow">
                <div class=" rowNoPadding nameToQuestion">
                    Name *
                </div>
                <div class=" rowNoPadding nameToQuestion">
                    <!--
                    <input name="answer[]" type="text" placeholder="dein Name" class="form-control"
                           required="true"
                           oninvalid="this.setCustomValidity('Bitte gib deinen Namen ein')">
                           -->
                    {!! Form::text('answer[]', null, ['class' => 'form-control', 'placeholder' => 'Bitte gib deinen Namen ein', 'required' => true]) !!}
                </div>
                @foreach($answers as $answer)
                    @if($alternatingColor == 0)
                        <?php $alternatingColor = 1; ?>
                        <div class=" rowNoPadding nameToQuestion color1 ">
                            @else
                                <?php $alternatingColor = 0; ?>
                                <div class=" rowNoPadding nameToQuestion color2 ">
                                    @endif
                                    {{$answer->name}}
                                </div>
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
                                    @if($userCanEditOrDeleteAnswer)
                                        <div class="answerToQuestion ">
                                        </div>
                                    @endif
                                </div>
                                <div class="rowNoPadding">
                                    <div class="answerToQuestion">
                                        <select class="form-control" id="sel1">
                                            @foreach($clubs as $club)
                                                <option>{{$club->clb_title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @foreach($questions as $question)
                                        <div class="answerToQuestion">
                                            @if($question->is_required == 0)
                                                    <!--Answer not required-->
                                            {!! Form::text('answer[]', null, ['rows' => 2, 'class' => 'form-control', 'placeholder' => 'Antwort hier hinzufügen']) !!}
                                            @else
                                                    <!--Answer* required-->
                                            {!! Form::text('answer[]', null, ['required' => 'true', 'rows' => 2, 'class' => 'form-control', 'placeholder' => 'Antwort hier hinzufügen']) !!}
                                            @endif
                                        </div>
                                    @endforeach
                                    @if($userCanEditOrDeleteAnswer)
                                        <div class="answerToQuestion ">
                                        </div>
                                    @endif
                                </div>
                                @foreach($answers as $answer)
                                    <div class="rowNoPadding">
                                        <!--Color 1-->
                                        @if($alternatingColor == 0)
                                            <?php $alternatingColor = 1; ?>
                                            <div class="answerToQuestion color1">
                                                @if($club = $clubs->find($answer->club_id))
                                                    club: {{$club->clb_title}}
                                                @else
                                                    club: kein Club
                                                @endif
                                            </div>
                                            @foreach($answer->getAnswerCells as $cell)
                                                <div class="answerToQuestion color1">
                                                    {{$cell->answer}}
                                                </div>
                                                @endforeach
                                                @if($userCanEditOrDeleteAnswer)
                                                        <!--Edid Delete Buttons-->
                                                <div class="answerToQuestion color1 editDelete">
                                                    <a href="#"
                                                       class="btn btn-primary"
                                                       data-toggle="tooltip"
                                                       data-placement="bottom">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    &nbsp;&nbsp;
                                                    <a href="#"
                                                       class="btn btn-default"
                                                       data-toggle="tooltip"
                                                       data-placement="bottom"
                                                       data-method="delete"
                                                       data-token="{{csrf_token()}}"
                                                       rel="nofollow"
                                                       data-confirm="Möchtest Du diese Antwort wirklich löschen?">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </div>
                                                @endif
                                                @else
                                                        <!--Color 2-->
                                                <?php $alternatingColor = 0; ?>
                                                <div class="answerToQuestion color2">
                                                    @if($club = $clubs->find($answer->club_id))
                                                        club: {{$club->clb_title}}
                                                    @else
                                                        club: kein Club
                                                    @endif
                                                </div>
                                                @foreach($answer->getAnswerCells as $cell)
                                                    <div class="answerToQuestion color2">
                                                        {{$cell->answer}}
                                                    </div>
                                                    @endforeach
                                                    @if($userCanEditOrDeleteAnswer)
                                                            <!--Edid Delete Buttons-->


                                                    <div class="answerToQuestion color2 editDelete">
                                                        <a href="#"
                                                           class="btn btn-primary"
                                                           data-toggle="tooltip"
                                                           data-placement="bottom">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                        &nbsp;&nbsp;
                                                        <a href="#"
                                                           class="btn btn-default"
                                                           data-toggle="tooltip"
                                                           data-placement="bottom"
                                                           data-method="delete"
                                                           data-token="{{csrf_token()}}"
                                                           rel="nofollow"
                                                           data-confirm="Diese Veranstaltung wirklich entfernen? Diese Aktion kann nicht rückgängig gemacht werden!">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </div>
                                                    @endif
                                                    @endif
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
            <!--Speichern desktop -->
            <button type="submit" class="btn btn-primary btn-margin" style="display: inline-block;">
                Speichern!
            </button>
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
        </div>
        {!! Form::close() !!}
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
            {!! Form::text('answer[]', null, ['class' => 'form-control', 'placeholder' => 'Bitte gib deinen Namen ein', 'required' => true]) !!}
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
                {!! Form::text('answer[]', null, ['required' => 'true', 'rows' => 2, 'class' => 'form-control', 'placeholder' => 'Antwort hier hinzufügen']) !!}
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
        <button type="submit" class="btn btn-primary btn-margin" style="display: inline-block;">
            Speichern!
        </button>
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
    </div>
@stop