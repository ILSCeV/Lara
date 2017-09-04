import * as $ from "jquery"
import { translate } from "./Translate"
import "isotope-layout"
import * as Isotope  from "../../../node_modules/isotope-layout/js/isotope.js"
import * as bootbox from "bootbox"
import {ToggleButton} from "./ToggleButton";
import {makeLocalStorageAction, makeClassToggleAction} from "./ToggleAction";
import {safeGetLocalStorage} from "./Utilities";

const jQuery = $;
/////////////
// Filters //
/////////////


$(function() {
    //////////////////////////////////////////////////////
    // Month view without Isotope, section filters only //
    //////////////////////////////////////////////////////
    const isMonthView = $('#month-view-marker').length;
    const isWeekView = $('.isotope').length > 0;


    const initializeSectionFilters = (isotope: typeof Isotope = null) => {
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

        sectionFilters.forEach((filterName) => {
            const sectionButton = new ToggleButton(filterName, () => $(`#${filterName}`).hasClass("btn-primary"));

            sectionButton.addActions([
                makeLocalStorageAction(filterName, "show", "hide"),
                showAllActiveSections,
                () => isotope ? isotope.layout() : null
            ])
                .setToggleStatus(safeGetLocalStorage(filterName) !== "hide");
        });
    }

    if (isMonthView || isWeekView) {
        initializeSectionFilters();
    }

    if (isWeekView) {
        const isotope = new Isotope('.isotope');

        /////////////////////////////////////////////////////////
        // Week view with Isotope, section and feature filters //
        /////////////////////////////////////////////////////////

        // init Isotope
        isotope.arrange({
            itemSelector: '.element-item',
            layoutMode: 'masonry',
            masonry:
                {
                    columnWidth: '.grid-sizer'
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

        /////////////////////
        // Section filters //
        /////////////////////

        // get all sections from buttons we created while rendering on the backend side
        initializeSectionFilters(isotope);

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
            () => isotope.layout()
        ])
            .setToggleStatus(safeGetLocalStorage("shiftTime") == "show")
            .setText(translate("shiftTime"));
        ////////////////////////////
        // Show/hide taken shifts //
        ////////////////////////////
        const takenShifts = new ToggleButton("toggle-taken-shifts", () => $("div.green").closest(".row").hasClass("hide"));
        takenShifts.addActions([
            makeLocalStorageAction("onlyEmptyShifts", "true", "false"),
            makeClassToggleAction($("div.green").closest(".row"), "hide", true),
            () => isotope.layout()
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
                () => isotope.layout()
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

        weekStart.addActions([
            makeLocalStorageAction("weekStart", "monday", "wednesday"),
            makeClassToggleAction(".week-mo-so", "hide", true),
            makeClassToggleAction(".week-mi-di", "hide", false),
            (isActive: boolean) => weekStart.setText(isActive ? weekMonSun : weekWedTue),
            () => isotope.layout()
        ])
            .setToggleStatus(safeGetLocalStorage("weekStart") == "monday");

        // Show/hide comments
        $('.showhide').click(function(e) {
            const comment = $(this).parent().next('[name^=comment]');
            comment.toggleClass("hide", comment.is(":visible"));
            isotope.layout();
        });

        // button to remove events from week view - mostly for printing
        $('.hide-event').click(function(e) {
            $(this).parents(".element-item").eq(0).addClass("hide");
            isotope.layout();
        });
    }
});




///////////////
// All views //
///////////////

// Enable Tooltips
$(function () { $("[data-toggle='tooltip']").tooltip({trigger: "hover"}); });

// Automatically close notifications after 4 seconds (4000 milliseconds)
window.setTimeout(function() {
    $(".message").fadeTo(1000, 0).slideUp(500, function(){
        $(this).alert('close'); 
    });
}, 4000);



// Own shift highlighting 
$('[name^=btn-submit-change]').click(function() {
    $(this).parents('.row').removeClass('my-shift');
});

// Dropdown hiding fix
$('input').focusout(function() {
    if ($(this).prop('placeholder') === '=FREI=') {
        // hack to allow for click to register before focusout is called
        setTimeout(function () {
            $('.dropdown-username').hide();
        }, 200);
    }
});

// conversion of html entities to text (e.g. "&" as "&amp;")
// ref: https://stackoverflow.com/questions/1147359/how-to-decode-html-entities-using-jquery
function decodeEntities(encodedString) {
    var textArea = document.createElement('textarea');
    textArea.innerHTML = encodedString;
    return textArea.value;
}

//////////
// AJAX //
//////////



// Update shifts
jQuery( document ).ready( function( $ ) {

/////////////////////////////
// AUTOCOMPELETE USERNAMES //
/////////////////////////////

    // open username dropdown on input selection and show only "I'll do it!" button at the beginning
    $( '.shift' ).find('input').on( 'focus', function() {
        // remove all other dropdowns
        $(document).find('.dropdown-username').hide();
        // open dropdown for current input
        $(document.activeElement).parent().children('.dropdown-username').show();
    } );

    // hide all dropdowns on ESC keypress
    $(document).keyup(function(e) {
        if (e.keyCode === 27) {
            $(document).find('.dropdown-username').hide();
        }
    });

    $( '.shift' ).find("input[id^='userName'], input[id^=comment]").on('input', function() {
        // show only current button
        $('[name^=btn-submit-change]')
            .addClass('hide')
            .removeClass('btn-primary');
        $(this).parents('.shift').find('[name^=btn-submit-change]')
            .removeClass('hide')
            .addClass('btn-primary');

        // hide only current icon
        $('[name^=status-icon]').removeClass('hide');
        $(this).parents('.shift').find('[name^=status-icon]').addClass('hide');

        // do all the work here after AJAX response is received
        function ajaxCallBackUsernames(response) {

            // clear array from previous results, but leave first element with current user's data
            $(document.activeElement).parent().children('.dropdown-username').contents().filter(function () {
                return this.id != "yourself";
            }).remove();

            // format data received
            response.forEach(function(data) {

                // convert person_status to text
                if ( data.prsn_status == 'candidate' ) { data.prsn_status = " (K)" }
                else if ( data.prsn_status == 'veteran' ) { data.prsn_status = " (V)" }
                else if ( data.prsn_status == 'resigned' ) { data.prsn_status = " (ex)" }
                else { data.prsn_status = "" }

                // add found persons to the array
                $(document.activeElement).parent().children('.dropdown-username').append(
                    '<li><a href="javascript:void(0);">'
                    + '<span id="currentLdapId" hidden>' + data.prsn_ldap_id + '</span>'
                    + '<span id="currentName">' + data.prsn_name + '</span>'
                    + data.prsn_status
                    + '(<span id="currentClub">' + data.club.clb_title + '</span>)'
                    + '</a></li>');
            });

            // process clicks inside the dropdown
            $(document.activeElement).parent().children('.dropdown-username').children('li').click(function(e){
                // ignore "i'll do it myself" button (handeled in view)
                if ( this.id == "yourself") return false;

                // gather the data for debugging
                var currentLdapId = $(this).find('#currentLdapId').html();
                var currentName = $(this).find('#currentName').html();
                var currentClub = $(this).find('#currentClub').html();
                var currentEntryId = $(this).closest(".shift").attr("id");

                // update fields
                $("input[id=userName" + currentEntryId + "]").val(currentName);
                $("input[id=ldapId"   + currentEntryId + "]").val(currentLdapId);
                $("input[id=club"     + currentEntryId + "]").val(currentClub);

                // send to server
                // need to go via click instead of submit because otherwise ajax:beforesend, complete and so on won't be triggered
                $("#btn-submit-changes"+currentEntryId).click();

            });

            // reveal newly created dropdown
            $(document.activeElement).parent().children('.dropdown-username').show();

        }

        // short delay to prevents double sending
        $(this).delay(250);

        // Request autocompleted names
        $.ajax({
            type: $( this ).prop( 'method' ),

            url: "/person/" + $(this).val(),

            data: {
                // We use Laravel tokens to prevent CSRF attacks - need to pass the token with each requst
                "_token": $(this).find( 'input[name=_token]' ).val(),

                // Most browsers are restricted to only "get" and "post" methods, so we spoof the method in the data
                "_method": "get"
            },

            dataType: 'json',

            success: function(response){
                // external function handles the response
                ajaxCallBackUsernames(response);
            },
        });
    } );



/////////////////////////
// AUTOCOMPELETE CLUBS //
/////////////////////////   



    // open club dropdown on input selection
    $( '.shift' ).find('input').on( 'focus', function() {
        // remove all other dropdowns
        $(document).find('.dropdown-club').hide();
        // open dropdown for current input
        $(document.activeElement).parent().parent().children('.dropdown-club').show();
    } );

    // hide all dropdowns on ESC keypress
    $(document).keyup(function(e) {
        if (e.keyCode === 27) {
            $(document).find('.dropdown-club').hide();
        }
    });

    $( '.shift' ).find("input[id^='club']").on( 'input', function() {
        // Show save icon on form change
        $(this).parents('.shift').find('[name^=btn-submit-change]').removeClass('hide');
        $(this).parents('.shift').find("[name^=status-icon]").addClass('hide');

        // do all the work here after AJAX response is received
        function ajaxCallBackClubs(response) {

            // clear array from previous results, but leave first element with current user's data
            $(document.activeElement).parent().parent().children('.dropdown-club').contents().remove();

            // format data received
            response.forEach(function(data) {

                // add found clubs to the array$(document.activeElement).parent().children('.dropdown-club')
                $(document.activeElement).parent().parent().children('.dropdown-club').append(
                    '<li><a href="javascript:void(0);">'
                    + '<span id="clubTitle">' + data.clb_title + '</span>'
                    + '</a></li>');
            });

            // process clicks inside the dropdown
            $(document.activeElement).parent().parent().children('.dropdown-club').children('li').click(function(e){

                var clubTitle = $(this).find('#clubTitle').html();
                var currentEntryId = $(this).closest(".shift").attr("id");

                // update fields
                $("input[id=club"     + currentEntryId + "]").val(clubTitle);

                // send to server
                // need to go via click instead of submit because otherwise ajax:beforesend, complete and so on won't be triggered
                $("#btn-submit-changes"+currentEntryId).click();

            });

            // reveal newly created dropdown
            $(document.activeElement).parent().parent().children('.dropdown-club').show();

        }

        // short delay to prevents double sending
        $(this).delay(250);

        // Request autocompleted names
        $.ajax({
            type: $( this ).prop( 'method' ),

            url: "/club/" + $(this).val(),

            data: {
                // We use Laravel tokens to prevent CSRF attacks - need to pass the token with each requst
                "_token": $(this).find( 'input[name=_token]' ).val(),

                // Most browsers are restricted to only "get" and "post" methods, so we spoof the method in the data
                "_method": "get"
            },

            dataType: 'json',

            success: function(response){
                // external function handles the response
                ajaxCallBackClubs(response);
            },
        });
    } );



    ///////////////////////////
    // AUTOCOMPLETE SHIFTTYPES //
    ///////////////////////////



    // open shiftType dropdown on input selection
    $( '.box' ).find('input[type=text]').on( 'focus', function()
    {
        // remove all other dropdowns
        $(document).find('.dropdown-shiftTypes').hide();
        // open dropdown for current input
        $(document.activeElement).next('.dropdown-shiftTypes').show();
    } );

    // hide all dropdowns on ESC keypress
    $(document).keyup(function(e)
    {
        if (e.keyCode === 27) {
            $(document).find('.dropdown-shiftTypes').hide();
        }
    });

    $('#yourself').on('click', function() {
        $(this).parents('.row').addClass('my-shift');
    });

    $( '.box' ).find("input[name^='shifts\[title\]']").on( 'input', function()
    {
        // do all the work here after AJAX response is received
        function ajaxCallBackClubs(response) {

            // clear array from previous results
            $(document.activeElement).next('.dropdown-shiftTypes').contents().remove();

            // format data received
            response.forEach(function(data) {

                // add found shiftTypes and metadata to the dropdown
                $(document.activeElement).next('.dropdown-shiftTypes').append(
                    '<li><a href="javascript:void(0);">'
                    + '<span id="shiftTypeTitle">'
                    + data.title
                    + '</span>'
                    + ' (<i class="fa fa-clock-o"></i> '
                    + '<span id="shiftTypeTimeStart">'
                    + data.start
                    + '</span>'
                    + '-'
                    + '<span id="shiftTypeTimeEnd">'
                    + data.end
                    + '</span>'
                    + '<span id="shiftTypeWeight" class="hidden">'
                    + data.statistical_weight
                    + '</span>'
                    + ')'
                    + '</a></li>');
            });

            // process clicks inside the dropdown
            $(document.activeElement).next('.dropdown-shiftTypes').children('li').click(function(e)
            {
                var selectedShiftTypeTitle        = decodeEntities($(this).find('#shiftTypeTitle').html());     // decoding html entities in the process
                var selectedShiftTypeTimeStart    = $(this).find('#shiftTypeTimeStart').html();
                var selectedShiftTypeTimeEnd      = $(this).find('#shiftTypeTimeEnd').html();
                var selectedShiftTypeWeight       = $(this).find('#shiftTypeWeight').html();

                // update fields
                $(this).parents(".box").find("[name^='shifts[title]']").val(selectedShiftTypeTitle);
                $(this).parents(".box").find("[name^='shifts[start]']").val(selectedShiftTypeTimeStart);
                $(this).parents(".box").find("[name^='shifts[end]']").val(selectedShiftTypeTimeEnd);
                $(this).parents(".box").find("[name^='shifts[weight]']").val(selectedShiftTypeWeight);

                // close dropdown afterwards
                $(document).find('.dropdown-shiftTypes').hide();
            });

            // reveal newly created dropdown
            $(document.activeElement).next('.dropdown-shiftTypes').show();

        }

        // short delay to prevents double sending
        $(this).delay(250);

        // Request autocompleted names
        $.ajax({
            type: $( this ).prop( 'method' ),

            url: "/shiftTypes/" + $(this).val(),

            data: {
                // We use Laravel tokens to prevent CSRF attacks - need to pass the token with each requst
                "_token": $(this).find( 'input[name=_token]' ).val(),

                // Most browsers are restricted to only "get" and "post" methods, so we spoof the method in the data
                "_method": "get"
            },

            dataType: 'json',

            success: function(response){
                // external function handles the response
                ajaxCallBackClubs(response);
            },
        });
    } );


    // Submit changes
    $( '.shift' ).on( 'submit', function() {

        // For passworded schedules: check if a password field exists and is not empty
        // We will check correctness on the server side
        if ( $(this).parentsUntil( $(this), '.panel-warning').find("[name^=password]").length
            && !$(this).parentsUntil( $(this), '.panel-warning').find("[name^=password]").val() )
        {
            var password = window.prompt( 'Bitte noch das Passwort für diesen Dienstplan eingeben:' );
        } else {
            var password = <string> $(this).parentsUntil( $(this), '.panel-warning').find("[name^=password]").val();
        }

        // necessary for the ajax callbacks
        var currentId = $(this).attr('id');

        $.ajax({
            type: $( this ).prop( 'method' ),

            url: $( this ).prop( 'action' ),

            data: JSON.stringify({
                // We use Laravel tokens to prevent CSRF attacks - need to pass the token with each requst
                "_token":       $(this).find( 'input[name=_token]' ).val(),

                // Actual data being sent below
                "entryId":      $(this).closest("form").attr("id"),
                "userName":     $(this).find("[name^=userName]").val(),
                "ldapId":       $(this).find("[name^=ldapId]").val(),
                "timestamp":    $(this).find("[name^=timestamp]").val(),
                "userClub":     $(this).find("[name^=club]").val(),
                "userComment":  $(this).find("[name^=comment]").val(),
                "password":     password,

                // Most browsers are restricted to only "get" and "post" methods, so we spoof the method in the data
                "_method": "put"
            }),

            dataType: 'json',

            contentType: 'application/json',

            beforeSend: function() {
                // console.log("beforesend");

                // hide dropdowns because they aren't no longer needed
                $(document).find('.dropdown-username').hide();
                $(document).find('.dropdown-club').hide();

                // Remove save icon and show a spinner in the username status while we are waiting for a server response
                $('#btn-submit-changes' + currentId).addClass('hide')
                    .parent()
                    .children('i')
                    .removeClass()
                    .addClass("fa fa-spinner fa-spin")
                    .attr("id", "spinner")
                    .attr("data-original-title", "In Arbeit...")
                    .css("color", "darkgrey");
            },

            complete: function() {
                // console.log('complete');
            },

            success: function(data) {
                // console.log("success");

                // COMMENT:
                // we update to server response instead of just saving user input
                // for the case when an entry has been updated recently by other user, 
                // but current user hasn't received a push-update from the server yet.
                //
                // This should later be substituted for "update highlighting", e.g.:
                // green  = "your data was saved successfully", 
                // red    = "server error, entry not saved (try again)", 
                // yellow = "other user updated before you, here's the new data"

                // Update the fields according to server response
                var $userNameInput = $("input[id=userName" + data["entryId"] + "]");
                $userNameInput.val(data["userName"]).attr("placeholder", "=FREI=");
                $("input[id=ldapId"   + data["entryId"] + "]").val(data["ldapId"]);
                $("input[id=timestamp"+ data["entryId"] + "]").val(data["timestamp"]);
                $("input[id=club"     + data["entryId"] + "]").val(data["userClub"]).attr("placeholder", "-");
                $("input[id=comment"  + data["entryId"] + "]").val(data["userComment"]).attr("placeholder", translate("addCommentHere"));

                // Switch comment icon in week view
                if ( $("input[id=comment"  + data["entryId"] + "]").val() == "" ) {
                    $("input[id=comment"  + data["entryId"] + "]").parent().children().children("button").children("i").removeClass().addClass("fa fa-comment-o");
                } else {
                    $("input[id=comment"  + data["entryId"] + "]").parent().children().children("button").children("i").removeClass().addClass("fa fa-comment");
                };

                // Switch comment in event view
                if ( $("input[id=comment"  + data["entryId"] + "]").val() == "" ) {
                    $("input[id=comment"  + data["entryId"] + "]").parent().children("span").children("i").removeClass().addClass("fa fa-comment-o");
                } else {
                    $("input[id=comment"  + data["entryId"] + "]").parent().children("span").children("i").removeClass().addClass("fa fa-comment");
                };

                var $colorDiv = $userNameInput.parent().prev().find("div");
                var isShiftEmpty = data["userName"] !== "";
                if(isShiftEmpty) {
                    $colorDiv.removeClass("red").addClass("green");
                }
                else {
                    $colorDiv.removeClass("green").addClass("red");
                }

                // UPDATE STATUS ICON
                // switch to normal user status icon and clear "spinner"-markup
                // we receive this parameters: e.g. ["status"=>"fa fa-adjust", "style"=>"color:yellowgreen;", "title"=>"Kandidat"] 
                $("#spinner").attr("style", data["userStatus"]["style"]);
                $("#spinner").attr("data-original-title", data["userStatus"]["title"]);
                $("#spinner").removeClass().addClass(data["userStatus"]["status"]).removeAttr("id");

                if (data["is_current_user"]) {
                    $userNameInput.closest('form').parent().addClass('my-shift');
                }
            },

            error: function (xhr, ajaxOptions, thrownError) {
                alert(JSON.stringify(xhr.responseJSON));
                // Hide spinner after response received
                // We make changes on success anyway, so the following state is only achieved 
                // when a response from server was received, but errors occured - so let's inform the user
                $("#spinner").removeClass().addClass("fa fa-exclamation-triangle").css("color", "red").attr("data-original-title", "Fehler: Änderungen nicht gespeichert!");
            }


        });

        // Prevent the form from actually submitting in browser
        return false;

    });



////////////////////////////////
// MANAGEMENT: UPDATE SHIFTTYPE //
////////////////////////////////



    $( '.updateShiftType' ).on( 'submit', function() {

        $.ajax({
            type: $( this ).prop( 'method' ),

            url: $( this ).prop( 'action' ),

            data: JSON.stringify({
                // We use Laravel tokens to prevent CSRF attacks - need to pass the token with each requst
                "_token":       $(this).find( 'input[name=_token]' ).val(),

                // Actual data being sent below
                "entryId":      $(this).closest("form").attr("id"),
                "shiftTypeId":    $(this).find("[name^=shiftType]").val(),

                // Most browsers are restricted to only "get" and "post" methods, so we spoof the method in the data
                "_method": "put"
            }),

            dataType: 'json',

            contentType: 'application/json',

            beforeSend: function() {
                // console.log("beforesend");
            },

            complete: function() {
                // console.log('complete');
            },

            success: function(data) {
                //console.log("success");
                // remove row to indicate successful renaming of the shiftType
                $(".shiftType-event-row" + data["entryId"]).hide();

                // if all rows except table header were hidden (all shiftTypes substituted withn other ones),
                // refresh the page to get the delete button or show remaining shiftTypes
                if ($("#events-rows").children("tr:visible").length <= 1) {
                    // we remove arguments after "?" because otherwise user could land on a pagination page that is already empty
                    (<any>window).location = window.location.href.split("?")[0];
                }

            },

            error: function (xhr, ajaxOptions, thrownError) {
                alert(JSON.stringify(xhr.responseJSON));
            }

        });

        // Prevent the form from actually submitting in browser
        return false;

    });

    // Detect shift name change and remove LDAP id from the previous shift
    $('.shift').find("[name^=userName]").on('input propertychange paste', function() {
        $(this).parent().find("[name^=ldapId]").val("");
    });

});
