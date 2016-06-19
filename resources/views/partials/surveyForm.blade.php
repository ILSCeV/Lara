<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

    <script>
        $(function () {
            if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
                $('.selectpicker').selectpicker('mobile');
            }
        });
    </script>
    <script type="text/javascript">

        window.onload = function () {
            $('.questions' || '.input_checkboxitem' || '.selectpicker').change(function () {
                $(window).bind('beforeunload', function () {
                    return 'Beim Verlassen der Seite gehen alle Eingaben verloren.';
                });
            });

            $("form").submit(function () {
                if ($('#btnAdd') !== '.click')
                    $(window).unbind('beforeunload');
            });
        };

        $(function () {
            var count_questions = 1;
            $('#btnAdd').click(function () {
                count_questions++;
                var num     = $('.clonedInput').length,
                        newNum  = new Number(num + 1),
                        newElem = $('#questions' + num).clone().attr('id', 'questions' + newNum).fadeIn('slow');

                newElem.find('.heading-reference').attr('id', 'ID' + newNum + '_reference').attr('name', 'ID' + newNum + '_reference').html('Frage #' + newNum);


                newElem.find('.questions').attr('for', 'ID' + newNum + 'questions[]');
                newElem.find('.questions').attr('id', 'ID' + newNum + 'questions[]').attr('name', 'questions[]').val('');


                newElem.find('.label_checkboxitem').attr('for', 'ID' + newNum + '_checkboxitem');
                newElem.find('.input_checkboxitem').attr('id', 'ID' + newNum + '_checkboxitem').val([]);

                newElem.find('.selectpicker').attr('id', 'field_type' + (newNum - 1));
                newElem.find('.btn-success').attr('id', 'button_answ' + (newNum - 1));

                newElem.find('.selectpicker').attr('onchange', 'javascript:check_question_type(' + (newNum - 1) + ');' + 'check_question_type2('+ (newNum - 1) + ');');

                newElem.find('.answ_option').attr('id', 'answ_opt' + (newNum - 1));
                newElem.find('.btn-success').attr('onclick', 'javascript:clone_this(this, "new_passage",' + (newNum - 1) + ');');

                newElem.find('.answer_option').val('');

                newElem.find('.passage' + newNum).slice(1).remove();
                newElem.find('.passage' + (newNum - 1)).remove();
                newElem.find('.passage' + (newNum-2)).remove();

                $('.del').attr('class', 'not_del');

                $('#questions' + num).after(newElem);
                $('#ID' + newNum + '_title').focus();

                newElem.find('.bootstrap-select').attr('class', '.bootstrap-select del');

                $('.del').find('.dropdown-toggle').remove();

                $('.selectpicker').selectpicker('refresh');

                newElem.find('.input_checkboxitem').attr('name', 'required[' + (newNum - 1) + ']');

                newElem.find('#button_answ' + (newNum - 1)).attr('style', 'display:none');

                $("form").submit(function() {
                        $('.bootstrap-select').find('#field_type' + (newNum -1));
                    if ($('#field_type' + (newNum -1)).val() === '0') {
                        alert("Frage-Typ muss bei Frage " + (newNum) + " ausgewählt sein");
                        return false;
                    }
                });

                $(document).ready(function () {
                    $('.questions' || '.input_checkboxitem' || '.selectpicker').change(function () {
                        $(window).bind('beforeunload', function () {
                            return 'Beim Verlassen der Seite gehen alle Eingaben verloren.';
                        });
                    });

                    var x = false;
                    var z = 0;
                    var field_type_not_selected = 0;
                    var field_type_selected = 0;

                    $("form").submit(function () {
                        x = false;
                        z = 0;
                        field_type_not_selected = 0;
                        field_type_selected = 0;
                        if ($('#field_type').val() !== '' && $('#field_type' + (count_questions - 1)).val() !== '0') {
                            x = true;

                            check_unbind2();
                            for (i = 0; i < (count_questions - 2); i++) {
                                z++;
                                if ($('#field_type' + z).val() === '0')
                                    field_type_not_selected++;
                                else
                                    field_type_selected++;
                            }
                        }
                        if (z >= (count_questions - 2) && (field_type_not_selected + field_type_selected) === z)
                            check_unbind();

                    });
                    function check_unbind() {

                        if (x === true && field_type_not_selected === 0 && count_questions >= '3' && field_type_selected > 0) {
                            $(window).unbind('beforeunload');
                        }
                    }

                    function check_unbind2() {
                        if (x === true && count_questions === 2) {
                            $(window).unbind('beforeunload');
                        }
                    }
                });

                $('#btnDel').attr('disabled', false);

                $('#btnDel').click(function () {
                    newNum--;
                });
            });

            $('#btnDel').click(function () {

                if (confirm("Bist du sicher, dass du die Frage löschen möchtest?"))
                {
                    var num = $('.clonedInput').length;

                    $('#questions' + num).slideUp('slow', function () {$(this).remove();

                        if (num -1 === 1)
                            $('#btnDel').attr('disabled', true);

                        $('#btnAdd').attr('disabled', false).prop('value', "Frage hinzufügen");});
                }
                return false;

            });

            $('#btnDel').attr('disabled', true);
        });

    </script>

    <script>
        function clone_this(button, objid, number){

            $(".passage").find('.answer_option').eq(0).attr({
                name: 'answer_options[' + number + ']' + '[]'
            });

            var clone_me = document.getElementById(objid).firstChild.cloneNode(true);

            button.parentNode.insertBefore(clone_me, button);

            if (number == '0')
                $('#answ_opt').find('table:last').attr('class', 'passage' + number );

            if (number >= 1)
                $('#answ_opt' + number).find('table:last').attr('class', 'passage' + (number ));
        }

    </script>

    <script>
        function remove_this(objLink)
        {
            objLink.parentNode.parentNode.parentNode.parentNode.parentNode.removeChild(objLink.parentNode.parentNode.parentNode.parentNode);
        }
    </script>

    <script>
        function check_question_type(number) {

            if ($('#field_type').val() === "3")
                $('#button_answ').fadeTo('slow', 1)&
                $('#button_answ').attr('style', 'visibility:visible');

            var att = document.getElementById('button_answ').getAttribute('style');
            if (att === 'display:none')
                void(0);

            if ($('#field_type').val() !== "3" && att !== 'display:none' && number == '0')
                $('#button_answ').fadeTo('slow', 0) &
                $('#button_answ').attr('style', 'visibility:hidden') &
                timeOut(number);

            if ($('#field_type' + number).val() === "3")
                $('#button_answ' + number).fadeTo('slow', 1) &
                $('#button_answ' + number).attr('style', 'visibility:visible');

            if (document.getElementById('button_answ' + number))
                var att2 = document.getElementById('button_answ' + number).getAttribute('style');
            if (att2 === 'display:none')
                void(0);

            if ($('#field_type' + number).val() !== "3" && att2 !== 'display:none')
                $('#button_answ' + number).fadeTo('slow', 0) &
                $('#button_answ' + number).attr('style', 'visibility:hidden') &
                timeOut2(number);

        }

        function timeOut() {
            setTimeout(function(){

                var att = document.getElementById('button_answ').getAttribute('style');
                if (att === 'display:none')
                    void(0);
                else
                    $('#button_answ').attr('style', 'display:none');
            },700);
        }

        function timeOut2(number) {
            setTimeout(function(){

                if (document.getElementById('button_answ' + number))
                    var att2 = document.getElementById('button_answ' + number).getAttribute('style');
                if (att2 === 'display:none')
                    void(0);
                else
                    $('#button_answ' + number).attr('style', 'display:none');
            },700);
        }
    </script>

    <script>
        function check_question_type2(number) {

            if ($('#field_type').val() !== "3")
                $('#answ_opt').find('.passage' + number).fadeOut() & setTimeout(function () {
                    $('#answ_opt').find('.passage' + number).remove();
                },700);

            if ($('#field_type' + number).val() !== "3")
                $('#answ_opt' + number).find('.passage' + number).fadeOut() & setTimeout(function () {
                    $('#answ_opt' + number).find('.passage' + number).remove();
                },700);

        }
    </script>

    <script>

        $("form").submit(function() {

            if ($('#field_type').val() === '0') {
                alert("Frage-Typ muss bei Frage 1 ausgewählt sein");
                return false;
            }
        });

    </script>

