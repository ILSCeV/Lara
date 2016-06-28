/*
    Scripts for surveyView
    Reaction to click on edit delete buttons
*/
$(document).ready(function() {
    //currently nothing
});

//source: http://stackoverflow.com/questions/2441565/how-do-i-make-a-div-element-editable-like-a-textarea-when-i-click-it
//On click of edid(Number) div, all divs in that row should be editable
$(".editRow1").click(function() {
    //save HTML within div
    var divText = $(".Name1").text();
    //create dynamic textarea
    var editableText = $("<textarea />");
    //fill textarea with text of former div
    editableText.val(divText);
    //replace div with textarea
    $(".Name1").replaceWith(editableText);
    //editableText.focus(); (optional)



});


//Replace edit icon with save icon
$('.editButton').click(function() {
    $('#display_advance').toggle('1000');
    $("i", this).toggleClass("fa-pencil fa-floppy-o");
});


///////////////////////////////////
// AUTOCOMPLETE USERNAMES SURVEY //
///////////////////////////////////

$(document).ready(function() {
// open username dropdown on input selection and show only "I'll do it!" button at the beginning
    $('.nameToQuestion').find('input').on('focus', function () {
        // remove all other dropdowns
        $(document).find('.dropdown-username').hide();
        // open dropdown for current input
        if ($(document.activeElement).parent().children('.dropdown-username').show()) {
        }
    });
// hide all dropdowns on ESC keypress
    $(document).keyup(function (e) {
        if (e.keyCode === 27) {
            $(document).find('.dropdown-username').hide();
        }
    });


    $( '.nameToQuestion' ).find('input').on( 'input', function() {
   

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
                    + '(<span id="currentClub">' + data.clb_id + '</span>)'
                    + '</a></li>');
            });

            // process clicks inside the dropdown
            $(document.activeElement).parent().children('.dropdown-username').children('li').click(function(e){
                // ignore "i'll do it myself" button (handeled in view)
                if ( this.id == "yourself") return false;

                // gather the data for debugging
             //   var currentLdapId = $(this).find('#currentLdapId').html();
                var currentName = $(this).find('#currentName').html();
                var currentClub = $(this).find('#currentClub').html();
               // var currentEntryId = $(this).closest(".nameToQuestion").attr("id");
                
                // update fields
                $("input[id=newName" + "]").val(currentName);
                $("select[id=sel1" + "]").prop('selectedIndex', currentClub);


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

});




