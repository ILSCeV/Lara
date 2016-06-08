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
    $userAlreadyParticipated = false;
    $tableNot100Percent = false;
    ?>
    @foreach($answers as $answer)
    @foreach($answer->getAnswerCells as $cell)
    @if($answer->creator_id == $userId)
            <!--if creator of one of the the questions is currently loged in, display edit / delete buttons -->
    <?php $userAlreadyParticipated = true; ?>
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
    $firstLine = true;
    $alternatingColor = 0;
    ?>
    @if($userAlreadyParticipated == true)
    <?php $numberQuestions += $columnWidth ?>
    @endif
            <!--table min 100% width of page-->
    @if($numberQuestions < 100)
        <?php $tableNot100Percent = true ?>
    @endif


    <div class="panel displayDesktop">
        <div class="row rowNoPadding">
            <div class="col-md-2 rowNoPadding shadow">
                @if($firstLine == true)
                    <?php $firstLine = false; ?>
                    <div class=" rowNoPadding nameToQuestion">
                        Namen
                    </div>
                    <div class=" rowNoPadding nameToQuestion">
                        <input type="text" placeholder="dein Name" class="form-control" id="name">
                    </div>
                @endif
                @foreach($answers as $answer)
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
                            <div id="rightPart" style="width: {{$numberQuestions}}vw;" class="displayDesktop">
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
                                        @if($userAlreadyParticipated)
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
                                        @foreach($questions as $q)
                                            <div class="answerToQuestion">
                                                <textarea class="form-control" rows="2" id="answer"
                                                          placeholder="Antwort hier hinzufügen"></textarea>
                                            </div>
                                        @endforeach
                                        @if($userAlreadyParticipated)
                                            <div class="answerToQuestion ">
                                            </div>
                                        @endif
                                    </div>
                                @endif
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

                                            @if($userAlreadyParticipated)
                                                @if($answer->creator_id == $userId)
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
                                                @else
                                                    <div class="answerToQuestion color1">
                                                    </div>
                                                    @endif
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
                                                    @if($userAlreadyParticipated)
                                                        @if($answer->creator_id == $userId)
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
                                                        @else
                                                            <div class="answerToQuestion color1">
                                                                empty
                                                            </div>
                                                        @endif
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
            @if($firstLine == true)
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
                    <textarea class="form-control" rows="2" id="answer"
                              placeholder="Antwort hier hinzufügen"></textarea>
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
                    @if($userAlreadyParticipated)
                        @if($answer->creator_id == $userId)
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
                    @endif
                @endif
                <div class="line"></div>
                @endforeach
        </div>
    </div>
    <button type="button" class="btn btn-primary btn-margin" data-dismiss="alert" style="display: inline-block;">
        Speichern!
    </button>

@stop