import * as $ from "jquery"
import translate from "../Translate"

///////////////////////////////////////
// Show shifts for a selected person //
///////////////////////////////////////

declare var chosenPerson;
declare var chosenMonth, chosenYear;
declare var isMonthStatistic;

$('[name^=show-stats-person]').click(function () {

    // Initialise modal and show loading icon and message
    var dialog = <any> bootbox.dialog({
        title: translate('listOfShiftsDone') + chosenPerson,
        size: 'large',
        message: '<p><i class="fa fa-spin fa-spinner"></i>' + translate('loading') + '</p>',
        onEscape: () => {
        }
    });


    // Do all the work here after AJAX response is received
    function ajaxCallBackPersonStats(response) {

        // Parse and show response
        dialog.init(function () {

            // Initialise table structure
            dialog.find('.modal-body').addClass("no-padding").html(
                "<table id=\"person-shifts-overview\" class=\"table table-hover no-padding\">"
                + "<thead>"
                + "<tr>"
                + "<th>#</th>"
                + "<th>" + translate('shift') + "</th>"
                + "<th>" + translate('event') + "</th>"
                + "<th>" + translate('section') + "</th>"
                + "<th>" + translate('date') + "</th>"
                + "<th>" + translate('weight') + "</th>"
                + "</tr>"
                + "</thead>"
                + "</table>"
            );

            // check for empty response
            if (response.length === 0) {
                $("#person-shifts-overview").append("<tr><td colspan=6>" + translate('noShiftsInThisPeriod') + "</td></tr>");
            }

            // Fill with data received
            for (var i = 0; i < response.length; i++) {
                $("#person-shifts-overview").append(
                    "<tbody>"
                    // Change background for shifts in other sections
                    + "<tr" + (!response[i]["isOwnClub"] ? " class=\"active text-muted\"" : "") + ">"
                    + "<td>" + (1 + i) + "</td>"
                    + "<td>" + response[i]["shift"] + "</td>"
                    + "<td>" + "<a href=\"../../event/" + response[i]["event_id"] + "\">" + response[i]["event"] + "</a>" + "</td>"
                    // Color-coding for different sections
                    + "<td class=\"" + response[i]["section"] + "-section-highlight\">" + response[i]["section"] + "</td>"
                    + "<td>" + response[i]["date"] + "</td>"
                    + "<td>" + response[i]["weight"] + "</td>"
                    + "</tr>"
                    + "</tbody>");
            }

        });
    }

    // AJAX Request shifts for a person selected
    $.ajax({
        type: $(this).prop('method'),

        url: "/statistics/person/" + $(this).prop("id"),

        data: {
            // chosen date values from the view
            "month": chosenMonth,
            "year": chosenYear,
            "isMonthStatistic": isMonthStatistic,

            // We use Laravel tokens to prevent CSRF attacks - need to pass the token with each requst
            "_token": $(this).find('input[name=_token]').val(),

            // Most browsers are restricted to only "get" and "post" methods, so we spoof the method in the data
            "_method": "get"
        },

        dataType: 'json',

        success: function (response) {
            // external function handles the response
            ajaxCallBackPersonStats(response);
        },
    });

});
