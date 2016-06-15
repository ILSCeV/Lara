/*
    Scripts for surveyView
    Reaction to click on edit delete buttons
*/
$(document).ready(function() {

    alert("Hello! I am an alert box!!");

    var docHeight = $(window).height();
    var footerHeight = $('#footer').height();
    var footerTop = $('#footer').position().top + footerHeight;

    if (footerTop < docHeight) {
        $('#footer').css('margin-top', (docHeight - footerTop) + 'px');
    }
});
