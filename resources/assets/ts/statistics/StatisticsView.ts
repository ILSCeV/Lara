import {safeGetLocalStorage, safeSetLocalStorage} from "../Utilities";
import 'bootstrap/js/dist/modal';
import 'bootstrap/js/dist/tab';
import * as bootbox from "bootbox"
import {translate} from "../Translate";

declare var userSection:any;

function getRowName(a: string | Element) {
    return $(a).children("td").eq(0).text();
}

function getRowShifts(a: string | Element) {
    return $(a).children("td").eq(1).text();
}

function getRowFlood(a: string | Element) {
  return $(a).children("td").eq(2).text();
}


type SortMode = 'name' | 'shifts' | 'flood'

function getRowCatcher(sortMode : SortMode) {
  switch (sortMode) {
  case "flood": return getRowFlood;
  case "shifts": return getRowShifts;
  case "name":
    default:
    return getRowName;
  }
}

//inspired by http://stackoverflow.com/questions/3160277/jquery-table-sort
function sortTable($table: JQuery, sortMode: SortMode, descending: boolean) {
    let rowCatcher = getRowCatcher(sortMode);
    let rows = $table
        .find("tbody")
        .find("tr")
        .toArray()
        .sort((a, b) => rowCatcher(a).localeCompare(rowCatcher(b), undefined, {'numeric': true}));
    if (descending) {
        rows.reverse();
    }
    rows.forEach(row => $table.append($(row)));
}

function updateSortIconStyle($table: JQuery, sortMode: SortMode, descending: boolean) {
    // remove specific sorting from all icons
    $table.find('.fa-sort, .fa-sort-up, .fa-sort-down')
        .removeClass('fa-sort-up')
        .removeClass('fa-sort-down')
        .addClass('fa-sort');
    // and add the current sorting order to the one that changed
    let icon = $table.find('.fa-sort, .fa-sort-up, .fa-sort-down')
        .filter(function () {
            return $(this).parent().data('sort') == sortMode;
        });
    icon.removeClass('fa-sort')
        .addClass(descending ? 'fa-sort-down' : 'fa-sort-up');
}

function sortLeaderboards(pSortIcon: JQuery) {
    let sortIcon = $(pSortIcon);
    let $tables = $('#memberStatisticsTabs').find('table');
    let wasAscending = sortIcon.hasClass('fa-sort-up');
    let sortMode = sortIcon.parent().data('sort') ;

    localStorage.setItem('preferredSortType', sortMode);
    localStorage.setItem('preferredSortOrder', wasAscending ? 'descending' : 'ascending');

    $tables.each(function(){
        sortTable($(this), sortMode, wasAscending);
        updateSortIconStyle($(this), sortMode, wasAscending);
    });
}


$( window ).on ( { load: () => {
    let preferredStatistics = safeGetLocalStorage('preferredStatistics');
    let preferredLeaderboards = safeGetLocalStorage('preferredLeaderboards');

    if (preferredStatistics == undefined) {
      safeSetLocalStorage('preferredStatistics', userSection);
    }
    if (preferredLeaderboards == undefined) {
      safeSetLocalStorage('preferredLeaderboards', userSection);
    }
  }
});

$(".fa-sort, .fa-sort-up, .fa-sort-down").on({ click:  (e) => {
    sortLeaderboards($(e.target));
}});

$('#memberStatisticsTabs').find('thead').find('td').click(function() {
    sortLeaderboards($($(this).find('i').first()));
});

$(".statisticClubPicker").find("a").click(function() {
    let clubName = $(this).text().trim();
    localStorage.setItem('preferredStatistics', clubName);
});

$(".leaderboardsClubPicker").find("a").click(function() {
    let leaderBoardName = $(this).text().trim();
    localStorage.setItem('preferredLeaderboards', leaderBoardName);
});


