/*
    Scripts for surveyView
    Reaction to click on edit delete buttons
*/
$(document).ready(function() {
    //currently nothing
});

//source: http://stackoverflow.com/questions/2441565/how-do-i-make-a-div-element-editable-like-a-textarea-when-i-click-it
$("div").click(function() {
    //save HTML within div
    var divHtml = $(this).html(); // notice "this" instead of a specific #myDiv
    //create dynamic textarea
    var editableText = $("<textarea />");
    //fill textarea with text of former div
    editableText.val(divHtml);
    //replace div with textarea
    $(this).replaceWith(editableText);
    //editableText.focus(); (optional) 
});
