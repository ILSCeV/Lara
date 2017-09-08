import * as $ from "jquery"
import {initializeSectionFilters} from "./master";
import {ToggleButton} from "../ToggleButton";
import {makeLocalStorageAction, makeClassToggleAction} from "../ToggleAction";
import {safeGetLocalStorage} from "../Utilities";
import {translate} from "../Translate";
import * as Isotope  from "../../../../node_modules/isotope-layout/js/isotope.js"

$(() => {
    const isWeekView = $('.isotope').length > 0;

    if (!isWeekView) {
        return;
    }

    const isotope = new Isotope('.isotope');

    isotope.arrange({
        itemSelector: '.element-item',
        layoutMode: 'masonry',
        masonry: {
            columnWidth: '.grid-sizer'
        },
        getSortData: {
            name: '.name',
            symbol: '.symbol',
            number: '.number parseInt',
            category: '[data-category]',
            weight: function (itemElem) {
                var weight = $(itemElem).find('.weight').text();
                return parseFloat(weight.replace(/[\(\)]/g, ''));
            }
        }
    });

    initializeSectionFilters(isotope);

    const shiftTimes = new ToggleButton("toggle-shift-time", () => $(".shift-time").is(":visible"));

    shiftTimes.addActions([
        makeLocalStorageAction("shiftTime", "show", "hide"),
        makeClassToggleAction(".shift-time", "hide", false),
        () => isotope.layout()
    ])
        .setToggleStatus(safeGetLocalStorage("shiftTime") == "show")
        .setText(translate("shiftTime"));

    const takenShifts = new ToggleButton("toggle-taken-shifts", () => $("div.green").closest(".row").hasClass("hide"));
    takenShifts.addActions([
        makeLocalStorageAction("onlyEmptyShifts", "true", "false"),
        makeClassToggleAction($("div.green").closest(".row"), "hide", true),
        () => isotope.layout()
    ])
        .setToggleStatus(safeGetLocalStorage("onlyEmptyShifts") == "true")
        .setText(translate("onlyEmpty"));

    const comments = new ToggleButton("toggle-all-comments", () => !$('[name^=comment]').hasClass("hide"));

    comments.addActions([
        makeLocalStorageAction("allComments", "show", "hide"),
        makeClassToggleAction("[name^=comment]", "hide", false),
        () => isotope.layout()
    ])
        .setToggleStatus(safeGetLocalStorage("allComments") == "show");

    const weekMonSun = translate('mondayToSunday');
    const weekWedTue = translate('wednesdayToTuesday');

    const weekStart = new ToggleButton("toggle-week-start", () => safeGetLocalStorage("weekStart") == "monday", "btn-primary", "btn-success");

    weekStart.addActions([
        makeLocalStorageAction("weekStart", "monday", "wednesday"),
        makeClassToggleAction(".week-mo-so", "hide", true),
        makeClassToggleAction(".week-mi-di", "hide", false),
        (isActive: boolean) => weekStart.setText(isActive ? weekMonSun : weekWedTue),
        () => isotope.layout()
    ])
        .setToggleStatus(safeGetLocalStorage("weekStart") == "monday");

    // Show/hide single comment
    $('.showhide').click(function (e) {
        const comment = $(this).parent().next('[name^=comment]');
        comment.toggleClass("hide", comment.is(":visible"));
        isotope.layout();
    });

    // button to remove events from week view - mostly for printing
    $('.hide-event').click(function (e) {
        $(this).parents(".element-item").eq(0).addClass("hide");
        isotope.layout();
    });
});
