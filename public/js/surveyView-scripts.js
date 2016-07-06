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
    $(this).toggleClass("editButton btn btn-primary fa-pencil editButton btn btn-success fa-floppy-o ");
    
    if($(this).val() == '')
    {
        $(this).val('');
    }
    else if($(this).val() == '')
    {
        $(this).val('')
    }


});

function change_to_submit(number) {

    if ($('#editButton' + number).attr('type') == 'button') {
        setTimeout(function(){
            $('#editButton' + number).attr('type', 'submit');
        },100);
    }
    else {
        setTimeout(function(){
            $('#editButton' + number).attr('type', 'button');
        },100);
    }
}

////////////////////////////////////////
// AUTOCOMPLETE USERNAMES EDIT ANSWER //
////////////////////////////////////////

jQuery( document ).ready( function( $ ) {

   // var row_number = $('#get_row').val();

// open username dropdown on input selection and show only "I'll do it!" button at the beginning
        $("[class^=row]").on('focus', 'input', function () {
           $('.edit_drop').attr('style', 'display: block');
           $(document).find('.dropdown-username').hide();
    });

// hide all dropdowns on ESC keypress
    $(document).keyup(function (e) {
        if (e.keyCode === 27) {
            $(document).find('.edit_drop').hide();
        }
    });

// hide dropdown if users clicks on dropdown
    $("[class^=row]").on('click', 'ul', function () {
        alert('test');
        $('.edit_drop').attr('style', 'display: none');
    });
});


///////////////////////////////////
// AUTOCOMPLETE USERNAMES SURVEY //
///////////////////////////////////