$(() => {
    let preferredStatistics = localStorage.getItem('preferredStatistics');
    if (preferredStatistics) {
        $('.statisticClubPicker').find('a').filter(function() {
            return $(this).text().trim() === preferredStatistics;
        })
            .first()
            .click();
    }

    let preferredLeaderboards = localStorage.getItem('preferredLeaderboards');
    if (preferredLeaderboards) {
        $('.leaderboardsClubPicker').find('a').filter(function() {
            return $(this).text().trim() === preferredLeaderboards;
        })
            .first()
            .click();
    }

    let preferredSortType = <SortMode> localStorage.getItem('preferredSortType');
    let preferredSortOrder = localStorage.getItem('preferredSortOrder');

    let $tables = $('#memberStatisticsTabs').find('table');
    let sortMode = preferredSortType ;
    let isDescending = preferredSortOrder === 'descending';

    $tables.each(function() {
        sortTable($(this), sortMode, isDescending);
        updateSortIconStyle($(this), sortMode, isDescending);
    })

});

////////////////
// Statistics //
////////////////



///////////////////////////////////////
// Show shifts for a selected person //
///////////////////////////////////////



declare var chosenPerson;
declare var chosenMonth, chosenYear;
declare var isMonthStatistic;
$('[name^=show-stats-person]').click(function(e) {
  e.preventDefault();
  // Initialise modal and show loading icon and message
  const dialog = <any> bootbox.dialog({
    title: translate('listOfShiftsDone') + chosenPerson,
    size: 'large',
    message: '<p><i class="fas fa-spin fa-spinner"></i>' + translate('loading') + '</p>',
    onEscape: () => {}
  });


  // Do all the work here after AJAX response is received
  function ajaxCallBackPersonStats(response) {

    // Parse and show response
    dialog.init(function(){
      dialog.find('.modal-dialog').attr('role','document');
      // Initialise table structure
      dialog.find('.modal-body').addClass("p-0").html(
        "<table id=\"person-shifts-overview\" class=\"table table-hover p-0\">"
        + "<thead>"
        + "<tr>"
        + "<th>#</th>"
        + "<th>" + translate('shift') + "</th>"
        + "<th>" + translate('event') + "</th>"
        + "<th class=\"statistics-section-highlight\"></th>"
        + "<th>" + translate('section') + "</th>"
        + "<th>" + translate('date') + "</th>"
        + "<th>" + translate('weight') + "</th>"
        + "</tr>"
        + "</thead>"
        +"</table>"
      );
      $("#person-shifts-overview").append("<tbody id='person-shifts-overview-body'></tbody>")

      // check for empty response
      if (response.length === 0)
      {
        $("#person-shifts-overview").append("<tbody><tr><td colspan=6>"  + translate('noShiftsInThisPeriod') + "</td></tr></tbody>");
      }

      // Fill with data received
      for (let i = 0; i < response.length; i++)
      {
        $("#person-shifts-overview-body").append(
          ""
          // Change background for shifts in other sections
          + "<tr" + (!response[i]["isOwnClub"] ? " class=\"other-section text-muted\"" : "") + ">"
          + "<td>"  + (1+i) + "</td>"
          + "<td>" + response[i]["shift"] + "</td>"
          + "<td>" + "<a href=\"/event/" + response[i]["event_id"] + "\">" + response[i]["event"] + "</a>" + "</td>"
          // Color-coding for different sections
          +"<td class=\"statistics-section-highlight palette-"+response[i]["sectionColor"]+"-500 bg\">&nbsp;</td>"
          + "<td>" + response[i]["section"] + "</td>"
          + "<td>" + response[i]["date"] + "</td>"
          + "<td>" + response[i]["weight"] + "</td>"
          + "</tr>");
      }

    });
  }

  // AJAX Request shifts for a person selected
  $.ajax({
    type: $( this ).prop( 'method' ),

    url: "/statistics/person/" + $(this).prop("id"),

    data: {
      // chosen date values from the view
      "month": chosenMonth,
      "year":  chosenYear,
      "isMonthStatistic": isMonthStatistic,

      // We use Laravel tokens to prevent CSRF attacks - need to pass the token with each requst
      "_token": $(this).find( 'input[name=_token]' ).val(),

      // Most browsers are restricted to only "get" and "post" methods, so we spoof the method in the data
      "_method": "get"
    },

    dataType: 'json',

    success: function(response){
      // external function handles the response
      ajaxCallBackPersonStats(response);
    },
  });

});
