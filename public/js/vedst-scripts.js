// Non-sticky footer at the bottom of the screen
$(document).ready(function() {

    var docHeight = $(window).height();
    var footerHeight = $('#footer').height();
    var footerTop = $('#footer').position().top + footerHeight;

    if (footerTop < docHeight) {
        $('#footer').css('margin-top', (docHeight - footerTop) + 'px');
    }
});

// Automatically close messages after 4 seconds (4000 milliseconds). M.
window.setTimeout(function() {
    $(".message").fadeTo(1000, 0).slideUp(500, function(){
        $(this).alert('close'); 
    });
}, 4000);


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



// Show/hide comments
$(function(){
	$('.showhide').click(function(e) {
		$(this).parent().next('.hide').toggleClass('show');
        $('.isotope').isotope('layout') 
	});
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
            $(this).find("[name^=jobType]").attr('id', "jobType" + tempId).attr('name', "jobType" + tempId);
            $(this).find("[name^=timeStart]").attr('id', "timeStart" + tempId).attr('name', "timeStart" + tempId);
            $(this).find("[name^=timeEnd]").attr('id', "timeEnd" + tempId).attr('name', "timeEnd" + tempId);
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
                $(this).find("[name^=jobType]").attr('id', "jobType" + tempId).attr('name', "jobType" + tempId);
                $(this).find("[name^=timeStart]").attr('id', "timeStart" + tempId).attr('name', "timeStart" + tempId);
                $(this).find("[name^=timeEnd]").attr('id', "timeEnd" + tempId).attr('name', "timeEnd" + tempId);
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
        
        $(this).closest('.box').find("[name^=jobType]").val(jobtype);
        $(this).closest('.box').find("[name^=timeStart]").val(timeStart);
        $(this).closest('.box').find("[name^=timeEnd]").val(timeEnd);   
        $(this).closest('.box').find("[name^=jbtyp_statistical_weight]").val(weight);
    };
});
 




// Enable Tooltips
$(function () { $("[data-toggle='tooltip']").tooltip(); });     





