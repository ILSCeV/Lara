import * as $ from "jquery"
import { translate } from "./Translate"
import "isotope-layout"
import * as Isotope  from "../../../node_modules/isotope-layout/js/isotope.js"
import * as bootbox from "bootbox"
import {ToggleButton} from "./ToggleButton";
import {makeLocalStorageAction, makeClassToggleAction} from "./ToggleAction";
import {safeGetLocalStorage} from "./EventView";

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
        const shiftTimes = new ToggleButton("toggle-shift-time", () => $('.shift-time').is(":visible"));

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
        const takenShifts = new ToggleButton("toggle-taken-shifts", () => $('div.green').closest('.row').hasClass("hide"));
        takenShifts.addActions([
            makeLocalStorageAction("onlyEmptyShifts", "true", "false"),
            makeClassToggleAction($('div.green').closest('.row'), "hide", true),
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
        var weekMonSun = translate('mondayToSunday');
        var weekWedTue = translate('wednesdayToTuesday');

        // Apply saved preferences from local storage on pageload
        if(typeof(Storage) !== "undefined")
        {
            if (localStorage.getItem("weekStart") === "wednesday")
            {
                // apply transformation, value already saved in storage
                $('.week-mo-so').addClass('hide');
                $('.week-mi-di').removeClass('hide');
                $('#toggle-week-start').removeClass("btn-primary");
                $('#toggle-week-start').addClass("btn-success");
                $('#toggle-week-start').text(weekWedTue);
                isotope.layout();
            }
            else // default to localStorage.weekStart == "monday" and save to storage
            {
                // save or update selection in local storage
                localStorage.setItem("weekStart", "monday");

                // apply transformation
                $('.week-mo-so').removeClass('hide');
                $('.week-mi-di').addClass('hide');
                $('#toggle-week-start').addClass("btn-primary");
                $('#toggle-week-start').removeClass("btn-success");
                $('#toggle-week-start').text(weekMonSun);
                isotope.layout();
            }
        };

        // Filter buttons action
        $('#toggle-week-start').click(function(e)
        {
            if (localStorage.getItem("weekStart") === "monday")    // week starts monday, intent to start on wednesday
            {
                // save selection in local storage
                if(typeof(Storage) !== "undefined") { localStorage.setItem("weekStart", "wednesday"); }

                // change state, change button
                $('.week-mo-so').addClass('hide');
                $('.week-mi-di').removeClass('hide');
                $('#toggle-week-start').removeClass("btn-primary");
                $('#toggle-week-start').addClass("btn-success");
                $('#toggle-week-start').text(weekWedTue);
                isotope.layout();
            }
            else    // localStorage.weekStart == "wednesday" -> week starts on wednesday, intent to start on monday
            {
                // save selection in local storage
                if(typeof(Storage) !== "undefined") { localStorage.setItem("weekStart", "monday"); }

                // change state, change button
                $('.week-mo-so').removeClass('hide');
                $('.week-mi-di').addClass('hide');
                $('#toggle-week-start')
                    .addClass("btn-primary")
                    .removeClass("btn-success")
                    .text(weekMonSun);
                isotope.layout();
            };
        });

        // Show/hide comments
        $('.showhide').click(function(e) {
            const comment = $(this).parent().next('[name^=comment]');
            comment.toggleClass("hide", comment.is(":visible"));
            isotope.layout();
        });

        // button to remove events from week view - mostly for printing
        $(function(){
            $('.hide-event').click(function(e) {
                // change state, change button
                $(this).parent().parent().parent().parent().parent().addClass('hide');
                isotope.layout();
            });
        });
    }
});




///////////////
// All views //
///////////////

// Default language is german
$(function() {
    localStorage["language"] = localStorage["language"] || "de";
});



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



// Language switcher 
$('.languageSwitcher').find('a').click(function() {
    var language = $(this).data('language');
    localStorage.setItem('language', language);
});



// conversion of html entities to text (e.g. "&" as "&amp;")
// ref: https://stackoverflow.com/questions/1147359/how-to-decode-html-entities-using-jquery
function decodeEntities(encodedString) {
    var textArea = document.createElement('textarea');
    textArea.innerHTML = encodedString;
    return textArea.value;
}



////////////////
// Month view //
////////////////


