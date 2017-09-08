import * as $ from "jquery"
import {initializeSectionFilters} from "./master";

// Scroll to current date/event if in mobile view in current month
$(() => {
    // check if we are in month view and if the today-marker exists
    const isMonthView = $('#month-view-marker').length;
    if (!isMonthView) {
        return;
    }

    initializeSectionFilters();

    if ($(window).width() < 978) {
        $('html, body').animate({ scrollTop: $(".scroll-marker").offset().top -80 }, 1000);
    };
});
