import * as $ from "jquery"

// Scroll to current date/event if in mobile view in current month
$(document).ready(function()
{
    // check if we are in month view and if the today-marker exists
    if ($('#month-view-marker').length && $(".scroll-marker").length)
    {
        if ($(window).width() < 978)
        {
            $('html, body').animate({ scrollTop: $(".scroll-marker").offset().top -80 }, 1000);
        };
    };
});
