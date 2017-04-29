/////////////
// Filters //
/////////////



$( document ).ready( function() {


    //////////////////////////////////////////////////////
    // Month view without Isotope, section filters only //
    //////////////////////////////////////////////////////
    


    if ($('#month-view-marker').length) 
    {
        // Apply filters from local storage on page load

        // first hide all sections
        $('.section-filter').hide(); 

        // get all sections from buttons we created while rendering on the backend side
        var sections = [];
        $.each($('.section-filter-selector'), function(){ sections.push($(this).prop('id')); });

        for (i = 0; i < sections.length; ++i ) 
        {
            // if "hide": filter exists and set to "hide" - no action needed
            // if "show": filter exists and set to "show" - show events, highlight button
            // if "null": filter doesn't exist - default to "show"
            if ( localStorage.getItem(sections[i]) !== "hide" ) 
            {   
                // save section filter value
                if(typeof(Storage) !== "undefined") { localStorage.setItem(sections[i], "show"); };

                // show events from this section in view
                $("."+sections[i].slice(7)).show();

                // set filter buttons to the saved state
                $('#'+sections[i]).addClass('btn-primary');
            } 
        }


        // Filter buttons action
        $('#section-filter').on( 'click', 'button', function() 
        {
            // save current filter intent
            var filterValue = $( this ).attr('data-filter');

            if ( $(this).hasClass('btn-primary') ) 
            {   // case 1: this section was shown, intent to hide
                
                // deactivate button
                $(this).removeClass('btn-primary');

                // save choice to local storage
                if(typeof(Storage) !== "undefined") { localStorage.setItem("filter-"+filterValue, 'hide'); }
                
                // First hide all
                $('.section-filter').hide();
                
                // go through local storage
                for (i = 0; i < window.localStorage.length; i++) 
                {
                    key = window.localStorage.key(i);

                    // look for all entries starting with "filter-" prefix
                    if (key.slice(0,7) === "filter-") 
                    {
                        // find what should be revealed
                        if (window.localStorage.getItem(key) == "show") 
                        { 
                            // show events
                            $("."+key.slice(7)).show(); 

                            // set filter buttons to the saved state
                            $('#filter-'+key.slice(7)).addClass('btn-primary');
                        };             
                    }
                }
            } 
            else 
            {   //case 2: this section was hidden, intent to show
                
                // reactivate button
                $(this).addClass('btn-primary');

                // save choice to local storage
                if(typeof(Storage) !== "undefined") { localStorage.setItem("filter-"+filterValue, 'show'); }
                
                // show events from this section in view
                $("."+filterValue).show(); 
            }
        });
    } 
    else    
    {


        /////////////////////////////////////////////////////////
        // Week view with Isotope, section and feature filters //
        /////////////////////////////////////////////////////////



        // init Isotope
        var $container = $('.isotope').isotope(
        {
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



        // Apply filters from local storage on page load

        // first hide all sections
        $('.section-filter').hide(); 

        // get all sections from buttons we created while rendering on the backend side
        var sections = [];
        $.each($('.section-filter-selector'), function(){ sections.push($(this).prop('id')); });

        for (i = 0; i < sections.length; ++i ) 
        {
            // if "hide": filter exists and set to "hide" - no action needed
            // if "show": filter exists and set to "show" - show events, highlight button
            // if "null": filter doesn't exist - default to "show"
            if ( localStorage.getItem(sections[i]) !== "hide" ) 
            {   
                // save section filter value
                if(typeof(Storage) !== "undefined") { localStorage.setItem(sections[i], "show"); };

                // show events from this section in view
                $("."+sections[i].slice(7)).show();
                $('.isotope').isotope('layout');

                // set filter buttons to the saved state
                $('#'+sections[i]).addClass('btn-primary');
            } 
        }
        

        // Filter buttons action
        $('#section-filter').on( 'click', 'button', function() 
        {
            // save current filter intent
            var filterValue = $( this ).attr('data-filter');

            if ( $(this).hasClass('btn-primary') ) 
            {   // case 1: this section was shown, intent to hide
                
                // deactivate button
                $(this).removeClass('btn-primary');

                // save choice to local storage
                if(typeof(Storage) !== "undefined") { localStorage.setItem("filter-"+filterValue, 'hide'); }
                
                // First hide all
                $('.section-filter').hide();
                
                // go through local storage
                for (i = 0; i < window.localStorage.length; i++) 
                {
                    key = window.localStorage.key(i);

                    // look for all entries starting with "filter-" prefix
                    if (key.slice(0,7) === "filter-") 
                    {
                        // find what should be revealed
                        if (window.localStorage.getItem(key) == "show") 
                        { 
                            // show events
                            $("."+key.slice(7)).show(); 

                            // set filter buttons to the saved state
                            $('#filter-'+key.slice(7)).addClass('btn-primary');
                        };             
                    }
                }
                $('.isotope').isotope('layout'); 
            } 
            else 
            {   //case 2: this section was hidden, intent to show
                
                // reactivate button
                $(this).addClass('btn-primary');

                // save choice to local storage
                if(typeof(Storage) !== "undefined") { localStorage.setItem("filter-"+filterValue, 'show'); }
                
                // show events from this section in view
                $("."+filterValue).show();
                $('.isotope').isotope('layout');
            }
        });

     

        /////////////////////
        // Feature filters //
        /////////////////////



        //////////////////////////////
        // Show/hide time of shifts //
        //////////////////////////////



        // set translated strings
        $('#toggle-shift-time').text(translate('shiftTime'));

        // Apply saved preferences from local storage on pageload
        if(typeof(Storage) !== "undefined") 
        {
            if (localStorage.shiftTime == "show") 
            {   
                $('.entry-time').removeClass("hide"); 
                $('#toggle-shift-time').addClass("btn-primary");
                $('.isotope').isotope('layout');
            } 
            else if (localStorage.shiftTime == "hide") 
            {
                $('.entry-time').addClass("hide");
                $('#toggle-shift-time').removeClass("btn-primary");
                $('.isotope').isotope('layout');                  
            }      
        };

        // Filter buttons action
        $('#toggle-shift-time').click(function(e) 
        { 
            if ($('.entry-time').is(":visible"))    // times are shown, intent to hide
            {
                // save selection in local storage
                if(typeof(Storage) !== "undefined") { localStorage.shiftTime = "hide"; }

                // change state, change button
                $('.entry-time').addClass("hide"); 
                $('#toggle-shift-time').removeClass("btn-primary");
                $('.isotope').isotope('layout');
            }
            else    // times are hidden, intent to show
            {
                // save selection in local storage
                if(typeof(Storage) !== "undefined") { localStorage.shiftTime = "show"; }

                // change state, change button
                $('.entry-time').removeClass("hide");
                $('#toggle-shift-time').addClass("btn-primary");
                $('.isotope').isotope('layout');
            };        
        });



        ////////////////////////////
        // Show/hide taken shifts //
        ////////////////////////////



        $('#toggle-taken-shifts').text(translate("onlyEmpty"));

        // Apply saved preferences from local storage on pageload
        if(typeof(Storage) !== "undefined") 
        {
            if (localStorage.onlyEmptyShifts == "true") 
            {   
                $('div.green').closest('.row').addClass('hide');
                $('#toggle-taken-shifts').addClass("btn-primary");
                $('.isotope').isotope('layout');
            } 
            else if (localStorage.onlyEmptyShifts == "false") 
            {
                $('div.green').closest('.row').removeClass('hide');
                $('#toggle-taken-shifts').removeClass("btn-primary");
                $('.isotope').isotope('layout');                  
            }      
        };

        // Filter buttons action
        $('#toggle-taken-shifts').click(function(e) 
        { 
            if ($('div.green').closest('.row').is(":visible"))    // all shifts are shown, intent to hide full shifts
            {
                // save selection in local storage
                if(typeof(Storage) !== "undefined") { localStorage.onlyEmptyShifts = "true"; }

                // change state, change button
                $('div.green').closest('.row').addClass('hide'); 
                $('#toggle-taken-shifts').addClass("btn-primary");
                $('.isotope').isotope('layout');
            }
            else    // only empty shifts shown, intent to show all shifts
            {
                // save selection in local storage
                if(typeof(Storage) !== "undefined") { localStorage.onlyEmptyShifts = "false"; }

                // change state, change button
                $('div.green').closest('.row').removeClass('hide');
                $('#toggle-taken-shifts').removeClass("btn-primary");
                $('.isotope').isotope('layout');
            };        
        });



        ////////////////////////////
        // Show/hide all comments //
        ////////////////////////////


        

        // Constraint: limits usage of this filter to week view only
        if ($('#week-view-marker').length) 
        {
            // Apply saved preferences from local storage on pageload
            if(typeof(Storage) !== "undefined") 
            {
                if (localStorage.allComments == "show") 
                {   
                    $('[name^=comment]').removeClass("hide"); 
                    $('#toggle-all-comments').addClass("btn-primary");
                    $('.isotope').isotope('layout');
                } 
                else if (localStorage.allComments == "hide") 
                {
                    $('[name^=comment]').addClass("hide");
                    $('#toggle-all-comments').removeClass("btn-primary");
                    $('.isotope').isotope('layout');                  
                }      
            };

            // Filter buttons action
            $('#toggle-all-comments').click(function(e) 
            { 
                if ($('[name^=comment]').is(":visible"))    // comments are shown, intent to hide
                {
                    // save selection in local storage
                    if(typeof(Storage) !== "undefined") { localStorage.allComments = "hide"; }

                    // change state, change button
                    $('[name^=comment]').addClass("hide"); 
                    $('#toggle-all-comments').removeClass("btn-primary");
                    $('.isotope').isotope('layout');
                }
                else    // comments are hidden, intent to show
                {
                    // save selection in local storage
                    if(typeof(Storage) !== "undefined") { localStorage.allComments = "show"; }

                    // change state, change button
                    $('[name^=comment]').removeClass("hide");
                    $('#toggle-all-comments').addClass("btn-primary");
                    $('.isotope').isotope('layout');
                };        
            });
        };


        ///////////////////////////////////////////////
        // Week view changer: start Monday/Wednesday //
        ///////////////////////////////////////////////



        // set translated strings
        var weekMonSun = translate('mondayToSunday');
        var weekWedTue = translate('wednesdayToTuesday');

        // Apply saved preferences from local storage on pageload
        if(typeof(Storage) !== "undefined") 
        {
            if (localStorage.weekStart === "wednesday") 
            {
                // apply transformation, value already saved in storage
                $('.week-mo-so').addClass('hide');
                $('.week-mi-di').removeClass('hide');
                $('#toggle-week-start').removeClass("btn-primary");
                $('#toggle-week-start').addClass("btn-success");
                $('#toggle-week-start').text(weekWedTue);
                $('.isotope').isotope('layout');                  
            } 
            else // default to localStorage.weekStart == "monday" and save to storage
            {
                // save or update selection in local storage
                localStorage.weekStart = "monday";

                // apply transformation
                $('.week-mo-so').removeClass('hide');
                $('.week-mi-di').addClass('hide');
                $('#toggle-week-start').addClass("btn-primary");
                $('#toggle-week-start').removeClass("btn-success");
                $('#toggle-week-start').text(weekMonSun);
                $('.isotope').isotope('layout');
            }     
        };

        // Filter buttons action
        $('#toggle-week-start').click(function(e) 
        { 
            if (localStorage.weekStart === "monday")    // week starts monday, intent to start on wednesday
            {
                // save selection in local storage
                if(typeof(Storage) !== "undefined") { localStorage.weekStart = "wednesday"; }

                // change state, change button
                $('.week-mo-so').addClass('hide');
                $('.week-mi-di').removeClass('hide');
                $('#toggle-week-start').removeClass("btn-primary");
                $('#toggle-week-start').addClass("btn-success");
                $('#toggle-week-start').text(weekWedTue);
                $('.isotope').isotope('layout');
            }
            else    // localStorage.weekStart == "wednesday" -> week starts on wednesday, intent to start on monday
            {
                // save selection in local storage
                if(typeof(Storage) !== "undefined") { localStorage.weekStart = "monday"; }

                // change state, change button
                $('.week-mo-so').removeClass('hide');
                $('.week-mi-di').addClass('hide');
                $('#toggle-week-start').addClass("btn-primary");
                $('#toggle-week-start').removeClass("btn-success");
                $('#toggle-week-start').text(weekMonSun);
                $('.isotope').isotope('layout');
            };        
        });

    };
});




///////////////
// All views //
///////////////

// Default language is german
$(function() {
    localStorage["language"] = localStorage["language"] || "pirate";
});



// Enable Tooltips
$(function () { $("[data-toggle='tooltip']").tooltip(); });     



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



// Show/hide comments
$('.showhide').click(function(e) {
    if ($(this).parent().next('[name^=comment]').is(":visible"))    
    {
        // comment is shown, intent to hide
        $(this).parent().next('[name^=comment]').addClass("hide");
    }
    else
    {
        // comment is hidden, intent to show
        $(this).parent().next('[name^=comment]').removeClass("hide");
    };   
    $('.isotope').isotope('layout');     
});







// button to remove events from week view - mostly for printing
$(function(){
    $('.hide-event').click(function(e) {
        // change state, change button
        $(this).parent().parent().parent().parent().parent().addClass('hide');
        $('.isotope').isotope('layout')       
    });
});



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
        
        var temp = $(this).closest('.box');
        var tempId = parseInt(temp.attr('id').substring(3,7));

        // clone entry
        temp.clone(true).insertAfter(temp);

        // update fields for following entries
        temp.nextUntil("br").each(function() {
            $(this).attr('id', "box" + ++tempId);
            $(this).find("[name^=jbtyp_title]").attr('id', "jbtyp_title" + tempId).attr('name', "jbtyp_title" + tempId);
            $(this).find("[name^=jbtyp_time_start]").attr('id', "jbtyp_time_start" + tempId).attr('name', "jbtyp_time_start" + tempId);
            $(this).find("[name^=jbtyp_time_end]").attr('id', "jbtyp_time_end" + tempId).attr('name', "jbtyp_time_end" + tempId);
            $(this).find("[name^=jbtyp_statistical_weight]").attr('id', "jbtyp_statistical_weight" + tempId).attr('name', "jbtyp_statistical_weight" + tempId);
        }); 

        // update counter
        iCnt = iCnt + 1;
        $('#counter').val(iCnt);      

        if (iCnt >> 1) {
            $(".btnRemove").show();
        };  
    });

    // Remove selected job
    $('.btnRemove').click(function(e) {            
            var temp = $(this).closest('.box');
            var tempId = parseInt(temp.attr('id').substring(3,7)) - 1;

            // update fields for following entries
            temp.nextUntil("br").each(function() {
                $(this).attr('id', "box" + ++tempId);
                $(this).find("[name^=jbtyp_title]").attr('id', "jbtyp_title" + tempId).attr('name', "jbtyp_title" + tempId);
                $(this).find("[name^=jbtyp_time_start]").attr('id', "jbtyp_time_start" + tempId).attr('name', "jbtyp_time_start" + tempId);
                $(this).find("[name^=jbtyp_time_end]").attr('id', "jbtyp_time_end" + tempId).attr('name', "jbtyp_time_end" + tempId);
                $(this).find("[name^=jbtyp_statistical_weight]").attr('id', "jbtyp_statistical_weight" + tempId).attr('name', "jbtyp_statistical_weight" + tempId);
            }); 

            // delete entry
            $(this).closest(".box").remove();
            e.preventDefault();
            
            // update counter
            iCnt = iCnt - 1; 
            $('#counter').val(iCnt);

            if (iCnt < 2) {
                $(".btnRemove").hide();
            }; 
    });

    // populate from dropdown select
    $.fn.dropdownSelect = function(jobtype, timeStart, timeEnd, weight) {
        
        $(this).closest('.box').find("[name^=jbtyp_title]").val(jobtype);
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



    $('[name^=show-stats-person]').click(function() {

        // Initialise modal and show loading icon and message
        var dialog = bootbox.dialog({
            title: translate('listOfShiftsDone') + chosenPerson,
            size: 'large',
            message: '<p><i class="fa fa-spin fa-spinner"></i>' + translate('loading') + '</p>'
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
                remindPersonalIcalInput = '<div class="input-group">' +
                    '<span class="input-group-addon">' + translate('remindsBeforeShiftStart') + '</span> ' +
                    '<input id="personalIcalRemindValue" class="form-control" type="number" value="0"/>' +
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



//////////
// AJAX //
//////////



// Update schedule entries
jQuery( document ).ready( function( $ ) {


/////////////////////////////
// AUTOCOMPELETE USERNAMES //
/////////////////////////////

    // open username dropdown on input selection and show only "I'll do it!" button at the beginning
    $( '.scheduleEntry' ).find('input').on( 'focus', function() {
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

    $( '.scheduleEntry' ).find("input[id^='userName'], input[id^=comment]").on('input', function() {
        // show only current button
        $('[name^=btn-submit-change]')
            .addClass('hide')
            .removeClass('btn-primary');
        $(this).parents('.scheduleEntry').find('[name^=btn-submit-change]')
            .removeClass('hide')
            .addClass('btn-primary');

        // hide only current icon
        $('[name^=status-icon]').removeClass('hide');
        $(this).parents('.scheduleEntry').find('[name^=status-icon]').addClass('hide');

        // do all the work here after AJAX response is received
        function ajaxCallBackUsernames(response) { 

            // clear array from previous results, but leave first element with current user's data
            $(document.activeElement).parent().children('.dropdown-username').contents().filter(function () {
                return this.id != "yourself";
            }).remove();

            // format data received
            response.forEach(function(data) {

                // now we convert our data to meaningful text - could have done it on server side, but this is easier for now:
                // convert club_id to text
                if (data.clb_id == 2) { data.clb_id = "bc-Club" }
                if (data.clb_id == 3) { data.clb_id = "bc-Café" }

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
                    + '(<span id="currentClub">' + data.clb_id + '</span>)'
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
                var currentEntryId = $(this).closest(".scheduleEntry").attr("id");

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
        $(this).delay('250');

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
    $( '.scheduleEntry' ).find('input').on( 'focus', function() {
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

    $( '.scheduleEntry' ).find("input[id^='club']").on( 'input', function() {
        // Show save icon on form change
        $(this).parents('.scheduleEntry').find('[name^=btn-submit-change]').removeClass('hide');
        $(this).parents('.scheduleEntry').find("[name^=status-icon]").addClass('hide');

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
                var currentEntryId = $(this).closest(".scheduleEntry").attr("id");

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
        $(this).delay('250');

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
    // AUTOCOMPLETE JOBTYPES //
    ///////////////////////////
    


    // open jobtype dropdown on input selection
    $( '.box' ).find('input[type=text]').on( 'focus', function() 
    {
        // remove all other dropdowns
        $(document).find('.dropdown-jobtypes').hide();
        // open dropdown for current input
        $(document.activeElement).next('.dropdown-jobtypes').show();
    } );

    // hide all dropdowns on ESC keypress
    $(document).keyup(function(e) 
    {
      if (e.keyCode === 27) {
        $(document).find('.dropdown-jobtypes').hide();
      }
    });

    $( '.box' ).find("input[id^='jbtyp_title']").on( 'input', function() 
    {
        // do all the work here after AJAX response is received
        function ajaxCallBackClubs(response) { 

            // clear array from previous results
            $(document.activeElement).next('.dropdown-jobtypes').contents().remove();

            // format data received
            response.forEach(function(data) {

                // add found jobtypes and metadata to the dropdown
                $(document.activeElement).next('.dropdown-jobtypes').append(
                    '<li><a href="javascript:void(0);">' 
                    + '<span id="jobTypeTitle">' 
                    + data.jbtyp_title 
                    + '</span>'
                    + ' (<i class="fa fa-clock-o"></i> '
                    + '<span id="jobTypeTimeStart">'
                    + data.jbtyp_time_start
                    + '</span>'
                    + '-'
                    + '<span id="jobTypeTimeEnd">'
                    + data.jbtyp_time_end
                    + '</span>'
                    + '<span id="jobTypeWeight" class="hidden">'
                    + data.jbtyp_statistical_weight
                    + '</span>'
                    + ')' 
                    + '</a></li>');
            });  

            // process clicks inside the dropdown
            $(document.activeElement).next('.dropdown-jobtypes').children('li').click(function(e)
            {
                var selectedJobTypeTitle        = decodeEntities($(this).find('#jobTypeTitle').html());     // decoding html entities in the process
                var selectedJobTypeTimeStart    = $(this).find('#jobTypeTimeStart').html();
                var selectedJobTypeTimeEnd      = $(this).find('#jobTypeTimeEnd').html();
                var selectedJobTypeWeight       = $(this).find('#jobTypeWeight').html();
                var currentInputId              = $(this).closest(".box").attr("id").slice(3);

                // update fields
                $("input[id=jbtyp_title"                + currentInputId + "]").val(selectedJobTypeTitle);
                $("input[id=jbtyp_time_start"           + currentInputId + "]").val(selectedJobTypeTimeStart);
                $("input[id=jbtyp_time_end"             + currentInputId + "]").val(selectedJobTypeTimeEnd);
                $("input[id=jbtyp_statistical_weight"   + currentInputId + "]").val(selectedJobTypeWeight);

                // close dropdown afterwards
                $(document).find('.dropdown-jobtypes').hide();
            });

            // reveal newly created dropdown
            $(document.activeElement).next('.dropdown-jobtypes').show();

        }

        // short delay to prevents double sending
        $(this).delay('250');

        // Request autocompleted names
        $.ajax({  
            type: $( this ).prop( 'method' ),  

            url: "/jobtypes/" + $(this).val(),  

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
    $( '.scheduleEntry' ).on( 'submit', function() {

        // For passworded schedules: check if a password field exists and is not empty
        // We will check correctness on the server side
        if ( $(this).parentsUntil( $(this), '.panel-warning').find("[name^=password]").length
          && !$(this).parentsUntil( $(this), '.panel-warning').find("[name^=password]").val() ) 
        {
            var password = window.prompt( 'Bitte noch das Passwort für diesen Dienstplan eingeben:' );      
        } else {
            var password = $(this).parentsUntil( $(this), '.panel-warning').find("[name^=password]").val();
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
// MANAGEMENT: UPDATE JOBTYPE //
////////////////////////////////



    $( '.updateJobtype' ).on( 'submit', function() {

        $.ajax({  
            type: $( this ).prop( 'method' ),  

            url: $( this ).prop( 'action' ),  

            data: JSON.stringify({
                    // We use Laravel tokens to prevent CSRF attacks - need to pass the token with each requst
                    "_token":       $(this).find( 'input[name=_token]' ).val(),

                    // Actual data being sent below
                    "entryId":      $(this).closest("form").attr("id"), 
                    "jobtypeId":    $(this).find("[name^=jobtype]").val(),

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
                // remove row to indicate successful renaming of the jobtype
                $(".jobtype-event-row" + data["entryId"]).hide();

                // if all rows except table header were hidden (all jobtypes substituted withn other ones),
                // refresh the page to get the delete button or show remaining jobtypes
                if ($("#events-rows").children("tr:visible").length <= 1) {
                    // we remove arguments after "?" because otherwise user could land on a pagination page that is already empty
                    window.location = window.location.href.split("?")[0];
                }
                
            },

            error: function (xhr, ajaxOptions, thrownError) {
                alert(JSON.stringify(xhr.responseJSON));
            }

        });

        // Prevent the form from actually submitting in browser
        return false; 

    });

    // Detect entry name change and remove LDAP id from the previous entry
    $('.scheduleEntry').find("[name^=userName]").on('input propertychange paste', function() {
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
            var confirm = new $.Deferred();
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