// Scroll to current date/event if in mobile view in current month
$(document).ready(function() 
{
    // check if we are in month view and if the today-marker exists
    if ($('#month-view-marker').length && $(".scroll-marker").length) 
    {
        if ($(window).width() < 978) 
        {
            $('html, body').animate({ scrollTop: $(".scroll-marker").offset().top -80 }, 1000);
        };
    };
});




////////////////
// Event view //
////////////////



// Show/hide more button for infos
$(function(){
	$('.moreless-more-info').click(function(e) {
		$(this).parent().children('.more-info').toggleClass('moreshow-info');
        $(this).parent().children('.more-info').css('height','auto'); 
        $(this).parent().children('.moreless-less-info').show();
        $(this).parent().children('.moreless-more-info').hide();
	});
});

$(function(){
    $('.moreless-less-info').click(function(e) {
        $(this).parent().children('.more-info').toggleClass('moreshow-info');
        $(this).parent().children('.more-info').css('height','100'); 
        $(this).parent().children('.more-info').height(100);  
        $(this).parent().children('.moreless-less-info').hide();
        $(this).parent().children('.moreless-more-info').show();  
    });
});

$(function(){
    $('.moreless-more-info').hide();
    $('.moreless-less-info').hide();
    if ($('.more-info').height() > 100) {   
        $('.more-info').height(100);        
        $('.moreless-more-info').show();
    };
});

$(function(){
    $('.moreless-more-details').click(function(e) {
        $(this).parent().children('.more-details').toggleClass('moreshow-details');
        $(this).parent().children('.more-details').css('height','auto'); 
        $(this).parent().children('.moreless-less-details').show();
        $(this).parent().children('.moreless-more-details').hide();
    });
});

$(function(){
    $('.moreless-less-details').click(function(e) {
        $(this).parent().children('.more-details').toggleClass('moreshow-details');
        $(this).parent().children('.more-details').css('height','100'); 
        $(this).parent().children('.more-details').height(100);  
        $(this).parent().children('.moreless-less-details').hide();
        $(this).parent().children('.moreless-more-details').show();  
    });
});

$(function(){
    $('.moreless-more-details').hide();
    $('.moreless-less-details').hide();
    if ($('.more-details').height() > 100) {   
        $('.more-details').height(100);        
        $('.moreless-more-details').show();
    };

});



// Show/hide change history
$(function(){
    $('#show-hide-history').click(function(e) {
        e.preventDefault();
        if ($('#change-history').hasClass("hide")) 
        {
            // change state, change button
            $('#change-history').removeClass('hide'); 
            $('#arrow-icon').removeClass('fa-caret-right');
            $('#arrow-icon').addClass('fa-sort-desc');
        }
        else
        {
            // change state, change button
            $('#change-history').addClass('hide');
            $('#arrow-icon').addClass('fa-caret-right');
            $('#arrow-icon').removeClass('fa-sort-desc');
        };        
    });
});



///////////////
// Week view //
///////////////






//////////////////////
// Create/edit view //
//////////////////////



