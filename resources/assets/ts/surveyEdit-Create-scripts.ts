var questionIdCounter = 0;

// Initialize State for questions and answers
$(function() {
    $('.selectpicker').each(updateQuestionDisplay);

    var $questions = $('.questions');
    $questions.find('select').each(function() {
        updateQuestionDisplay(this);
    });
    $questions.children().each(function () {
        updateAnswerOptionsIndices(this);
    });
    setQuestionNumberDisplayed();
    setDeleteQuestionButtonStatus();

    // Changes dropdown style for mobile view
    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
        $('.selectpicker').selectpicker('mobile');
    }

    $('.form-group').change(function() {
        $(window).bind('beforeunload', function() {
            return 'Beim Verlassen der Seite gehen alle Eingaben verloren.';
        });
    });

    $("form").submit(function () {
        if (<any>$('#btnAdd') !== '.click') {
            $(window).unbind('beforeunload');
        }
    });

    // Checks if user has made input -> if yes, a popup appears if user wants to leave the site
    $('.questions').add('.input_checkboxitem').add('.selectpicker').change(function () {
        $(window).bind('beforeunload', function () {
            return 'Beim Verlassen der Seite gehen alle Eingaben verloren.';
        });
    });

    questionIdCounter = getLastQuestionId();
});

function questionAmountChanged() {
    setDeleteQuestionButtonStatus();
}

function setDeleteQuestionButtonStatus() {
    var amountOfQuestions = $('.questions').children().length;
    $('.btnRemoveQuestion').prop('disabled', amountOfQuestions === 1);
}

$('#btnAdd').click(function () {
    questionIdCounter++;
    var $questions = $('.questions');
    var $lastQuestion = $questions.children().last();
    var $clone = $lastQuestion.clone(true, true);

    $questions.append($clone);

    var $answerOptions = $clone.find('.answ_option').find('.input-group');
    // reset first option
    $answerOptions.first()
        .find('input')
        .val('')
        .prop('name', 'answerOption[' + questionIdCounter + '][0]');
    // delete all other options
    $answerOptions.slice(1).remove();
    $clone.find('[name^=questionText]')
        .val('')
        .prop('name', 'questionText[' + questionIdCounter + ']');
    $clone.find('select')
        .val(1)
        .change()
        .prop('name', 'type_select['+ questionIdCounter +']');
    $clone.find('[name^=required]')
        .prop('checked', false)
        .prop('name', 'required[' + questionIdCounter + ']');
    setQuestionNumberDisplayed();
    questionAmountChanged();

    //prevent submit
    return false;
});

$('.btnRemoveQuestion').click(function() {
    $(this).closest('[name=question]').remove();
    setQuestionNumberDisplayed();
    setDeleteQuestionButtonStatus();
});

function updateQuestionDisplay (select) {
    var $typeSelect = $(select);
    var $question = $typeSelect.closest('[name^=question]');
    var $answerOptionsDiv = $question.find('.answ_option');
    var type = parseInt($typeSelect.find('option:selected').val());
    switch (type) {
        case 1:
            $answerOptionsDiv.hide();
            break;
        case 2:
            $answerOptionsDiv.hide();
            break;
        case 3:
            $answerOptionsDiv.show();
            break;
    }
}

function updateAnswerOptionsIndices(question) {
    $(question).find('.answ_option').find('.input-group').find('input').each(function(index, input) {
        var oldName = $(input).prop('name');
        if (oldName) {
            // only update second index! Note the first \] in the regexp
            // first index belongs to question, second to answer option
            var newName = oldName.replace(/\]\[[0-9]+\]/, '][' + index + ']');
            $(input).prop('name', newName);
        }
    });
}

function addNewAnswerOption(button) {
    var $clone = $(button).prev().clone(true, true).insertBefore(button);
    $clone.find('input').val('');
    updateAnswerOptionsIndices($(button).closest('[name^=question]'));
}

// Deleting answer options
function removeAnswerOption(answer) {
    var question = $(answer).closest('[name^=question]');
    if ($(question).find('.answ_option').children('div').length > 1) {
        $(answer).closest('.input-group').remove();
        updateAnswerOptionsIndices(question);
    }
}

function updateQuestionNumber(index, topDiv){
    var $heading = $(topDiv).find('.heading-reference');
    var currentText = $heading.text();
    $heading.text(currentText.replace(/#.*/, '#' + (index + 1)));
}

function setQuestionNumberDisplayed() {
    $('div').filter('.questions').children().each(updateQuestionNumber);
}

function getLastQuestionId() {
    try {
        return parseInt(/questionText\[([0-9]*)\]/g.exec($('.questions').children()
            .last()
            .find('[name^=questionText]')
            .prop('name')
        )[1]);
    }
    catch (e) {
        return 0;
    }
}
