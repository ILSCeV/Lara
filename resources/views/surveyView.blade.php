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

    <!--
Calculate width of row in answers
-->
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
            <div class="col-md-2 rowNoPadding shadow">
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
                        <div class=" rowNoPadding nameToQuestion color1 shadow">
                            @else
                                <?php $alternatingColor = 0; ?>
                                <div class=" rowNoPadding nameToQuestion color2 shadow">
                                    @endif
                                    {{$answer->name}}
                                </div>
                                @endforeach
                        </div>
                        <?php $firstLine = true;
                        $alternatingColor = 0;
                        ?>
                        <div class="col-md-10 answers rowNoPadding">
                            <div style="width: {{$numberAnswers}}vw;" class="displayDesktop">
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


    <?php
    $firstLine = true;
    $alternatingColor = 0;
    $userAlreadyParticipated = false;
    ?>
    Wo sollen die Fragen stehen?
    <div class="panel displayMobile">
        <div class="panel-body">
            <h4 class="panel-title">
                mobile
            </h4>
            @foreach($answers as $answer)
                    <!--First Line-->
            @if($userAlreadyParticipated == false && $firstLine == true)
                <?php $firstLine = false; ?>
                Name: <br>
                textbox
                <br>
                Verein: <br>
                dropdown
                @foreach($answer->getAnswerCells as $cell)
                    <br>
                    Antwort:
                    <br>
                    textbox
                @endforeach
                <div class="line"></div>
            @endif
            @if($userAlreadyParticipated == true && $firstLine == true)
                <?php $firstLine = false; ?>
                here Answer of User
                <div class="line"></div>
                @endif
                        <!--All other Answers / Lines-->
                @if($firstLine == false)
                    Name: {{$answer->name}}
                    <br>
                    Verein: {{$answer->club_id}}
                    @foreach($answer->getAnswerCells as $cell)
                        <br>
                        Antwort:    {{$cell->answer}}
                    @endforeach
                @endif
                <div class="line"></div>
                @endforeach
        </div>
    </div>
@stop