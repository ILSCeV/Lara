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
    {{--
        @foreach($answers as $answer)
            answer: {{$answer}}
            <br>
            @if($club = $clubs->find($answer->club_id))
                club: {{$club->clb_title}}
            @else
                club: kein Club
            @endif
            <br>
            @foreach($answer->getAnswerCells as $cell)
                cell {{$cell}}
                <br>
                cell->answer: {{$cell->answer}}
                <br>
            @endforeach
            <br>
            <br>
        @endforeach
    --}}

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



    <!-- /////////////////////////////////////////// Start of desktop View /////////////////////////////////////////// -->


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
    @if($answer->creator_id == $userId)
        /* if creator of one of the the questions is currently loged in, display edit / delete buttons */
            <?php $userAlreadyParticipated = true; ?>
        @endif
    @endforeach
    @endif
            <!--
                also add delete / edit buttons at the end of the line
            -->
    @endforeach


    <?php
    /* columnWidth = width of answerToQuestion */
    $columnWidth = 50;
    /* number of columns * width */
    $numberAnswers *= $columnWidth;
    /* Club Column is added as a default */
    $numberAnswers += $columnWidth;
    $firstLine = true;
    $alternatingColor = 0;
    ?>

    @if($userAlreadyParticipated == true)
        <?php $numberAnswers += $columnWidth ?>
    @endif

    <div class="panel displayDesktop">
        meine Daten: <br>
        userID: {{$userId}}
        <br>
        userGroup: {{$userGroup}}
        <br>
        @foreach($answers as $answer)
            User der geantwortet hat: {{$answer->creator_id}}
            <br>
            <br>
        @endforeach
        <div class="row rowNoPadding">
            <div class="col-md-2 rowNoPadding shadow">
                @foreach($answers as $answer)
                    @if($firstLine == true)
                        <?php $firstLine = false; ?>
                        <div class=" rowNoPadding nameToQuestion">
                            names
                        </div>
                        <div class=" rowNoPadding nameToQuestion">
                            <input type="text" placeholder="dein Name" class="form-control" id="name">
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
                                            <div class="answerToQuestion">
                                                Club
                                            </div>
                                            @foreach($questions as $question)
                                                <div class="answerToQuestion">
                                                    {{$question->question}}
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="rowNoPadding">
                                            <div class="answerToQuestion">
                                                <select class="form-control" id="sel1">
                                                    <option>D</option>
                                                    <option>C</option>
                                                    <option>Cafe</option>
                                                    <option>I</option>
                                                </select>
                                            </div>
                                            @foreach($answer->getAnswerCells as $cell)
                                                <div class="answerToQuestion">
                                                        <textarea class="form-control" rows="5" id="answer"
                                                                  placeholder="meine Antwort...">
                                                        </textarea>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
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
                                                @if($answer->creator_id == $userId)
                                                    <div class="answerToQuestion color1">
                                                        buttons, bearbeiten, löschen
                                                    </div>
                                                    @endif
                                                    @endforeach
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
                                                        @if($answer->creator_id == $userId)
                                                            <div class="answerToQuestion color1">
                                                                buttons, bearbeiten, löschen
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
            </div>
        </div>
    </div>


    <!-- /////////////////////////////////////////// Start of mobile View /////////////////////////////////////////// -->


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
    <div class="panel displayMobile">
        Fragen nur Oben hinschreiben
        <br>
        userAlreadyParticipated rausnehmen!
        <br>
        Why do the form-controls look so different?
    </div>
    <div class="panel displayMobile">
        <div class="panel-body">
            <h4 class="panel-title">
                mobile
            </h4>
            @foreach($answers as $answer)
                    <!--First Line-->
            @if($userAlreadyParticipated == false && $firstLine == true)
                <?php $firstLine = false; ?>
                <label for="name">Name:</label>
                <br>
                <input type="text" placeholder="dein Name" class="form-control" id="name">
                <br>
                <label for="organization">Verein</label>
                <br>
                <label for="sel1">Select list:</label>
                <select class="form-control" id="sel1">
                    <option>D</option>
                    <option>C</option>
                    <option>Cafe</option>
                    <option>I</option>
                </select>
                @foreach($answer->getAnswerCells as $cell)
                    <br>
                    <label for="answer">{{$question->question}}</label>
                    <br>
                    <textarea class="form-control" rows="5" id="answer"
                              placeholder="meine Antwort..."></textarea>
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
                @endif
                <div class="line"></div>
                @endforeach
        </div>
    </div>
@stop