import * as Isotope from "isotope-layout/js/isotope";
import { safeGetLocalStorage, safeSetLocalStorage } from "../Utilities";
import { ToggleButton } from "../ToggleButton";
import { makeClassToggleAction, makeLocalStorageAction } from "../ToggleAction";
import { translate } from "../Translate";

/** request param
 * filter="mi-di"
 */
declare var extraFilter: any;

const allSectionsCheckboxes: NodeListOf<HTMLInputElement> = document.querySelectorAll('#offcanvas input[type=checkbox]');
declare var sectionCountString: string;

export const showAllActiveSections = () => {
  let enabledSections = Array.from(allSectionsCheckboxes, (c, i) => { return c.checked ? c.dataset : null })
    .filter(e => e !== null);

  $(".section-filter").addClass('d-none');
  enabledSections.forEach(s => {
    $(`.section-${s.sectionId}`).removeClass('d-none');
  });

  const filterButton = <HTMLButtonElement>document.getElementById('filterCountButtonText');

  // Set x of y selected
  filterButton.textContent =
    sectionCountString.replace(':sel', String(enabledSections.length))
      .replace(':total', String(allSectionsCheckboxes.length));

  // Set the tooltip text
  filterButton.dataset.bsTitle = enabledSections.map(e => e.section).join(', ');
};

export const initFilters = () => {
  //////////////////////////////////////////////////////
  // Month view without Isotope, section filters only //
  //////////////////////////////////////////////////////
  const isMonthView: boolean = $('#month-view-marker').length > 0;
  const isWeekView: boolean = $('#week-view-marker').length > 0;
  const isDayView: boolean = $('#day-view-marker').length > 0;


  const initializeSectionFilters = (isotope: typeof Isotope = null) => {

    const enableAllButton: HTMLButtonElement = document.querySelector("#sections-filter-enable-all");
    const disableAllButton: HTMLButtonElement = document.querySelector("#sections-filter-disable-all");

    enableAllButton.addEventListener("click", () => {
      allSectionsCheckboxes.forEach((c: HTMLInputElement) => {
        c.checked = true
        safeSetLocalStorage(`filter-section-${c.dataset.sectionId}`, "show");
        showAllActiveSections();
      });
    });
    disableAllButton.addEventListener("click", () => {
      allSectionsCheckboxes.forEach((c: HTMLInputElement) => {
        c.checked = false
        safeSetLocalStorage(`filter-section-${c.dataset.sectionId}`, "hide");
        showAllActiveSections();
      });
    });

    // Handle toggling on a section label
    allSectionsCheckboxes.forEach((c: HTMLInputElement) => {
      c.addEventListener("change", handleSectionCheckboxChanged)
    });

    function handleSectionCheckboxChanged(ev: Event): any {
      safeSetLocalStorage(`filter-section-${this.dataset.sectionId}`, this.checked ? "show" : "hide");
      showAllActiveSections();
    }

    //read the saved state from localstorage
    allSectionsCheckboxes.forEach(c => {
      c.checked = !(safeGetLocalStorage(`filter-section-${c.dataset.sectionId}`) === "hide")
    })
    showAllActiveSections();
  };




  if (isMonthView || isWeekView || isDayView) {
    initializeSectionFilters();
  }

  if (isWeekView) {
    //const isotope = new Isotope('.isotope');

    /////////////////////////////////////////////////////////
    // Week view with Isotope, section and feature filters //
    /////////////////////////////////////////////////////////

    // init Isotope
    /*
    isotope.arrange({
      itemSelector: '.element-item',
      percentPosition: true,
      masontryHorizontal:
        {
          columnWidth: '.element-size'
        },
      getSortData:
        {
          name: '.name',
          symbol: '.symbol',
          number: '.number parseInt',
          category: '[data-category]',
          weight: function( itemElem )
          {
            var weight = $( itemElem ).find('.weight').text();
            return parseFloat( weight.replace( /[\(\)]/g, '') );
          }
        }
    });


    (<any>window).isotope = isotope;

    /////////////////////
    // Section filters //
    /////////////////////

    // get all sections from buttons we created while rendering on the backend side
    initializeSectionFilters(isotope);
    */

    /////////////////////
    // Feature filters //
    /////////////////////

    //////////////////////////////
    // Show/hide time of shifts //
    //////////////////////////////
    const shiftTimes = new ToggleButton("toggle-shift-time", () => $(".shift-time").is(":visible"));

    shiftTimes.addActions([
      makeLocalStorageAction("shiftTime", "show", "hide"),
      makeClassToggleAction(".shift-time", "hide", false),
      // () => isotope.layout()
    ])
      .setToggleStatus(safeGetLocalStorage("shiftTime") == "show")
      .setText(translate("shiftTime"));
    ////////////////////////////
    // Show/hide taken shifts //
    ////////////////////////////
    const takenShifts = new ToggleButton("toggle-taken-shifts", () => $(".shift_taken").closest(".shiftRow").hasClass("hide"));
    takenShifts.addActions([
      makeLocalStorageAction("onlyEmptyShifts", "true", "false"),
      makeClassToggleAction($(".shift_taken").closest(".shiftRow"), "hide", true),
      //() => isotope.layout()
    ])
      .setToggleStatus(safeGetLocalStorage("onlyEmptyShifts") == "true")
      .setText(translate("onlyEmpty"));
    ////////////////////////////
    // Show/hide all comments //
    ////////////////////////////

    // Constraint: limits usage of this filter to week view only
    if ($('#week-view-marker').length) {
      const allComments = new ToggleButton("toggle-all-comments", () => !$('[name^=comment]').hasClass("hide"));

      allComments.addActions([
        makeLocalStorageAction("allComments", "show", "hide"),
        makeClassToggleAction("[name^=comment]", "hide", false),
        //  () => isotope.layout()
      ])
        .setToggleStatus(safeGetLocalStorage("allComments") == "show");
    }


    ///////////////////////////////////////////////
    // Week view changer: start Monday/Wednesday //
    ///////////////////////////////////////////////


    // set translated strings
    const weekMonSun = translate('mondayToSunday');
    const weekWedTue = translate('tuesdayToMonday');

    const weekStart = new ToggleButton("toggle-week-start", () => safeGetLocalStorage("weekStart") == "monday", "btn-primary", "btn-success");

    weekStart.addActions([makeLocalStorageAction("weekStart", "monday", "wednesday"),
    makeClassToggleAction(".week-mo-so", "hide", true),
    makeClassToggleAction(".week-di-mo", "hide", false),
    (isActive: boolean) => weekStart.setText(isActive ? weekMonSun : weekWedTue),
      //() => isotope.layout()
    ]);

    switch (extraFilter) {
      case 'di-mo':
        weekStart.setToggleStatus(true);
        break;
      case 'mo-so':
        weekStart.setToggleStatus(false);
        break;
      default:
        weekStart.setToggleStatus(safeGetLocalStorage("weekStart") == "monday");
    }

    // button to remove events from week view - mostly for printing
    $('.hide-event').on({
      click: function (e) {
        $(this).parents(".element-item").eq(0).addClass("hide");
        //isotope.layout();
      }
    });
  }
};
