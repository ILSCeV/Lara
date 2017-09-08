import * as $ from "jquery"
import {safeGetLocalStorage} from "../Utilities";
import {ToggleButton} from "../ToggleButton";
import {makeLocalStorageAction} from "../ToggleAction";
import * as Isotope  from "../../../../node_modules/isotope-layout/js/isotope.js"

// Enable Tooltips
$(function () { $("[data-toggle='tooltip']").tooltip({trigger: "hover"}); });

// Automatically close notifications after 4 seconds (4000 milliseconds)
window.setTimeout(function() {
    $(".message").fadeTo(1000, 0).slideUp(500, function(){
        $(this).alert('close');
    });
}, 4000);

export const initializeSectionFilters = (isotope: typeof Isotope = null) => {
    let sectionFilters = [];
    $.each($('.section-filter-selector'), function () {
        sectionFilters.push($(this).prop('id'));
    });

    const showAllActiveSections = () => {
        $(".section-filter").hide();
        sectionFilters
            .filter(filter => safeGetLocalStorage(filter) !== "hide")
            .forEach(filter => $(`.${filter.slice(7)}`).show())
    };

    const rebuildIsotopeLayout = () => isotope ? isotope.layout() : null;
    
    sectionFilters.forEach((filterName) => {
        const sectionButton = new ToggleButton(filterName, () => $(`#${filterName}`).hasClass("btn-primary"));

        sectionButton.addActions([
            makeLocalStorageAction(filterName, "show", "hide"),
            showAllActiveSections,
            rebuildIsotopeLayout
        ])
            .setToggleStatus(safeGetLocalStorage(filterName) !== "hide");
    });
}
