<div class="form-group">

</div>

<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

    <script type="text/javascript">
        $(function () {
            $('#btnAdd').click(function () {
                var num     = $('.clonedInput').length,
                        newNum  = new Number(num + 1),
                        newElem = $('#questions' + num).clone().attr('id', 'questions' + newNum).fadeIn('slow');

                newElem.find('.heading-reference').attr('id', 'ID' + newNum + '_reference').attr('name', 'ID' + newNum + '_reference').html('Frage #' + newNum);


                newElem.find('.questions').attr('for', 'ID' + newNum + 'questions[]');
                newElem.find('.questions').attr('id', 'ID' + newNum + 'questions[]').attr('name', 'questions[]').val('');

                newElem.find('.field_type').attr('for', 'ID' + newNum + '_type');
                newElem.find('.btn btn-group btn-default dropdown-toggle btn-sm').attr('id', 'ID' + newNum + '_type').attr('name', 'ID' + newNum + '_type').val('');

                newElem.find('.label_checkboxitem').attr('for', 'ID' + newNum + '_checkboxitem');
                newElem.find('.input_checkboxitem').attr('id', 'ID' + newNum + '_checkboxitem').val([]);

                newElem.find('.answ_option').attr('id', 'answ_opt' + newNum);
                newElem.find('#button_answ').attr('onclick', 'javascript:clone_this(this, "new_passage",' + (newNum - 1) + ');');

                newElem.find('.answer_option').val('');
                newElem.find('.passage').remove();

                newElem.find('.caret').val('');

                $('#questions' + num).after(newElem);
                $('#ID' + newNum + '_title').focus();


                $('#btnDel').attr('disabled', false);

                if (newNum == 10)
                    $('#btnAdd').attr('disabled', true).prop('value', "Limit erreicht");
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

                $('#btnAdd').attr('disabled', false);
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
          }

    </script>

    <script>
        function remove_this(objLink)
        {
            objLink.parentNode.parentNode.parentNode.parentNode.parentNode.removeChild(objLink.parentNode.parentNode.parentNode.parentNode);
        }
    </script>

</head>

<body>

<div class="panel-body">
    <div id="wrapper">

        <div class="questions">

            <div id="questions1" class="clonedInput">
                <h4 id="reference" name="reference" class="heading-reference">Frage #1</h4>

                <fieldset>
                    <label class="questions" for="question">Frage: &nbsp</label>

                    <input class="questions" type="text" name="questions[]" id="question" value="">
                </fieldset>


                <div style="visibility:hidden; display:none">
                    <div id="new_passage"><table class="passage" id="new_passage" name="cloneTable">
                            <tr>
                                <td>Antwortmöglichkeit: &nbsp</td>
                                <td><input class="answer_option" type="text" name="answer_options[][]"></input></td>
                                <td class="helltab" rowspan="3">
                                    <a href="#" id="delete_button" onclick="javascript:remove_this(this); return false;">
                                         <i class="fa fa-trash" aria-hidden="true"></i></a>
                                </td>
                        </table>
                    </div>
                </div>

                <div class="answ_option" id="answ_opt">
                    <input class="btn btn-success btn-sm" id="button_answ" name="1" value="Antwortmöglichkeit hinzufügen" onclick="javascript:clone_this(this, 'new_passage', 0);" type="button"></input>
                </div>

                &nbsp

                <fieldset>
                    <div>
                        <label class="field_type" for="type">Frage-Typ:</label>
                            <select class="btn btn-default dropdown-toggle btn-sm" type="button" name="type" id="field_type" data-toggle="dropdown">
                                <option value="" selected="selected" disabled="disabled">Frage-Typ Auswählen</option>
                                <option value="1">Freitext</option>
                                <option value="2">Checkbox</option>
                                <option value="3">Dropdown</option>
                            </select>
                    </div>
                </fieldset>

        <fieldset class="checkbox entrylist">
                    <label class="label_checkboxitem" for="checkboxitemitem"></label>
                    <ul>
                        <li><label><input type="checkbox" id="required" value="required" name="required[]" class="input_checkboxitem"> erforderlich</label></li>

                    </ul>
                </fieldset>


            </div>
        </div>
        <div id="addDelButtons">
            <input type="button" id="btnAdd" value="Frage hinzufügen" class="btn btn-war"> <input type="button" id="btnDel" value="letzte Frage löschen" class="btn btn-danger">
        </div>


    </div>

</div>
</body>
</html>