// ISOTOPE masonry plugin
$( document ).ready( function() {
  // init Isotope
  var $container = $('.isotope').isotope({
    itemSelector: '.element-item',
    layoutMode: 'masonry',
    masonry: {
          columnWidth: '.grid-sizer'
      },
    getSortData: {
      name: '.name',
      symbol: '.symbol',
      number: '.number parseInt',
      category: '[data-category]',
      weight: function( itemElem ) {
        var weight = $( itemElem ).find('.weight').text();
        return parseFloat( weight.replace( /[\(\)]/g, '') );
      }
    }
  });

  // filter functions
  var filterFns = {
    // show if name matches class
    name: function() {
      var name = $(this).find('.name').text();
    }
  };

  // bind filter button click
  $('#filters').on( 'click', 'button', function() {
    var filterValue = $( this ).attr('data-filter');
    // use filterFn if matches value
    filterValue = filterFns[ filterValue ] || filterValue;
    $container.isotope({ filter: filterValue });
  });
  
  // change is-checked (btn-primary) class on buttons and update local storage
  $('.btn-group').each( function( i, buttonGroup ) {
    var $buttonGroup = $( buttonGroup );
    $buttonGroup.on( 'click', 'button', function() {
        // highlight selection
        $buttonGroup.find('.btn-primary').removeClass('btn-primary');
        $( this ).addClass('btn-primary');

        // save selection in local storage
        if(typeof(Storage) !== "undefined") 
        {
            localStorage.filter = $(this).text();
        }
    });
  });
  

    // Show/hide time of entries

    $(document).ready(function() {
        if(typeof(Storage) !== "undefined") 
        {
            if (localStorage.showTime == "Zeiten einblenden") 
            {
                $('.entry-time').removeClass('hide'); 
                $('#show-hide-time').text("Zeiten ausblenden");
                $container.isotope('layout');
            } 
            else if (localStorage.showTime == "Zeiten ausblenden") 
            {
                $('.entry-time').addClass('hide');
                $('#show-hide-time').text("Zeiten einblenden");
                $('.isotope').isotope('layout')                  
            }

            var language = localStorage.language;

            $.ajax({
                url: '/lang',
                success: function(data) {
                    if (language === 'en' || language === 'de') {
                        if (data.language !== language) {
                            window.location = 'lang/' + language;
                        }
                    }
                    else {
                        localStorage.language = data.language;
                    }
                }
            })
        }
    });

    $(function(){
        $('#show-hide-time').click(function(e) {
            if ($('.entry-time').hasClass("hide")) 
            {
                // save selection in local storage
                if(typeof(Storage) !== "undefined") 
                {
                    localStorage.showTime = $(this).text();
                }

                // change state, change button
                $('.entry-time').removeClass('hide'); 
                $(this).text("Zeiten ausblenden");
                $container.isotope('layout');
            }
            else
            {
                // save selection in local storage
                if(typeof(Storage) !== "undefined") 
                {
                    localStorage.showTime = $(this).text();
                }

                // change state, change button
                $('.entry-time').addClass('hide');
                $(this).text("Zeiten einblenden");
                $('.isotope').isotope('layout')
            };        
        });
    });

    // Show/hide taken shifts

    $(document).ready(function() {
        if(typeof(Storage) !== "undefined")
        {
            if (localStorage.showTakenShifts == "Vergebene Dienste einblenden")
            {
                $('div.green').closest('.row').removeClass('hide');
                $('#show-hide-taken-shifts').text("Vergebene Dienste ausblenden");
                $container.isotope('layout');
            }
            else if (localStorage.showTakenShifts == "Vergebene Dienste ausblenden")
            {
                $('div.green').closest('.row').addClass('hide');
                $('#show-hide-taken-shifts').text("Vergebene Dienste einblenden");
                $('.isotope').isotope('layout')
            }
        }
    });

    $(function(){
        $('#show-hide-taken-shifts').click(function(e) {
            if ($('div.green').closest('.row').hasClass("hide"))
            {
                // save selection in local storage
                if(typeof(Storage) !== "undefined")
                {
                    localStorage.showTakenShifts = $(this).text();
                }

                // change state, change button
                $('div.green').closest('.row').removeClass('hide');
                $(this).text("Vergebene Dienste ausblenden");
                $container.isotope('layout');
            }
            else
            {
                // save selection in local storage
                if(typeof(Storage) !== "undefined")
                {
                    localStorage.showTakenShifts = $(this).text();
                }

                // change state, change button
                $('div.green').closest('.row').addClass('hide');
                $(this).text("Vergebene Dienste einblenden");
                $('.isotope').isotope('layout')
            };
        });
    });


   // Week view changer

    $(document).ready(function() {
        if(typeof(Storage) !== "undefined") 
        {
            if (localStorage.weekViewType == "Woche: Montag - Sonntag") 
            {
                $('.week-mo-so').removeClass('hide');
                $('.week-mi-di').addClass('hide');
                $('#change-week-view').text("Woche: Mittwoch - Dienstag");
                $container.isotope('layout');
            } 
            else if (localStorage.weekViewType == "Woche: Mittwoch - Dienstag") 
            {
                $('.week-mo-so').addClass('hide');
                $('.week-mi-di').removeClass('hide');
                $('#change-week-view').text("Woche: Montag - Sonntag");
                $('.isotope').isotope('layout')                  
            }
        }
    });

    $(function(){
        $('#change-week-view').click(function(e) {
            if ($('.week-mo-so').hasClass('hide')) 
            {
                // save selection in local storage
                if(typeof(Storage) !== "undefined") 
                {
                    localStorage.weekViewType = $(this).text();
                }

                // change state, change button
                $('.week-mo-so').removeClass('hide');
                $('.week-mi-di').addClass('hide');
                $(this).text("Woche: Mittwoch - Dienstag");
                $container.isotope('layout');
            }
            else
            {
                // save selection in local storage
                if(typeof(Storage) !== "undefined") 
                {
                    localStorage.weekViewType = $(this).text();
                }

                // change state, change button
                $('.week-mo-so').addClass('hide');
                $('.week-mi-di').removeClass('hide');
                $(this).text("Woche: Montag - Sonntag");
                $('.isotope').isotope('layout')
            };        
        });
    });





});


