import * as Isotope from "isotope-layout/js/isotope";
import {safeGetLocalStorage, safeSetLocalStorage} from "../Utilities";
import {ToggleButton} from "../ToggleButton";
import {makeClassToggleAction, makeLocalStorageAction} from "../ToggleAction";
import {translate} from "../Translate";


/** request param
 * filter="mi-di"
 */
declare var extraFilter: any;

export const showAllActiveSections = () => {
  let $sectionSelect = $('#section-filter-selector');
  $(".section-filter").addClass('d-none');
  $(".label-filters").addClass('d-none');
  if ((<string[]>$sectionSelect.val()).length == 0) {
    $('#label-none').removeClass('d-none');
  } else {
    (<string[]>$sectionSelect.val()).forEach(filter => {
      $(`.${filter.slice(7)}`).removeClass('d-none');
      $(`#label-section-${filter.slice(15)}`).removeClass('d-none');
    });
  }
  // isotope ? isotope.layout() : null;
};

export const initFilters = () => {

  //////////////////////////////////////////////////////
  // Month view without Isotope, section filters only //
  //////////////////////////////////////////////////////
  const isMonthView = $('#month-view-marker').length;
  const isWeekView = $('#week-view-marker').length > 0;
  const isDayView = $('#day-view-marker').length;


  const initializeSectionFilters = (isotope: typeof Isotope = null) => {

    let enabledSections = [];

    // Handle clicking on a section label
    $('.label-filters').click((e) => {
      // Deselect the clicked section
      let section = (<HTMLSpanElement>e.target).id.slice(14);
      // Update the local storage
      safeSetLocalStorage("filter-section-" + section, "hide");
      // Uncheck the select option
      $sectionSelect.selectpicker('val',  $sectionSelect.val().filter(sec => sec !== "filter-section-"+section));
      // Refresh elements
      showAllActiveSections();
      e.preventDefault();
    });

    let $sectionSelect = $('#section-filter-selector');

    $sectionSelect.on('changed.bs.select', (event, clickedIndex, newValue, oldValue) => {
      //Always set all of them, in case the user selected "Select/Deselect all"
      $sectionSelect.find('option').each((i: number, option: HTMLOptionElement) => {
        safeSetLocalStorage(option.value, option.selected ? "show" : "hide");
      });
      showAllActiveSections();
    });

    $sectionSelect.find('option').each((i: number, option: HTMLOptionElement) => {
      if (safeGetLocalStorage(option.value) !== "hide") {
        enabledSections.push(option.value);
      }
    });

    //Enable all sections enabled in the localStorage inside the select
    $sectionSelect.removeClass("d-none");
    $sectionSelect.selectpicker('val', enabledSections);
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
      const allComments = new ToggleButton("toggle-all-comments", () => ! $('[name^=comment]').hasClass("hide"));

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
    const weekWedTue = translate('wednesdayToTuesday');

    const weekStart = new ToggleButton("toggle-week-start", () => safeGetLocalStorage("weekStart") == "monday", "btn-primary", "btn-success");

    weekStart.addActions([makeLocalStorageAction("weekStart", "monday", "wednesday"),
      makeClassToggleAction(".week-mo-so", "hide", true),
      makeClassToggleAction(".week-mi-di", "hide", false),
      (isActive: boolean) => weekStart.setText(isActive ? weekMonSun : weekWedTue),
      //() => isotope.layout()
    ]);

    switch (extraFilter) {
      case 'mi-di':
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
