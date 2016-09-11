// Binds window if user has made input
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


// Changes dropdown style for mobile view
$(function () {
    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
        $('.selectpicker').selectpicker('mobile');
    }
});


// Checks if user has made input -> if yes, a popup appears if user wants to leave the site
window.onload = function () {
    $('.questions' || '.input_checkboxitem' || '.selectpicker').change(function () {
        $(window).bind('beforeunload', function () {
            return 'Beim Verlassen der Seite gehen alle Eingaben verloren.';
        });
    });

    $("form").submit(function () {
        if ($('#btnAdd') !== '.click')
            $(window).unbind('beforeunload');

        if ($("[data-id=clonedInput_edit]").attr('style') == 'display: none') {
            $("[data-id=clonedInput_edit]").remove();
        }
    });
};

function questionAmountChanged() {
    updateAllQuestionNumbers();
    setDeleteQuestionButtonStatus();
}

function setDeleteQuestionButtonStatus() {
    var amountOfQuestions = $('.questions').children().length;
    if (amountOfQuestions > 1) {
        $('.btnRemoveQuestion').attr('disabled', false);
    }
    else {
        $('.btnRemoveQuestion').attr('disabled', true);
    }
}

$('#btnAdd').click(function () {
    var $questions = $('.questions');
    var $lastQuestion = $questions.children().last();
    var $clone = $lastQuestion.clone();
    var $selectClone = $clone.find('select').clone();
    $clone.find('.bootstrap-select').remove();
    $clone.find('fieldset').prepend($selectClone);
    $selectClone.selectpicker();
    $selectClone.change(function() {
        updateQuestionDisplay(this);
    });
    $questions.append($clone);
    questionAmountChanged();
});
$('.btnRemoveQuestion').click(function() {
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
};
$('.selectpicker').change(function() {
    updateQuestionDisplay(this);
});

function addNewAnswerOption(button) {
    var $clone = $(button).prev().clone().insertBefore(button);
    $clone.find('input').attr('value', ' ');
}

// Deleting answer options
function removeAnswerOption(answer) {
    $(answer).closest('.input-group').remove();
}

function displayQuestionAccordingToType(selectElement){
    var $typeSelect = $(selectElement);
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

function updateQuestionNumber(index, topDiv){
    var $heading = $(topDiv).find('.heading-reference');
    var currentText = $heading.text();
    $heading.text(currentText.replace(/#.*/, '#' + (index + 1)));
}

function updateAllQuestionNumbers() {
    $('div').filter('.questions').children().each(updateQuestionNumber);
}

$(function() {
    $('.selectpicker').each(displayQuestionAccordingToType);
});

// Checks question type -> has to be selected
$("form").submit(function() {

    if ($('#field_type').val() === '0') {
        bootbox.alert("Frage-Typ muss bei Frage 1 ausgewählt sein");
        return false;
    }
});


// Sets a hidden input field to true for standard users (survey -> is private)
$('form').on('submit', function () {
    if ($('#required1').attr('disabled')) {
        $('#required1_hidden').attr('checked', 'true');
    }
});

