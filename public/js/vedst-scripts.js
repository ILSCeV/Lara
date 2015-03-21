// Automatically close messages after 4 seconds (4000 milliseconds). M.
window.setTimeout(function() {
    $(".message").fadeTo(1000, 0).slideUp(500, function(){
        $(this).alert('close'); 
    });
}, 4000);


// Show/hide more button for infos
$(function(){
	$('.moreless').click(function(e) {
		$(this).parent().children('.more').toggleClass('moreshow');
		window.location.hash="navbar";
	});
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