// Shows dynamic form fields for new job types 
$(document).ready(function() {
    // initialise counter
    var iCnt = parseInt($('#counter').val());

    if (iCnt < 2) {
        $(".btnRemove").hide();
    }; 

    // Add one more job with every click on "+"
    $('.btnAdd').click(function() {
        var elementToCopy = $(this).closest('.box');
        elementToCopy.find(".dropdown-menu").hide();
        var clone = elementToCopy.clone(true);
        clone.insertAfter(elementToCopy);
        clone.find('.shiftId').val("");
    });

    // Remove selected job
    $('.btnRemove').click(function(e) {
        $(this).closest('.box').remove();
    });

    // populate from dropdown select
    (<any>$.fn).dropdownSelect = function(shiftType, timeStart, timeEnd, weight) {
        
        $(this).closest('.box').find("[name^=jbtyp_title]").val(shiftType);
        $(this).closest('.box').find("[name^=jbtyp_time_start]").val(timeStart);
        $(this).closest('.box').find("[name^=jbtyp_time_end]").val(timeEnd);   
        $(this).closest('.box').find("[name^=jbtyp_statistical_weight]").val(weight);
    };
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
    $('[name^=show-stats-person]').click(function() {

        // Initialise modal and show loading icon and message
        var dialog = <any> bootbox.dialog({
            title: translate('listOfShiftsDone') + chosenPerson,
            size: 'large',
            message: '<p><i class="fa fa-spin fa-spinner"></i>' + translate('loading') + '</p>',
            onEscape: () => {}
        });

       
        // Do all the work here after AJAX response is received
        function ajaxCallBackPersonStats(response) { 

            // Parse and show response
            dialog.init(function(){

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
                    +"</table>"
                );

                // check for empty response
                if (response.length === 0) 
                {
                    $("#person-shifts-overview").append("<tr><td colspan=6>"  + translate('noShiftsInThisPeriod') + "</td></tr>");
                }

                // Fill with data received 
                for (var i = 0; i < response.length; i++)
                {
                    $("#person-shifts-overview").append(
                        "<tbody>" 
                        // Change background for shifts in other sections
                        + "<tr" + (!response[i]["isOwnClub"] ? " class=\"active text-muted\"" : "") + ">" 
                        + "<td>"  + (1+i) + "</td>" 
                        + "<td>" + response[i]["shift"] + "</td>"
                        + "<td>" + "<a href=\"../../event/" + response[i]["event_id"] + "\">" + response[i]["event"] + "</a>" + "</td>"
                        // Color-coding for different sections 
                        + "<td class=\"" + response[i]["section"]+ "-section-highlight\">" + response[i]["section"] + "</td>"
                        + "<td>" + response[i]["date"] + "</td>" 
                        + "<td>" + response[i]["weight"] + "</td>"
                        + "</tr>"
                        + "</tbody>");
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


//////////
// ICal //
//////////

/* 
 * Disabling iCal until fully functional.
 * 

$('[name^=icalfeeds]').click(function () {

    var clipboard = null;
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

Disabling iCal until fully functional. */



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



////////////////////////////////////
// Clever RESTful Resource Delete //
////////////////////////////////////



/*
Taken from: https://gist.github.com/soufianeEL/3f8483f0f3dc9e3ec5d9
Modified by Ferri Sutanto
- use promise for verifyConfirm
Examples : 
<a href="posts/2" data-method="delete" data-token="{{csrf_token()}}"> 
- Or, request confirmation in the process -
<a href="posts/2" data-method="delete" data-token="{{csrf_token()}}" data-confirm="Are you sure?">
*/

(function(window, $, undefined) {

    var Laravel = {
        initialize: function() {
            this.methodLinks = $('a[data-method]');
            this.token = $('a[data-token]');
            this.registerEvents();
        },

        registerEvents: function() {
            this.methodLinks.on('click', this.handleMethod);
        },

        handleMethod: function(e) {
            e.preventDefault();

            var link = $(this);
            var httpMethod = link.data('method').toUpperCase();
            var form;

            // If the data-method attribute is not PUT or DELETE,
            // then we don't know what to do. Just ignore.
            if ($.inArray(httpMethod, ['PUT', 'DELETE']) === -1) {
                return false
            }

            Laravel
                .verifyConfirm(link)
                .done(function () {
                    form = Laravel.createForm(link);
                    form.submit()
                })
        },

        verifyConfirm: function(link) {
            var confirm = $.Deferred();
            bootbox.confirm(link.data('confirm'), function(result){
                if (result) {
                    confirm.resolve(link);
                } else {
                    confirm.reject(link);
                }
            });

            return confirm.promise();
        },

        createForm: function(link) {
            var form =
                $('<form>', {
                    'method': 'POST',
                    'action': link.attr('href')
                });

            var token =
                $('<input>', {
                    'type': 'hidden',
                    'name': '_token',
                    'value': link.data('token')
                });

            var hiddenInput =
                $('<input>', {
                    'name': '_method',
                    'type': 'hidden',
                    'value': link.data('method')
                });

            return form.append(token, hiddenInput)
                .appendTo('body');
        }
    };

    Laravel.initialize();

})(window, jQuery);


////////////////////////
// Back to top button //
////////////////////////

$(() => {
    $(document).ready(function(){
        $(window).scroll(function () {
            if ($(this).scrollTop() > 50) {
                $('#back-to-top').fadeIn();
            } else {
                $('#back-to-top').fadeOut();
            }
        });
        // scroll body to 0px on click
        $('#back-to-top').click(function () {
            $('#back-to-top').tooltip('hide');
            $('body,html').animate({
                scrollTop: 0
            }, 800);
            return false;
        });

    });
});
