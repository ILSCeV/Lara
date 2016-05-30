@extends('layouts.master')
@section('title')
    {{$survey->title}}
@stop
@section('moreStylesheets')
    <link rel="stylesheet" media="all" type="text/css" href="{{ asset('/css/surveyViewStyles.css') }}"/>
    @stop
    @section('content')
            <!--
        Title Box
        All relevant infos about survey are here!
    -->

    <div class="panel no-padding">
        @foreach($answers as $answer)
            answer: {{$answer}}
            <br>
            @foreach($answer->getAnswerCells as $cell)
                cell:    {{$cell}}
                <br>
                cell->answer:    {{$cell->answer}}
                <br>
            @endforeach
            <br>
            <br>
        @endforeach
        <div class="panel-body">
            <h4 class="panel-title">
                {{ $survey->title }}
            </h4>
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


    <div class="panel displayMobile">
        <!--
        mobile
        <br>
        <br>
        Tragen Sie bitte Ihre Antwort ein <br>
        Frage 1: <br>
        Tetxtbox 1 <br>
        Frage 2: <br>
        Textbox 2<br>
        Frage 3<br>
        Dropdown zu Frage 3<br>
        <br>
        Person 1
        Frage 1: <br>
        Antwort 1 <br>
        Frage 2: <br>
        Antwort 2<br>
        Frage 3<br>
        Antwort 3<br>
        <br>
        alle weiteren Personen
        -->
    </div>


    {{--        @foreach($answers as $answer)
                answer: {{$answer}}
                <br>
                @foreach($answer->getAnswerCells as $cell)
                    cell:    {{$cell}}
                    <br>
                    cell->answer:    {{$cell->answer}}
                    <br>
                @endforeach
                <br>
                <br>
            @endforeach
    --}}
            <!--
        Calculate width of row in answers

    <?php
    $numberAnswers = 0;
    $userAlreadyParticipated = false;
    ?>
    @foreach($answers as $answer)
    @if($numberAnswers == 0)
    @foreach($answer->getAnswerCells as $cell)
    <?php $numberAnswers = $numberAnswers + 1;  ?>
    @endforeach
    @endif
            <!--
                    if user did already participate in poll,
                    do not display textareas

                    also add delete / edit buttons at the end of the line
                        -->
    @endforeach


    <?php
    $numberAnswers *= 50;
    $firstLine = true;
    $alternatingColor = 0;
    ?>
    <div class="panel displayDesktop">
        <div class="row rowNoPadding">
            <div class="col-md-2">
                @foreach($answers as $answer)
                    @if($firstLine == true)
                        <?php $firstLine = false; ?>
                        <div class=" rowNoPadding nameToQuestion">
                            names
                        </div>
                        <div class=" rowNoPadding nameToQuestion">
                            enter your name here
                        </div>
                    @endif
                    @if($alternatingColor == 0)
                        <?php $alternatingColor = 1; ?>
                        <div class=" rowNoPadding nameToQuestion color1">
                            @else
                                <?php $alternatingColor = 0; ?>
                                <div class=" rowNoPadding nameToQuestion color2">
                                    @endif
                                    {{$answer->name}}
                                </div>
                                @endforeach
                        </div>
                        <?php $firstLine = true;
                        $alternatingColor = 0;
                        ?>
                        <div class="col-md-10 answers">
                            <div style="width: {{$numberAnswers}}vw;">
                                @foreach($answers as $answer)
                                    @if($firstLine == true)
                                        <?php $firstLine = false; ?>
                                        <div class="rowNoPadding ">
                                            @foreach($answer->getAnswerCells as $cell)
                                                <div class="answerToQuestion">
                                                    question
                                                </div>
                                            @endforeach
                                        </div>
                                        @if($userAlreadyParticipated == false)
                                            <div class="rowNoPadding">
                                                @foreach($answer->getAnswerCells as $cell)
                                                    <div class="answerToQuestion">
                                                        enter answer here
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    @endif
                                    <div class="rowNoPadding">
                                        @if($alternatingColor == 0)
                                            <?php $alternatingColor = 1; ?>
                                            @foreach($answer->getAnswerCells as $cell)
                                                <div class="answerToQuestion color1">
                                                    {{$cell->answer}}
                                                </div>
                                            @endforeach
                                        @else
                                            <?php $alternatingColor = 0; ?>
                                            @foreach($answer->getAnswerCells as $cell)
                                                <div class="answerToQuestion color2">
                                                    {{$cell->answer}}
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
            </div>
        </div>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <!--
            Body Box
            Write Answers in here!
            Idea! How about each answer as a separate row?
        -->
        <div class="panel no-padding">
            <div class="panel-body">
                <!--Title of answer box-->
                <h4 class="panel-title">
                    <div class="row">
                        <div class="col-md-2 names">
                            Name
                        </div>
                        <div class="col-md-10 answers">
                            Verein, Frage 1, Frage 2
                        </div>
                    </div>
                </h4>
                <!--new answer-->
                <div class="row answerBox">
                    <div class="col-md-2 names">
                        Textbox
                    </div>
                    <div class="col-md-10 answers">
                        dropdown, Textbox 1, Textbox 2
                    </div>
                </div>
                <!--All answers until now-->
                <div class="row answerBox">
                    <div class="col-md-2 names">
                        mein Name
                    </div>
                    <div class="col-md-10 answers">
                        <div>
                            <!--
                                Solution problem not scrollable:
                                Build wrapper with "fixed" width. This width is a variable.
                                It is the sum of the width of its sub-elements
                                Example:
                                3 sub element, each 50vw wide.
                                2x margin 10vh
                                Sum = 170vw
                            -->
                            <div style="width: 140%; background-color: red; float: left;">
                                test
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="answerBox" style="width: 100%;">
                <div class="inner-container">
                    <div class="answers">
                    </div>
                </div>
            </div>
            <div class="btn-group col-md-6">
                <div class="row">
                    <div class="panel no-padding">
                        <br><br><br><br>
                        {{$questions[0]}}
                        <br><br><br><br>
                        <h4 class="panel-title text-center">Umfrage {{ $survey->title }}</h4>
                        <div class="panel-body">
                            <h6>Beschreibung:</h6>
                            {!! $survey->description!!}
                            <div class="panel-footer">
                                Die Umfrage läuft noch
                                bis: {{ strftime("%a, %d %b", strtotime($survey->deadline)) }} um
                                {{ date("H:i", strtotime($survey->deadline)) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="panel">
                        <h5 class="panel-title text-center">Auswahlmöglichkeiten</h5>
                        @foreach($questions as $question)
                            <div class="row">
                                {{$question->question}}
                                {{--@foreach($answers[$question->id] as $answer)--}}
                                {{--<div class="col-md-3">--}}
                                {{--{{ $answer->content }}--}}
                                {{--</div>--}}
                                {{--@endforeach--}}
                            </div>
                        @endforeach
                    </div>
                </div>
                <div>
                    @include('partials.surveyAnswer')
                </div>
                <div>
                    @include('partials.surveyEvaluation')
                </div>
            </div>
@stop