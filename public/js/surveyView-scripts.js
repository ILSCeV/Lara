/*
    Scripts for surveyView
    Reaction to click on edit delete buttons
*/
$(document).ready(function() {

    $('form').on( 'submit', function () {
        // For passworded surveys: check if a password field exists and is not empty
        // We will check correctness on the server side
        if ($('.panel-warning').find("[name^=password]").length
            && !$('.panel-warning').find("[name^=password]").val()) {
            var password = window.prompt('Bitte noch das Passwort für diese Umfrage eingeben:');
            $('.panel-warning').find("[name^=password]").val(password);

        } else {
            var password = $('.panel-warning').find("[name^=password]").val();
        }
    });

});

//source: http://stackoverflow.com/questions/2441565/how-do-i-make-a-div-element-editable-like-a-textarea-when-i-click-it
//On click of edit(Number) div, all divs in that row should be editable
//Reworked below with generel Div Elements
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

//Edit of a Div element reworked
//use in form of $("div").click(divClicked):
//to swap a text field to an editable text field
    function divClicked () {
    //Textfield to work with.
    var editableText = $("<textarea/>");
    //First save old Text (this for whatever div element)
    var divText = $(this).text();
    //Fill it
    editableText.val(divText);
    //Now Replace the Div
    $(this).replaceWith(editableText);
    editableText.focus();
    editableText.blur(editableTextBlurred)
}

//Function to react to user leaving a text field in/after edit
    function editableTextBlurred () {
    //Variables that will be used
    var text = $(this).val();
    var editedText = $("<div>");

    editedText.text(text);
    $(this).replaceWith(editedText);
    $(editedText).click(divClicked);
}


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

                // now we convert our data to meaningful text - could have done it on server side, but this is easier for now:
                // convert club_id to text
                if (data.clb_id == 1) { data.clb_id = "Kein Club" }
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

               // var currentEntryId = $(this).closest(".nameToQuestion").attr("id");
                
                // update fields
                $("input[id=newName" + "]").val(currentName);
                $("input[id=club" + "]").val(currentClub);
                $("input[id=ldapId" + "]").val(currentLdapId);


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


/////////////////////////
// AUTOCOMPELETE CLUBS //
/////////////////////////

// open club dropdown on input selection
$( '.clubToQuestion' ).find('input').on( 'focus', function() {
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

$( '.clubToQuestion' ).find('input').on( 'input', function() {

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
            //var currentEntryId = $(this).closest(".scheduleEntry").attr("id");

            // update fields
            $("input[id=club" + "]").val(clubTitle);

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





