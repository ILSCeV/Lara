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
        $(this).parent().children('.more-info').css('height','125'); 
        $(this).parent().children('.more-info').height(125);  
        $(this).parent().children('.moreless-less-info').hide();
        $(this).parent().children('.moreless-more-info').show();  
    });
});

$(function(){
    $('.moreless-more-info').hide();
    $('.moreless-less-info').hide();
    if ($('.more-info').height() > 125) {   
        $('.more-info').height(125);        
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
        $(this).parent().children('.more-details').css('height','125'); 
        $(this).parent().children('.more-details').height(125);  
        $(this).parent().children('.moreless-less-details').hide();
        $(this).parent().children('.moreless-more-details').show();  
    });
});

$(function(){
    $('.moreless-more-details').hide();
    $('.moreless-less-details').hide();
    if ($('.more-details').height() > 125) {   
        $('.more-details').height(125);        
        $('.moreless-more-details').show();
    };

});



// Show/hide comments
$(function(){
	$('.showhide').click(function(e) {
		$(this).parent().next().children('.hide').toggleClass('show');
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



jQuery( document ).ready( function( $ ) {
 
    $( '.scheduleEntry' ).on( 'submit', function() {
 
        $.ajax({  
            type: $( this ).prop( 'method' ),  

            url: $( this ).prop( 'action' ),  

            data: {
                    // We use Laravel tokens to prevent CSRF attacks - need to pass the token with each requst
                    "_token":   $( this ).find( 'input[name=_token]' ).val(),

                    // Actual data being sent below
                    "entryId":  $(this).closest("form").attr("id"), 
                    "userName": $(this).find("[name^=userName]").val(),
                    "ldapId":   $(this).find("[name^=ldapId]").val(),
                    "userClub":     $(this).find("[name^=club]").val(),
                    "userComment":  $(this).find("[name^=comment]").val(),

                    // Most browsers are restricted to only "get" and "post" methods, so we spoof the method in the data
                    "_method": "put"

                },  

            dataType: 'json',
            
            beforeSend: function() {
                //... your initialization code here (so show loader) ...

                // start a spinner in the username field
                // until I get a reponse from the server
                // $("[class^=clubStatus]").nearest('.fa').toggleClass('fa fa-spinner fa-spin');
            },
            
            complete: function() {
                //... your finalization code here (hide loader) ...
                // $("[class^=clubStatus]").nearest('.fa').toggleClass('fa fa-circle-o');
            },

            success: function(data) {  
               // update the fields according to server response
               $("input[id=userName" + data["entryId"] + "]").val(data["userName"]);
               $("input[id=ldapId" + data["entryId"] + "]").val(data["ldapId"]);
               $("input[id=club" + data["entryId"] + "]").val(data["userClub"]);
               $("input[id=comment" + data["entryId"] + "]").val(data["userComment"]);

               // COMMENT:
               // we update to server response instead of just saving user input
               // for the case when an entry has been updated recently by other user, 
               // but current user hasn't received a push-update from the server yet.
               //
               // This should later be substituted for "update highlighting", e.g.:
               // green  = "your data was saved successfully", 
               // red    = "server error, entry not saved (try again)", 
               // yellow = "other user updated before you, here's the new data"


               // UPDATE STATUS ICON HERE
               

               // debugging info
               console.log(data);
            },

            error: function (xhr, ajaxOptions, thrownError) {
                alert(JSON.stringify(xhr.responseJSON));
              }

        });

        // Prevent the form from actually submitting in browser
        return false; 

    });
 
});