// Saving filtering for isotope, using browser local storage if enabled to save/resume state
$(document).ready(function() {
    if(typeof(Storage) !== "undefined") 
    {
        var filter = localStorage.filter;
        if (!filter) {
            return;
        }
        if (filter.match("bc-Club"))
        {
            $("#bc-Club-filter").trigger("click"); 
        } 
        else if (filter.match("bc-Café"))
        {
            $("#bc-Cafe-filter").trigger("click");                   
        }
        else if (filter.match("\\*"))
        {
            $("#show-all-filter").trigger("click");                    
        }
    }
});





// Filtering month view without Isotope
// onLoad inits
$(document).ready(function() {  
    // checking if we are in the month view
    if ($('#own-filter-marker').length) {
        // check if local storage in use
        if(typeof(Storage) !== "undefined" && localStorage.filter) {
            var filter = localStorage.filter;
            if (filter.match("bc-Club"))
            {
                $('.filter').hide();
                $('.bc-Club').show(); 
            }
            else if (filter.match("bc-Café"))
            {
                $('.filter').hide();
                $('.bc-Café').show(); 
            }
            else if (filter.match("\\*"))
            {
                $('.filter').show();  
            }
        }

        // click checks
        // bind filter button click
        $('#filters').on( 'click', 'button', function() {
            var filterValue = $( this ).attr('data-filter');
            if (filterValue.match("bc-Club")) 
            {
                $('.filter').hide();
                $('.bc-Club').show(); 
            }
            else if (filterValue.match("bc-Café"))
            {
                $('.filter').hide(); 
                $('.bc-Café').show(); 
            }
            else if (filterValue.match("\\*")) 
            {
                $('.filter').show(); 
            }
            
        });

    }
});

// button to remove events from week view - mostly for printing
$(function(){
    $('.hide-event').click(function(e) {
        // change state, change button
        $(this).parent().parent().parent().parent().parent().addClass('hide');
        $('.isotope').isotope('layout')       
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

    $( '.scheduleEntry' ).find("input[id^='userName']").on('input', function() {
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

                // HOTFIX: resolve current schedule entry ID via looking for an active save button
                var currentId = $('button.btn-primary').parents('form').attr('id');

                // Remove save icon and show a spinner in the username status while we are waiting for a server response
                $('#btn-submit-changes' + currentId).addClass('hide').parent().children('i').removeClass().addClass("fa fa-spinner fa-spin").attr("id", "spinner").attr("data-original-title", "In Arbeit...").css("color", "darkgrey");                
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
                $("input[id=comment"  + data["entryId"] + "]").val(data["userComment"]).attr("placeholder", "Kommentar hier hinzufügen");

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
                if ($("tr:visible").length <= 1) {
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



    /*
    $( '.survey' ).on( 'submit', function() {

        // For passworded surveys: check if a password field exists and is not empty
        // We will check correctness on the server side
        if ($(this).parentsUntil($(this), '.panel-warning').find("[name^=password]").length
            && !$(this).parentsUntil($(this), '.panel-warning').find("[name^=password]").val()) {
            var password = window.prompt('Bitte noch das Passwort für diese Umfrage eingeben:');
            $(this).parentsUntil($(this), '.panel-warning').find("[name^=password]").val(password);
        } else {
            var password = $(this).parentsUntil($(this), '.panel-warning').find("[name^=password]").val();
        }
        //return false; ?
    });
    */

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

$('[name^=btn-submit-change]').click(function() {
    $(this).parents('.row').removeClass('my-shift');
});

$('input').focusout(function() {
    if ($(this).prop('placeholder') === '=FREI=') {
        // hack to allow for click to register before focusout is called
        setTimeout(function () {
            $('.dropdown-username').hide();
        }, 200);
    }
});

$('.languageSwitcher').find('a').click(function() {
    var language = $(this).data('language');
    localStorage.setItem('language', language);
});