</head>

<body>

<script>
    $(document).ready(function() {
        $('.form-group').change(function() {
            $(window).bind('beforeunload', function() {
                return 'Beim Verlassen der Seite gehen alle Eingaben verloren.';
            });
        });

    $("form").submit(function () {
          if ($('#btnAdd') !== '.click')
              $(window).unbind('beforeunload');
             });
         });
</script>

<div class="panel-body">

    <div class="form-group">
        {!! Form::label('title', 'Umfragentitel:') !!}
        {!! Form::text('title', $survey->title, ['placeholder'=>'z.B. Teilnahme an der Clubfahrt',
            'required',
            'class' => 'form-control'
            ]) !!}
    </div>
    <div class="form-group">
        {!! Form::label('description', 'Umfragenbeschreibung:') !!}
        {!! Form::textarea('description', $survey->description, ['size' => '100x4',
            'class' => 'form-control'
            ]) !!}
    </div>
    <div class="form-group">
        {!! Form::label('deadline', 'Umfrage aktiv bis:') !!}
        {!! Form::date('deadline', $time, ['class' => 'form-control']) !!}
    </div>
    {{--<div class="form-group">--}}
    {{--{!! Form::label('in_calendar', 'In Kalender am:') !!}--}}
    {{--{!! Form::date('in_calendar', $date, ['class' => 'form-control']) !!}--}}
    {{--</div>--}}

    <div class="form-group">
        <div>
            <label class="label_checkboxitem" for="checkboxitemitem"></label>
            <label><input type="checkbox" id="required1" value="required1" name="is_private" class="input_checkboxitem"
                          @if($survey->is_private) checked @endif> Nur für eingeloggte Nutzer sichtbar?  </label>
        </div>
        <div>
            <label class="label_checkboxitem" for="checkboxitemitem"></label>
            <label><input type="checkbox" id="required2" value="required2" name="is_anonymous" class="input_checkboxitem"
                          @if($survey->is_anonymous) checked @endif> anonyme Umfrage</label>
        </div>
        <div>
            <label class="label_checkboxitem" for="checkboxitemitem"></label>
            <label><input type="checkbox" id="required3" value="required3" name="show_results_after_voting" class="input_checkboxitem"
                          @if($survey->show_results_after_voting) checked @endif> zeige Ergebnisse nach der Abstimmung</label>
        </div>

    </div>
    <hr class="col-md-6 col-xs-12 top-padding no-margin no-padding">
