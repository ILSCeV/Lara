/*
    Scripts for surveyView
    Reaction to click on edit delete buttons
*/
$(document).ready(function() {

    $('form').on( 'submit', function () {
        // For passworded schedules: check if a password field exists and is not empty
        // We will check correctness on the server side
        if ($('.panel-warning').find("[name^=password]").length
            && !$('.panel-warning').find("[name^=password]").val()) {
            var password = window.prompt('Bitte noch das Passwort für diese Umfrage eingeben:');
            $('.panel-warning').find("[name^=password]").val(password);

        } else {
            var password = $('.panel-warning').find("[name^=password]").val();
        }
    });

});

//source: http://stackoverflow.com/questions/2441565/how-do-i-make-a-div-element-editable-like-a-textarea-when-i-click-it
//On click of edid(Number) div, all divs in that row should be editable
$(".editRow1").click(function() {
    //save HTML within div
    var divText = $(".Name1").text();
    //create dynamic textarea
    var editableText = $("<textarea />");
    //fill textarea with text of former div
    editableText.val(divText);
    //replace div with textarea
    $(".Name1").replaceWith(editableText);
    //editableText.focus(); (optional)



});


//Replace edit icon with save icon
$('.editButton').click(function() {
    $('#display_advance').toggle('1000');
    $("i", this).toggleClass("fa-pencil fa-floppy-o");
});





