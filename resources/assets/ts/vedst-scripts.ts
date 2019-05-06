import {translate} from "./Translate"
import "isotope-layout"

import * as bootbox from "bootbox"

import {initFilters} from "./common/filters"

const jQuery = $;



/////////////
// Filters //
/////////////


$(initFilters);



///////////////
// All views //
///////////////



// Default language is german
$(function() {
    localStorage["language"] = localStorage["language"] || "de";
});



// Enable Tooltips
$(function () { $("[data-toggle='tooltip']").tooltip({trigger: "hover"}); }).tooltip("hide");

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



////////////////
// Month view //
////////////////

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
    let iCnt = parseInt(<string>$('#counter').val());

    if (iCnt < 2) {
        $(".btnRemove").hide();
    }

    const updateIsOptionalCheckboxes = () =>{
        $('.isOptional').attr('name',(index)=>{return "shifts[optional]["+index+"]";});
        $('.isOptionalHidden').attr('name',(index)=>{return "shifts[optional]["+index+"]";});
    };

    // Add one more job with every click on "+"
    $('.btnAdd').click(function() {
        let elementToCopy = $(this).closest('.box');
        elementToCopy.find(".dropdown-menu").hide();
        let clone = elementToCopy.clone(true);
        clone.insertAfter(elementToCopy);
        clone.find('.shiftId').val("");
        updateIsOptionalCheckboxes();
    });

    // Remove selected job
    $('.btnRemove').click(function(e) {
        $(this).closest('.box').remove();
        updateIsOptionalCheckboxes();
    });

    // populate from dropdown select
    (<any>$.fn).dropdownSelect = function(shiftType, timeStart, timeEnd, weight) {

        $(this).closest('.box').find("[name^=jbtyp_title]").val(shiftType);
        $(this).closest('.box').find("[name^=jbtyp_time_start]").val(timeStart);
        $(this).closest('.box').find("[name^=jbtyp_time_end]").val(timeEnd);
        $(this).closest('.box').find("[name^=jbtyp_statistical_weight]").val(weight);
    };
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
                remindPersonalIcalInput = '<div class="form-group pl-3 pr-3 col-md-12 col-12">' +
                    translate('remindsBeforeShiftStart') + '&nbsp;&nbsp;' +
                    '<input id="personalIcalRemindValue" type="number" value="0" width="20%"/>' + translate('minutes') +
                    '</div>';
            }

            var legend = "";
            if (typeof response['isPublic'] !== 'undefined' && response['isPublic'] !== true) {
                legend = '<div class="p-3">' + translate('legend') + ': <span class="bg-warning" style="border: 1px solid black;"> <span class="glyphicon">&nbsp;</span></span> ' + translate("internalUsageOnly") + '</div>  ';
            }

            dialog.find('.modal-body').addClass("p-0").html("" +
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
      let self = $(this).closest('.shiftRow');
      $(self).find("[name^=ldapId]").val("");
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
  $(document).on({
    ready: function () {
      $(window).on({
        scroll: function () {
          if ($(this).scrollTop() > 50) {
            $('#back-to-top').fadeIn();
          } else {
            $('#back-to-top').fadeOut();
          }
        }
      });
      // scroll body to 0px on click
      $('#back-to-top').on({
        click: function () {
          $('#back-to-top').tooltip('hide');
          $('body,html').animate({
            scrollTop: 0
          }, 800);
          return false;
        }
      });
    }
  });
});

$( ()=> {
  $(window).scroll(()=>{
    $('.alert-fixed').css('top',window.pageYOffset + 'px')
  });
} );
