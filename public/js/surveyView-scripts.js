/*
    Scripts for surveyView
    Reaction to click on edit delete buttons
*/
$(document).ready(function() {

    $('form').on( 'submit', function () {
        // For passworded surveys: check if a password field exists and is not empty
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
//On click of edit(Number) div, all divs in that row should be editable
//Reworked below with generel Div Elements
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

//Edit of a Div element reworked
//use in form of $("div").click(divClicked):
//to swap a text field to an editable text field
    function divClicked () {
    //Textfield to work with.
    var editableText = $("<textarea/>");
    //First save old Text (this for whatever div element)
    var divText = $(this).text();
    //Fill it
    editableText.val(divText);
    //Now Replace the Div
    $(this).replaceWith(editableText);
    editableText.focus();
    editableText.blur(editableTextBlurred)
}

//Function to react to user leaving a text field in/after edit
    function editableTextBlurred () {
    //Variables that will be used
    var text = $(this).val();
    var editedText = $("<div>");

    editedText.text(text);
    $(this).replaceWith(editedText);
    $(editedText).click(divClicked);
}


//Replace edit icon with save icon
$('.editButton').click(function() {
    $('#display_advance').toggle('1000');
    $("i", this).toggleClass("fa-pencil fa-floppy-o");
});