jQuery( document ).ready( function( $ ) {
// open username dropdown on input selection and show only "I'll do it!" button at the beginning
    $('.nameToQuestion').find('input').on('focus', function () {
        // remove all other dropdowns
        $('.edit_drop').attr('style', 'display: none');
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

// hide dropdown if users clicks on dropdown
    $(document).find('.dropdown-username').click(function () {
        $(document).find('.dropdown-username').hide();
    });


/////////////////////////
// AUTOCOMPELETE CLUBS //
/////////////////////////
//
// // open club dropdown on input selection
// $( '.clubToQuestion' ).find('input').on( 'focus', function() {
//     // remove all other dropdowns
//     $(document).find('.dropdown-club').hide();
//     // open dropdown for current input
//     $(document.activeElement).parent().parent().children('.dropdown-club').show();
// } );
//
// // hide all dropdowns on ESC keypress
// $(document).keyup(function(e) {
//     if (e.keyCode === 27) {
//         $(document).find('.dropdown-club').hide();
//     }
// });
//
// $( '.clubToQuestion' ).find('input').on( 'input', function() {
//
//     // do all the work here after AJAX response is received
//     function ajaxCallBackClubs(response) {
//
//         // clear array from previous results, but leave first element with current user's data
//         $(document.activeElement).parent().parent().children('.dropdown-club').contents().remove();
//
//         // format data received
//         response.forEach(function(data) {
//
//             // add found clubs to the array$(document.activeElement).parent().children('.dropdown-club')
//             $(document.activeElement).parent().parent().children('.dropdown-club').append(
//                 '<li><a href="javascript:void(0);">'
//                 + '<span id="clubTitle">' + data.clb_title + '</span>'
//                 + '</a></li>');
//         });
//
//         // process clicks inside the dropdown
//         $(document.activeElement).parent().parent().children('.dropdown-club').children('li').click(function(e){
//
//             var clubTitle = $(this).find('#clubTitle').html();
//             //var currentEntryId = $(this).closest(".scheduleEntry").attr("id");
//
//             // update fields
//             $("input[id=club" + "]").val(clubTitle);
//
//         });
//
//         // reveal newly created dropdown
//         $(document.activeElement).parent().parent().children('.dropdown-club').show();
//
//     }
//
//     // short delay to prevents double sending
//     $(this).delay('250');
//
//     // Request autocompleted names
//     $.ajax({
//         type: $( this ).prop( 'method' ),
//
//         url: "/club/" + $(this).val(),
//
//         data: {
//             // We use Laravel tokens to prevent CSRF attacks - need to pass the token with each requst
//             "_token": $(this).find( 'input[name=_token]' ).val(),
//
//             // Most browsers are restricted to only "get" and "post" methods, so we spoof the method in the data
//             "_method": "get"
//         },
//
//         dataType: 'json',
//
//         success: function(response){
//             // external function handles the response
//             ajaxCallBackClubs(response);
//         },
//     });
// } );

});


/////////////////////
// EDITING ANSWERS //
/////////////////////

function get_answer_row(number) {
    $('#get_row').attr('value', number);
}

jQuery( document ).ready( function( $ ) {


    var count_clicks = 0;

    $(this).find(".fa-pencil").click(function () {

        var counter = $(this).attr('id').slice(10, 20);

        // Adding answerid to the update form
        $(document).find('.update').attr('action', window.location.href+'/answer/'+counter);

        count_clicks++;

        $('.table').find('input').each(function () {
            $(".editButton").not('#editButton' + counter).attr('disabled', 'disabled');
        });

        if (count_clicks === 1) {


            $(".row" + counter).find(".singleAnswer").attr('style', 'background-color: #B0C4DE');

            var i = -3;
            var question_counter = -3;

            $(".row" + counter).find(".singleAnswer").each(function () {

                var field_type = $('#field_type' + question_counter).val();
                var OriginalContent = $(this).text();
                var x = 0;

                var radio_counter = 10;

                i++;
                question_counter++;
               

                if (i == -2) {
                    $(this).addClass("cellEditing" + i +" dropdown").attr('id', 'cellEditing' + i);
                    $(this).html("<input id='newName2' name='name' type='text' value='" + OriginalContent.trim() + "' />" +
                                    "<ul id='dropdown-menu_name2' class='dropdown-menu edit_drop'>" +
                                        "<li id='yourself'>" +
                                            "<a href='"+'javascript:void(0);'+"' onClick='"+ 'document.getElementById("newName2").value=$("#hdnSession_userName").val(); document.getElementById("newClub").value=$("#hdnSession_userClub").val(); document.getElementById("ldapID_edit").value=$("#hdnSession_userID").val();' +"'</a>" +
                                                "<b>Mich eintragen!</b>" +
                                            "</a>" +
                                        "</li>" +
                                    "</ul>" +
                    "<input type='hidden' name='ldapID_edit' id='ldapID_edit' value='' >");
                }

                if (i == -1) {
                    $(this).addClass("cellEditing" + i).attr('id', 'cellEditing' + i);
                    $(this).html("<input id='newClub' name='club' type='text' value='" + OriginalContent.trim() + "' />");
                }

                if (i > -1 && field_type == 3) {
                    var selected_answer_dropdown = $(this).text().trim();

                    $(this).addClass("cellEditing" + i).attr('id', 'dropdown');
                    $(this).html("<select class='form-control' id='" + i + "' name='answers[" + question_counter +"]' style='font-size: 13px;' >");

                    $('#options' + i).find('option').each(function () {

                        var new_option = document.createElement("option");
                        var options = document.createTextNode(document.getElementById('options' + i).options[x].innerHTML);
                        new_option.appendChild(options);
                        var dropdown = document.getElementById(""+i);
                        dropdown.appendChild(new_option);
                        x++;
                        $("#" + i).attr('style', 'font-size: 13px;height: 22px;padding: 0px');
                    });

                    //Get select object
                    var objSelect = document.getElementById(""+i);

                    //Set selected
                    setSelectedValue(objSelect, selected_answer_dropdown);

                    function setSelectedValue(selectObj, valueToSet) {
                        for (var i = 0; i < selectObj.options.length; i++) {
                            if (selectObj.options[i].text== valueToSet) {
                                selectObj.options[i].selected = true;
                                return;
                            }
                        }
                    }
                }


                if (i > -1 && field_type == 1) {
                    $(this).addClass("cellEditing" + i).attr('id', 'text');
                    $(this).html("<input id='"+i+"' name='answers[" + question_counter +"]' type='text' value='" + OriginalContent.trim() + "' />");
                }

                if (i > -1 && field_type == 2) {
                    var selected_answer_radio = $(this).text().trim();

                    $(this).addClass("cellEditing" + i).attr('id', 'radio'+i);
                    $(this).html("");
                    var y = 0;
                    $('.question' + i).find('input:radio').each(function () {

                        var new_radio = document.createElement("input");
                        new_radio.setAttribute('type', 'radio');
                        new_radio.setAttribute('data-id', 'radio' + i + '-' + radio_counter);
                        new_radio.setAttribute('id', '' + i);
                        new_radio.setAttribute('name', 'answers[' + question_counter + ']');
                        var radio_text = document.createTextNode(document.getElementById('radio' + i + '-' + y).value);

                        new_radio.setAttribute('value', document.getElementById('radio' + i + '-' + y).value);
                        new_radio.appendChild(radio_text);
                        var radio = document.getElementById('radio'+i);
                        radio.appendChild(new_radio);

                        y++;
                        radio_counter++;

                    });

                    $("input[data-id=radio" + i + "-10]").after("Ja   ");
                    $("input[data-id=radio" + i + "-11]").after('Nein   ');
                    $("input[data-id=radio" + i + "-12]").after('keine Angabe');

                    if (selected_answer_radio == "Ja") {
                        $("input[data-id=radio" + i + "-10]").prop("checked", true);
                    }
                    if (selected_answer_radio == "Nein") {
                        $("input[data-id=radio" + i + "-11]").prop("checked", true);
                    }
                    if (selected_answer_radio == "keine Angabe") {
                        $("input[data-id=radio" + i + "-12]").prop("checked", true);
                    }

                }

            });

        }

        else {
            return true;
        }

    });


    $("form").find('.update').on('submit', function () {

        var counter_ajax = $('#get_row').val();
        var count_answers = 0;
        var checked_answers = [];

        $(".row"+counter_ajax).find(".singleAnswer").not(".cellEditing-1").not(".cellEditing-2").each(function () {

            if ($(this).attr('id') == 'radio'+count_answers) {
                var answers = $(this).find('input:checked').val();
                checked_answers.push(answers);

            }
            if ($(this).attr('id') == 'text' || $(this).attr('id') == 'dropdown') {
                var answers = $("#"+ count_answers).val();
                checked_answers.push(answers);
            }

            count_answers++;
        });



        $.ajax({

            type: $(this).prop('method'),
            url: $(this).prop('action'),

            data: JSON.stringify({

                // We use Laravel tokens to prevent CSRF attacks - need to pass the token with each requst
                "_token": $(document).find('input[name=_token]').val(),

                "name": $('.row' + counter_ajax).find("[name^=name]").val(),
                "club": $('.row' + counter_ajax).find("[name^=club]").val(),
                "ldapId": $('.row' + counter_ajax).find("[name^=ldapID_edit]").val(),
                "answers": checked_answers,
                "_method": "put"

            }),
            dataType: 'json',

            contentType: 'application/json',

            complete: function () {

                var counter = $('#get_row').val();

                var column_counter = -3;
                $('.row' + counter).find("[class^=singleAnswer]").each(function () {
                    column_counter++;

                    if ($(this).attr('id') == 'cellEditing-2') {
                        var newContent = $(this).find('input').val();
                        $(this).text(newContent);
                    }

                    if ($(this).attr('id') == 'cellEditing-1') {
                        var newContent = $(this).find('input').val();
                        $(this).text(newContent);
                    }

                    if ($(this).attr('id') == 'text') {
                        var newContent = $(this).find('input').val();
                        $(this).text(newContent);
                    }

                    if ($(this).attr('id') == 'radio'+column_counter) {
                        if ($(this).find('input:checked').val() == 1) {
                            var newContent = "Ja";
                            $(this).text(newContent);
                        }

                        if ($(this).find('input:checked').val() == 0) {
                            var newContent = "Nein";
                            $(this).text(newContent);
                        }

                        if ($(this).find('input:checked').val() == -1) {
                            var newContent = "keine Angabe";
                            $(this).text(newContent);
                        }
                    }

                    if ($(this).attr('id') == 'dropdown') {
                        var skillsSelect = document.getElementById(""+column_counter);
                        var newContent = skillsSelect.options[skillsSelect.selectedIndex].text;
                        $(this).text(newContent);
                    }

                    count_clicks = 0;

                    $(".row" + counter).find(".singleAnswer").attr('style', '');


                });
                $('.row' + counter).find('td').each(function () {
                    $("#radio"+column_counter).attr('id', '');
                });

                $('.table').find('input').each(function () {
                    $(".editButton").not('#editButton' + counter).prop('disabled', false);
                });

            },

            success: function (insertedData) {

            },

            error: function (xhr, ajaxOptions, thrownError) {
                alert(JSON.stringify(xhr.responseJSON));
            }

        });
        return false;
    });
});





