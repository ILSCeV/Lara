import * as $ from "jquery"
import {translate} from "./Translate"
import * as bootbox from "bootbox"
import {ToggleButton} from "./ToggleButton";
import {makeClassToggleAction, makeLocalStorageAction} from "./ToggleAction";
import {safeGetLocalStorage} from "./Utilities";


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

    const takenShifts = new ToggleButton("toggle-taken-shifts", () => $('.shift_taken').closest('.row').hasClass("hide"));
    takenShifts.addActions([
        makeLocalStorageAction("onlyEmptyShifts", "true", "false"),
        makeClassToggleAction($('.shift_taken').closest('.row'), "hide", true)
    ])
        .setToggleStatus(safeGetLocalStorage("onlyEmptyShifts") == "true")
        .setText(translate("onlyEmpty"));

    const shiftTimes = new ToggleButton("toggle-shift-time", () => $('.shift-time').is(":visible"));

    shiftTimes.addActions([
        makeLocalStorageAction("shiftTime", "show", "hide"),
        makeClassToggleAction(".shift-time", "hide", false)
    ])
        .setToggleStatus(safeGetLocalStorage("shiftTime") == "show")
        .setText(translate("shiftTime"));
});


