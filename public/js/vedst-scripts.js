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
		$(this).parent().parent().next().children().children('.hide').toggleClass('show');
		window.location.hash="";
	});
});


// Filter events from club/cafe, using browser local storage if enabled to save state
$(document).ready(function() {			    
    $('#bc-Club').change(function() {
        if($(this).prop("checked")) {
            $('.bc-Club').show();
            if(typeof(Storage) !== "undefined") {
            	localStorage.filterClub = "show";
            } 
        } else {
            $('.bc-Club').hide(); 
           	if(typeof(Storage) !== "undefined") {
            	localStorage.filterClub = "hide";
            } 
        }         
    });
});

$(document).ready(function() {			    
    $('#bc-Café').change(function() {
        if($(this).prop("checked")) {
            $('.bc-Café').show();
            if(typeof(Storage) !== "undefined") {
            	localStorage.filterCafe = "show";
            }			        
        } else {
            $('.bc-Café').hide();  
           	if(typeof(Storage) !== "undefined") {
            	localStorage.filterCafe = "hide";
            } 
        }			              
    });
});

$(document).ready(function() {
	if(typeof(Storage) !== "undefined") {
		if (localStorage.filterClub == "hide") {
        	$("#bc-Club").prop('checked', false);
        	$('.bc-Club').hide();		        	
        } else {
        	$("#bc-Club").prop('checked');
        	$('.bc-Club').show();			        	
        }

		if (localStorage.filterCafe == "hide") {
        	$("#bc-Café").prop('checked', false);
        	$('.bc-Café').hide();
        } else {
        	$("#bc-Café").prop('checked');
        	$('.bc-Café').show();			        	
        }

    } else {
    	if ( "{{ Session::get('clubFilter') }}" === "bc-Café" ) {
        	$("#bc-Club").prop('checked', false);
        	$('.bc-Club').hide();
    	} else if ( "{{ Session::get('clubFilter') }}" === "bc-Club" ) {
        	$("#bc-Café").prop('checked', false);
        	$('.bc-Café').hide();
    	} else {
        	$("#bc-Café").prop('checked');
        	$('.bc-Café').show();
        	$("#bc-Club").prop('checked');
        	$('.bc-Club').show();			    		
    	}
    }
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


// TESTING AJAX

          $('#input-test').focusout(function() {

            // start a spinner in the username field
            // until I get a reponse from the server
            $('#username-addon').html('<i class="fa fa-spinner fa-spin"></i>');

            var username = $('#input-test').val();

            $.post("/vedst-dev/ajax/posted", { 'input-test': username }, function(data) {
              
              if(data == "match")
              {
                $('#username-addon').html('<i class="fa fa-check"></i>');
                $('#username-group').removeClass('has-error');
                $('#username-group').addClass('has-success');

              } else {

                $('#username-addon').html('<i class="fa fa-ban"></i>');
                $('#username-group').addClass('has-error');
                $('#username-group').removeClass('has-');
              }

            });

          });