</div>
    </div>


    <div id="wrapper">

        <div class="questions">

            <div id="questions1" class="clonedInput">
                <div class="panel col-md-8 col-sm-12 col-xs-12"></div>
                <div class="panel col-md-8 col-sm-12 col-xs-12">
                    <div class="panel-body">

                <h4 id="reference" name="reference" class="heading-reference">Frage #1</h4>

                        <div class="col-md-6 col-sm-6 col-xs-12">
                <fieldset>
                    <label class="questions" for="question">Frage: &nbsp</label>

                    <textarea class="form-control" type="text" name="questions[]" id="question"></textarea>
                </fieldset>
                        </div>

                        
                        <div class="col-md-6 col-sm-6 col-xs-12">
                        <fieldset>
                            <select class="selectpicker" title="Frage-Typ Auswählen" name="type[]" id="field_type" onchange="javascript:check_question_type(0); check_question_type2(0);">
                                <option selected value="0" style="display:none"></option>
                                <option value="1" data-icon="fa fa-file-text-o">Freitext</option>
                                <option value="2" data-icon="fa fa-check-square-o">Checkbox</option>
                                <option value="3" data-icon="fa fa-caret-square-o-down">Dropdown</option>
                            </select>
                        </fieldset>
                            </div>




                        <div class="col-md-6 col-sm-6 col-xs-12">
                <fieldset class="checkbox entrylist">
                    <label class="label_checkboxitem" for="checkboxitemitem"></label>
                    <label><input type="checkbox" id="required" value="required" name="required[0]" class="input_checkboxitem"> erforderlich</label>
                </fieldset>
                            </div>

                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div style="visibility:hidden; display:none">
                                <div id="new_passage"><table class="passage" id="new_passage" name="cloneTable">
                                        <tr>
                                            <td>Antwortmöglichkeit: &nbsp</td>
                                            <td><input id="answer_option" class="answer_option" type="text" name="answer_options[][]"></input></td>
                                            <td class="helltab" rowspan="3">
                                                <a href="#" id="delete_button" onclick="javascript:remove_this(this); return false;">
                                                    <i class="fa fa-trash" aria-hidden="true"></i></a>
                                            </td>
                                    </table>
                                </div>
                            </div>

                            <div class="answ_option" id="answ_opt">
                                <input class="btn btn-success btn-sm" id="button_answ" value="Antwortmöglichkeit hinzufügen" style="display:none"  onclick="javascript:clone_this(this, 'new_passage', 0);" type="button"></input>
                            </div>
                        </div>

            </div>
                    </div>

            </div>

        </div>
        <div class="panel col-md-8 col-sm-12 col-xs-12"></div>
        <div class="panel col-md-8 col-sm-12 col-xs-12">
            <div class="panel-body">
        <div id="addDelButtons">
            <input type="button" id="btnAdd" value="Frage hinzufügen" class="btn btn-success"> <input type="button" id="btnDel" value="letzte Frage löschen" class="btn btn-danger">
        </div>
        </div>
        </div>
    </div>
</div>
</body>
</html>