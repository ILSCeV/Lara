import * as $ from "jquery"
import {translate} from "./Translate"
import * as bootbox from "bootbox"

const safeSetLocalStorage = (key: string, prop: any) => {
    if (typeof(Storage) !== "undefined") {
        localStorage.setItem(key, prop);
    }
}

const safeGetLocalStorage = (key: string) => {
    if (typeof(Storage) !== "undefined") {
        return localStorage.getItem(key);
    }
    return undefined;
}

const isWeekView = $('.isotope').length > 0;
$(() => {
    $('#pubishEventLnkBtn').click(() => {
        bootbox.confirm(translate('confirmPublishingEvent'), (result) => {
            if (result) {
                let targetLocation = $('#pubishEventLnkBtn').data('href');
                window.location.href = targetLocation;
            }
        });
    });
    $('#unPublishEventLnkBtn').click(() => {
        bootbox.confirm(translate('confirmUnpublishingEvent'), (result) => {
            if (result) {
                let targetLocation = $('#unPublishEventLnkBtn').data('href');
                window.location.href = targetLocation;
            }
        });
    });
    if (isWeekView) {
        return;
    }
    $('#toggle-taken-shifts').text(translate("onlyEmpty"));

    if (safeGetLocalStorage("onlyEmptyShifts") === "true") {
        $('div.green').closest('.row').addClass('hide');
        $('#toggle-taken-shifts').addClass("btn-primary");
    }
    else if (safeGetLocalStorage("onlyEmptyShifts") == "false") {
        $('div.green').closest('.row').removeClass('hide');
        $('#toggle-taken-shifts').removeClass("btn-primary");
    }

    // Filter buttons action
    $('#toggle-taken-shifts').click(() => {
        // all shifts are shown, intent to hide full shifts
        const areAllShiftsShown = $('div.green').closest('.row').is(":visible");
        if (areAllShiftsShown) {
            // save selection in local storage
            safeSetLocalStorage("onlyEmptyShifts", "true");

            // change state, change button
            $('div.green').closest('.row').addClass('hide');
            $('#toggle-taken-shifts').addClass("btn-primary");
        }
        else {
            safeSetLocalStorage("onlyEmptyShifts", "false");
            // change state, change button
            $('div.green').closest('.row').removeClass('hide');
            $('#toggle-taken-shifts').removeClass("btn-primary");
        }
    });

    $('#toggle-shift-time').text(translate('shiftTime'));

    // Apply saved preferences from local storage on pageload
    if (safeGetLocalStorage("shiftTime") == "show") {
        $('.entry-time').removeClass("hide");
        $('#toggle-shift-time').addClass("btn-primary");
    }
    else if (safeGetLocalStorage("shiftTime") == "hide") {
        $('.shift-time').addClass("hide");
        $('#toggle-shift-time').removeClass("btn-primary");
    }

    // Filter buttons action
    $('#toggle-shift-time').click(() => {

        const areTimesShown = $('.shift-time').is(":visible");
        if (areTimesShown) {
            // save selection in local storage
            safeSetLocalStorage("shiftTime", "hide");

            // change state, change button
            $('.shift-time').addClass("hide");
            $('#toggle-shift-time').removeClass("btn-primary");
        }
        else {
            // save selection in local storage
            safeSetLocalStorage("shiftTime", "show");
            // change state, change button
            $('.shift-time').removeClass("hide");
            $('#toggle-shift-time').addClass("btn-primary");
        }
    });
});


