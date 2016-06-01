<div class="form-group">
    {!! Form::label('questions[]', 'Frage:') !!}
    {!! Form::textarea('questions[]', null, ['placeholder'=>'z.B. Kommst du zur Weihnachtsfeier?',
                                            'size' => '100x3',
                                            'class' => 'form-control'
                                            ]) !!}
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
                newElem.find('.questions').attr('id', 'ID' + newNum + 'questions[]').attr('name', 'ID' + newNum + 'questions[]').val('');


                $('#questions' + num).after(newElem);
                $('#ID' + newNum + '_title').focus();


                $('#btnDel').attr('disabled', false);

                if (newNum == 5)
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

</head>

<body>

<div class="panel-body">
    <div id="wrapper">

        <div class="questions">

            <div id="questions1" class="clonedInput">
                <h4 id="reference" name="reference" class="heading-reference">Frage #1</h4>

                <fieldset>
                    <label class="question" for="question">Frage:</label>

                    <input class="question" type="text" name="questions[]" id="question" value="">

                </fieldset>

                <fieldset class="checkbox entrylist">
                    <label class="label_checkboxitem" for="checkboxitemitem"></label>
                    <ul>
                        <li><label><input type="checkbox" id="colorBlue" value="colorBlue" name="checkboxitem" class="input_checkboxitem"> erforderlich</label></li>

                    </ul>
                </fieldset>


            </div>
        </div>
        <div id="addDelButtons">
            <input type="button" id="btnAdd" value="Frage hinzufügen" class="btn btn-primary"> <input type="button" id="btnDel" value="letzte Frage löschen" class="btn btn-danger">
        </div>


    </div>
</div>
</body>
</html>