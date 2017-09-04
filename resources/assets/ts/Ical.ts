import * as $ from "jquery"
import * as bootbox from "bootbox"
import translate from "Translate"
import * as Clipboard from "clipboard";

//////////
// ICal //
//////////

$('[name^=icalfeeds]').click(function () {

    var clipboard = null as Clipboard;
    // Initialise modal and show loading icon and message
    var dialog = bootbox.dialog({
        title: translate("icalfeeds"),
        size: 'large',
        message: '<p><i class="fa fa-spin fa-spinner"></i>' + translate('loading') + '</p>',
        callback: function () {
            if (clipboard !== null) {
                clipboard.destroy();
            }
        }
    });


    $.ajax({
        url: "/ical/links/",

        data: {
            // We use Laravel tokens to prevent CSRF attacks - need to pass the token with each requst
            "_token": $(this).find('input[name=_token]').val(),

        },

        dataType: 'json',

        success: function (response) {
            // we don't need to show this field, if the person does not exists, instead we show a warning
            var remindPersonalIcalInput;
            if ((typeof response['personal'] === 'undefined' || response['personal'] === null)) {
                if (typeof response['isPublic'] !== 'undefined' && response['isPublic'] !== true) {
                    remindPersonalIcalInput = '<div class="alert alert-warning"> <span class="glyphicon glyphicon-warning-sign"></span> ' + translate("noPrivateCalendarWarn") + ' </div>';
                } else {
                    remindPersonalIcalInput = "";
                }
            } else {
                remindPersonalIcalInput = '<div class="form-group left-padding-16 padding-right-16 col-md-12 col-xs-12">' +
                    translate('remindsBeforeShiftStart') + '&nbsp;&nbsp;' +
                    '<input id="personalIcalRemindValue" type="number" value="0" width="20%"/>' + translate('minutes') +
                    '</div>';
            }

            var legend = "";
            if (typeof response['isPublic'] !== 'undefined' && response['isPublic'] !== true) {
                legend = '<div class="all-sides-padding-16">' + translate('legend') + ': <span class="bg-warning" style="border: 1px solid black;"> <span class="glyphicon">&nbsp;</span></span> ' + translate("internalUsageOnly") + '</div>  ';
            }

            dialog.find('.modal-body').addClass("no-padding").html("" +
                remindPersonalIcalInput +
                legend +
                "<table class='table table-hover'>" +
                "<thead><tr>" +
                "<th></th>" +
                "<th> " + translate('iCalendarlink') + " </th>" +
                "</tr></thead>" +
                "<tbody id='icalTbody'></tbody>" +
                "</table>"
            );

            var icalTbody = $('#icalTbody');

            if (!(typeof response['personal'] === 'undefined' || response['personal'] === null)) {
                icalTbody.append('<tr class="warning">' +
                    '<td> ' + translate('personalFeed') + '<span id="ical_personal_link" class="hidden">' + response['personal'] + '</span>  </td>' +
                    '<td> ' +
                    '<div class="input-group"> ' +
                    '<input class="form-control " id="ical_personal_input" type="text" value="' + response['personal'] + '"/>' +
                    '<span class="input-group-btn">' +
                    '<button type="button" class=" icalinput btn btn-small" data-clipboard-target="#ical_personal_input" ><span class="fa fa-clipboard"></span> </button> ' +
                    '</span> ' +
                    '</div>' +
                    '</td>' +
                    '</tr>')
            }

            var allPublicEvents = response['allPublicEvents'];
            var locationsNames = response['locationName'];
            var locations = response['location'];
            var locationsPublic = response['locationPublic'];

            icalTbody.append('<tr>' +
                '<td></td>' +
                '<td>' +
                '<div class="input-group"> ' +
                '<input class="form-control " id="icalAllPublicEvents" type="text" value="' + allPublicEvents + '"/>' +
                '<span class="input-group-btn">' +
                '<button type="button" class=" icalinput btn btn-small" data-clipboard-target="#icalAllPublicEvents" ><span class="fa fa-clipboard"></span> </button> ' +
                '</span>' +
                '</div>' + '</td>' +
                '</tr>');

            locationsNames.forEach(function (element, idx) {
                icalTbody.append('<tr>' +
                    '<td>' + element + '</td>' +
                    '<td>' +
                    '<div class="input-group"> ' +
                    '<input class="form-control " type="text" id="locationPublic' + idx + '" value="' + locationsPublic[idx][element] + '"/>' +
                    '<span class="input-group-btn">' +
                    '<button type="button" class=" icalinput btn btn-small" data-clipboard-target="#locationPublic' + idx + '" ><span class="fa fa-clipboard"></span> </button> ' +
                    '</span>' +
                    '</div>' +
                    '</td>' +
                    '</tr>');
            });
            if (typeof response['isPublic'] !== 'undefined' && response['isPublic'] !== true) {
                locationsNames.forEach(function (element, idx) {
                    icalTbody.append('<tr class="warning">' +
                        '<td> private ' + element + '</td>' +
                        '<td>' +
                        '<div class="input-group"> ' +
                        '<input class="form-control " type="text" id="location' + idx + '" value="' + locations[idx][element] + '"/>' +
                        '<span class="input-group-btn">' +
                        '<button type="button" class=" icalinput btn btn-small" data-clipboard-target="#location' + idx + '"  ><span class="fa fa-clipboard"></span> </button> ' +
                        '</span>' +
                        '</div>' +
                        '</td>' +
                        '</tr>');
                });
            }
            $('#personalIcalRemindValue').change(function () {
                $('#ical_personal_input').val($('#ical_personal_link').text() + $('#personalIcalRemindValue').val());
            });
            clipboard = new Clipboard('.icalinput');
        }
    });
});

