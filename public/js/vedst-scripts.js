// Automatically close messages after 4 seconds (4000 milliseconds). M.
window.setTimeout(function() {
    $(".message").fadeTo(1000, 0).slideUp(500, function(){
        $(this).alert('close'); 
    });
}, 4000);


// Show/hide more button for infos
$(function(){
	$('.moreless-more').click(function(e) {
		$(this).parent().children('.more').toggleClass('moreshow');
        $(this).parent().children('.more').css('height','auto'); 
        $(this).parent().children('.moreless-less').show();
        $(this).parent().children('.moreless-more').hide();
	});
});

$(function(){
    $('.moreless-less').click(function(e) {
        $(this).parent().children('.more').toggleClass('moreshow');
        $(this).parent().children('.more').css('height','125'); 
        $(this).parent().children('.more').height(125);  
        $(this).parent().children('.moreless-less').hide();
        $(this).parent().children('.moreless-more').show();  
    });
});

$(function(){
    $('.moreless-more').hide();
    $('.moreless-less').hide();
    if ($('.more').height() > 125) {   
        $('.more').height(125);        
        $('.moreless-more').show();
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
