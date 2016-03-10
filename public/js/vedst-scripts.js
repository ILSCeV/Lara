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
        if (localStorage.filter == "nur bc-Club") 
        {
            $("#bc-Club-filter").trigger("click"); 
        } 
        else if (localStorage.filter == "nur bc-Café") 
        {
            $("#bc-Cafe-filter").trigger("click");                   
        }
        else if (localStorage.filter == "Alle Sektionen")
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
        if(typeof(Storage) !== "undefined") 
        {
            if (localStorage.filter == "nur bc-Club") 
            {
                $('.bc-Club').show(); 
                $('.bc-Café').hide(); 
            }
            else if (localStorage.filter == "nur bc-Café")
            {
                $('.bc-Club').hide(); 
                $('.bc-Café').show(); 
            }
            else if (localStorage.filter == "Alle Sektionen") 
            {
                $('.bc-Club').show(); 
                $('.bc-Café').show(); 
            }
        }

        // click checks
        // bind filter button click
        $('#filters').on( 'click', 'button', function() {
            var filterValue = $( this ).attr('data-filter');
            if (filterValue.match("bc-Club")) 
            {
                $('.bc-Club').show(); 
                $('.bc-Café').hide(); 
            }
            else if (filterValue.match("bc-Café"))
            {
                $('.bc-Club').hide(); 
                $('.bc-Café').show(); 
            }
            else if (filterValue.match("\\*")) 
            {
                $('.bc-Club').show(); 
                $('.bc-Café').show(); 
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
    // Show save icon on form change
    $( '.scheduleEntry' ).find('input').on( 'input', function() {
        $(this).parents('.scheduleEntry').find('[name^=btn-submit-change]').removeClass('hide');
        $(this).parents('.scheduleEntry').find("[name^=status-icon]").addClass('hide');
    } );

    // Submit changes
    $( '.scheduleEntry' ).on( 'submit', function() {

        // For passworded schedules: check if a password field exists and is not empty
        // We will check correctness on the server side
        if ( $(this).parentsUntil( $(this), '.panel-warning').find("[name^=password]").length
          && !$(this).parentsUntil( $(this), '.panel-warning').find("[name^=password]").val() ) 
        {
            var password = window.prompt( 'Bitte noch das Passwort für diesen Dienstplan eingeben:' );
            $(this).parentsUntil( $(this), '.panel-warning').find("[name^=password]").val(password);       
        } else {
            var password = $(this).parentsUntil( $(this), '.panel-warning').find("[name^=password]").val();
        }

        $.ajax({  
            type: $( this ).prop( 'method' ),  

            url: $( this ).prop( 'action' ),  

            data: {
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
                },  

            dataType: 'json',
            
            beforeSend: function(data) {
                // Remove save icon, restore status icon
                console.log($(event.target).children().find('[name^=btn-submit-change]'));
                $(event.target).children().find('[name^=btn-submit-change]').addClass('hide');
                $(event.target).children().find("[name^=status-icon]").removeClass('hide');

                // Show a spinner in the username status while we are waiting for a server response                
                $(event.target).children().children("[id^=clubStatus]").children("i").removeClass().addClass("fa fa-spinner fa-spin").attr("id", "spinner").attr("data-original-title", "In Arbeit...");
            },
            
            complete: function() {
                // Hide spinner after response received, show change was made
                // We make changes on success anyway, so the following state is only achieved 
                // when a response from server was received, but errors occured - so let's inform the user
                $("#spinner").removeClass().addClass("fa fa-exclamation-triangle").css("color", "red").attr("data-original-title", "Fehler: Änderungen nicht gespeichert!");
            },

            success: function(data) {  
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
                $("input[id=userName" + data["entryId"] + "]").val(data["userName"]).attr("placeholder", "=FREI=");
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

                // UPDATE STATUS ICON
                // switch to normal user status icon and clear "spinner"-markup
                // we receive this parameters: e.g. ["status"=>"fa fa-adjust", "style"=>"color:yellowgreen;", "title"=>"Kandidat"] 
                $("#spinner").attr("style", data["userStatus"]["style"]);
                $("#spinner").attr("data-original-title", data["userStatus"]["title"]);
                $("#spinner").removeClass().addClass(data["userStatus"]["status"]).removeAttr("id");               

                // debugging info
                console.log(data);
            },

            error: function (xhr, ajaxOptions, thrownError) {
                alert(JSON.stringify(xhr.responseJSON));
                $("#spinner").removeClass().addClass("fa fa-exclamation-triangle").css("color", "red").attr("data-original-title", "Fehler: Änderungen nicht gespeichert!");
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
            e.preventDefault()

            var link = $(this)
            var httpMethod = link.data('method').toUpperCase()
            var form

            // If the data-method attribute is not PUT or DELETE,
            // then we don't know what to do. Just ignore.
            if ($.inArray(httpMethod, ['PUT', 'DELETE']) === -1) {
                return false
            }

            Laravel
                .verifyConfirm(link)
                .done(function () {
                    form = Laravel.createForm(link)
                    form.submit()
                })
        },

        verifyConfirm: function(link) {
            var confirm = new $.Deferred()

            var userResponse = window.confirm(link.data('confirm'))

            if (userResponse) {
                confirm.resolve(link)
            } else {
                confirm.reject(link)
            }

            return confirm.promise()
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