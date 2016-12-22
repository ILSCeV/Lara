/*
    Scripts for surveyView
    Reaction to click on edit delete buttons
*/
function getPasswordField() {
    return $('.panel-warning').find("[name^=password]");
}

function promptForPassword() {
    return new Promise(function(resolve, reject) {
        bootbox.prompt('Bitte noch das Passwort für diese Umfrage eingeben:', function(password) {
            if (password) {
                resolve(password);
            }
            else {
                reject(password);
            }

        });
    });
}
$(document).ready(function() {

    $('.store').on('submit', function () {
        // For passworded surveys: check if a password field exists and is not empty
        // We will check correctness on the server side

        var that = this;
        var passwordField = getPasswordField();
        if (passwordField.length && !passwordField.val()) {
            promptForPassword().then(function(password) {
                passwordField.val(password);
                $(that).submit();
            }, function() {
                bootbox.alert("Ohne Passwort wird das nichts!");
            });
            return false;
        }
    });
});


function confirmDeletion() {
    return new Promise(function (resolve, reject) {
        bootbox.confirm("Wirklich löschen?", function (result) {
            if (result) {
                resolve();
            }
            else {
                reject();
            }
        });
    });
}
$(".deleteRow").click(function () {
    var link = $(this);
    var passwordField = getPasswordField();
    var doDeletion = function() {
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
                'value': 'delete'
            });

        var passwordInput = $('<input>', {
            'name': 'password',
            'type': 'hidden',
            'value': passwordField.val()
        });

        form.append(token, hiddenInput, passwordInput)
            .appendTo('body');
        form.submit();
    };
    if (passwordField.length && !passwordField.val()) {
        promptForPassword().then(function(password) {
            passwordField.val(password);
            return confirmDeletion();
        }, function() {
            bootbox.alert("Ohne Passwort wird das nichts!");
            return new Promise(function (resolve, reject) {
                reject();
            });
        }).then(doDeletion);
    }
    else {
        confirmDeletion().then(doDeletion());
    }
    return false;
});

//Replace edit icon with save icon
$('.editButton').click(function() {
    $('#display_advance').toggle('1000');
    $(this).addClass("editButton btn btn-success fa-floppy-o");
    $(this).removeClass("btn-primary fa-pencil");
    $(this).val('');
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
        $('.edit_drop').attr('style', 'display: none');
    });

});

$('input').focusout(function() {
    // hack to allow for click to register before focusout is called
    if ($(this).prop('id') === 'newName') {
        setTimeout(function() {
            $('#dropdown-menu_name').hide();
        }, 200);
    }
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

$(document).ready(function() {

    window.onresize = resize_evaluation_heading();
    function resize_evaluation_heading() {
        var height_evaluation = $('#evaluation').height();

        $(document).find('.evaluation_heading').attr('style', 'height:'+height_evaluation+'px;');
    }
});

/////////////////////
// EDITING ANSWERS //
/////////////////////

function get_answer_row(number) {
    $('#get_row').attr('value', number);
}

jQuery( document ).ready( function( $ ) {
    function submitChanges() {
        var password = getPasswordField().val();
        var counter_ajax = $('#get_row').val();
        var count_answers = 0;
        var checked_answers = [];
        var req_error = [];

        $(".row"+counter_ajax).find(".singleAnswer").not(".cellEditing-1").not(".cellEditing-2").each(function () {

            if ($(this).attr('id') == 'text' && $(this).attr('data-id') == 'required' && $("#"+ count_answers).val() == "") {
                var error = "required_missing";
                req_error.push(error);
            }

            if ($(this).attr('id') == 'radio' +count_answers + '-' + counter_ajax) {
                var answers = $(this).find('input:checked').val();
                checked_answers.push(answers);

            }
            if ($(this).attr('id') == 'text' || $(this).attr('id') == 'dropdown') {
                var answers = $("#"+ count_answers).val();
                checked_answers.push(answers);
            }

            count_answers++;
        });

        req_error.push("no_required_missing");

        $.ajax({

            type: $(this).prop('method'),
            url: $(this).prop('action'),

            data: JSON.stringify({

                // We use Laravel tokens to prevent CSRF attacks - need to pass the token with each request
                "_token": $(document).find('input[name=_token]').val(),

                "name": $('.row' + counter_ajax).find("[name^=name]").val(),
                "club": $('.row' + counter_ajax).find("[name^=club]").val(),
                "ldapId": $('.row' + counter_ajax).find("[name^=ldapID_edit]").val(),
                "answers": checked_answers,
                "password": password,
                "error": req_error,
                "_method": "put"

            }),
            dataType: 'json',

            contentType: 'application/json',

            beforeSend: function () {

                $('#editButton' + counter_ajax).removeClass("fa-floppy-o");
                $('#editButton' + counter_ajax).attr('value', '');
                $('#spinner' + counter_ajax).removeClass("hidden");

            },

            complete: function () {

                $('#editButton' + counter_ajax).val('');

                $('#editButton' + counter_ajax).removeClass("editButton btn btn-success fa-floppy-o");

                $('#editButton' + counter_ajax).addClass("editButton btn btn-primary fa-pencil");

            },

            //updates the content of the cells with the new content if it has been edited
            success: function (data) {

                var answer_number = $('#get_row').val();

                var column_counter = -3;
                $('.row' + answer_number).find("[class^=singleAnswer]").each(function () {
                    column_counter++;


                    if ($(this).attr('id') == 'cellEditing-2') {
                        var newContent = $(this).find('input').val();
                        $(this).html("<i id='userStatus' ></i> " + newContent);
                    }

                    if ($(this).attr('id') == 'cellEditing-1') {
                        var newContent = $(this).find('input').val();
                        $(this).text(newContent);
                    }

                    if ($(this).attr('id') == 'text') {
                        var newContent = $(this).find('input').val();
                        $(this).text(newContent);
                    }

                    if ($(this).attr('id') == 'radio' + column_counter + '-' + counter_ajax) {
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
                        var skillsSelect = document.getElementById("" + column_counter);
                        var newContent = skillsSelect.options[skillsSelect.selectedIndex].text;
                        $(this).text(newContent);
                    }

                    count_clicks = 0;

                    $("#userStatus").attr("style", data["user_status"]["style"]);
                    $("#userStatus").attr("data-original-title", data["user_status"]["title"]);
                    $("#userStatus").removeClass().addClass(data["user_status"]["status"]).removeAttr("id");


                    $(".row" + answer_number).find(".singleAnswer").attr('style', '');


                });
                $('.row' + answer_number).find('td').each(function () {
                    $("#radio" + column_counter + '-' + counter_ajax).attr('id', '');
                });

                $('.table').find('input').each(function () {
                    $(".editButton").not('#editButton' + answer_number).prop('disabled', false);
                });
                $(".answer_button").prop('disabled', false);

                $('#spinner' + counter_ajax).addClass('hidden');

                oldContent = [];

            },

            //sets the color circles for the members depending on their rank
            error: function (xhr, ajaxOptions, thrownError) {
                bootbox.alert(JSON.stringify(xhr.responseJSON));

                var answer_number = $('#get_row').val();
                var column_counter = 0;

                $('.row' + answer_number).find("[class^=singleAnswer]").each(function () {

                    if (column_counter == 0) {
                        var newContent = oldContent[column_counter];

                        if (userStatusIcon[0] == "Kandidat") {
                            $(this).html("<i class='fa fa-adjust' name='status-icon' style='color:yellowgreen;' title='Kandidat'></i> " + newContent);
                        }

                        if (userStatusIcon[0] == "Veteran") {
                            $(this).html("<i class='fa fa-star' name='status-icon' style='color:gold;' title='Veteran'></i> " + newContent);
                        }

                        if (userStatusIcon[0] == "Aktiv") {
                            $(this).html("<i id='userStatus' class='fa fa-circle' name='status-icon' style='color:forestgreen;' ></i> " + newContent);
                        }

                        if (userStatusIcon[0] == "ex-Mitglied") {
                            $(this).html("<i class='fa fa-star-o' name='status-icon' style='color:gold;' title='ex-Mitglied'></i> " + newContent);
                        }

                        if (userStatusIcon[0] == "Extern") {
                            $(this).html("<i class='fa fa-circle' name='status-icon' style='color:lightgrey;' title='Extern'></i> " + newContent);
                        }

                    }

                    else

                        $(this).text(oldContent[column_counter]);
                    column_counter++;

                });

                count_clicks = 0;

                $(".row" + answer_number).find(".singleAnswer").attr('style', '');


                $('.row' + answer_number).find('td').each(function () {
                    $("#radio" + column_counter + '-' + counter_ajax).attr('id', '');
                });

                $('.table').find('input').each(function () {
                    $(".editButton").not('#editButton' + answer_number).prop('disabled', false);
                });
                $(".answer_button").prop('disabled', false);

                $('#spinner' + counter_ajax).addClass('hidden');

                oldContent = [];
            }

        });
        return false;
    };

    $('form').find('.update').on('submit', function () {
        // For passworded surveys: check if a password field exists and is not empty
        // We will check correctness on the server side
        var that = this;
        var $passwordField = getPasswordField();
        if ($passwordField.length && !$passwordField.val()) {
            bootbox.prompt('Bitte noch das Passwort für diese Umfrage eingeben:', function(password) {
                if (password) {
                    $passwordField.val(password);
                    submitChanges.call(that);
                }
            });
            return false;
        }
        submitChanges.call(that);
        return false;
    });

    var count_clicks = 0;

    // Array with saved user status icon
    var userStatusIcon = [];

    // Array for saving current content
    var oldContent = [];

    $(this).find(".fa-pencil").click(function () {

        var answer_number = $(this).attr('id').slice(10, 20);

        // Pushing user status icon to array for accessibility in ajax error handling
        var get_userStatusIcon = $(".row" + answer_number).find("[name^=status-icon]").attr('title');
        userStatusIcon.push(get_userStatusIcon);


        var check_url = window.location.href;

        // Adding answerid to the update form and checking if url is correct
        if (check_url.match('3/') || check_url.match('3#')) {
            var window_loc = window.location.href;
            var window_loc_cut = window_loc.substr(0, window_loc.length-1);
            $(document).find('.update').attr('action', window_loc_cut+'/answer/'+answer_number);
        }
        else {
            $(document).find('.update').attr('action', window.location.href+'/answer/'+answer_number);
        }

        count_clicks++;


        $('.table').find('input').each(function () {
            $(".editButton").not('#editButton' + answer_number).attr('disabled', 'disabled');
        });

        $(".tdButtons ").find(".answer_button").attr('disabled', 'disabled');

        if (count_clicks === 1) {


            $(".row" + answer_number).find(".singleAnswer").attr('style', 'background-color: #B0C4DE');

            var i = -3;
            var question_counter = -3;

            $(".row" + answer_number).find(".singleAnswer").each(function () {

                var field_type = $('#field_type' + question_counter).val();
                var OriginalContent = $(this).text();
                var x = 0;

                var radio_counter = 10;

                i++;
                question_counter++;

                var is_required = $('#question_required' + question_counter).val();

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

                    if (is_required == 1) {
                        $(this).attr('data-id', 'required');
                    }
                }

                if (i > -1 && field_type == 2) {
                    var selected_answer_radio = $(this).text().trim();

                    $(this).addClass("cellEditing" + i).attr('id', 'radio'+i+ '-' + answer_number);
                    $(this).html("");
                    var y = 0;
                    $('.question' + i).find('input:radio').each(function () {

                        var new_radio = document.createElement("input");
                        new_radio.setAttribute('type', 'radio');
                        new_radio.setAttribute('data-id', 'radio' + i + '-' + radio_counter);
                        new_radio.setAttribute('id', '' + i);
                        new_radio.setAttribute('name', 'answers[' + question_counter + ']-edit');
                        var radio_text = document.createTextNode(document.getElementById('radio' + i + '-' + y).value);

                        new_radio.setAttribute('value', document.getElementById('radio' + i + '-' + y).value);
                        new_radio.appendChild(radio_text);
                        var radio = document.getElementById('radio'+i+ '-' + answer_number);
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

                // Pushing old content into array "oldContent" so it does not get lost
                if ($(this).attr('id') == 'cellEditing-2') {
                    var answers = $("#newName2").val();
                    oldContent.push(answers);
                }

                if ($(this).attr('id') == 'cellEditing-1') {
                    var answers = $("#newClub").val();
                    oldContent.push(answers);
                }

                if ($(this).attr('id') == 'radio' + question_counter + '-' + answer_number) {
                    if ($(this).find('input:checked').after().val() == 1) {
                        var answers = "Ja";
                    }
                    if ($(this).find('input:checked').after().val() == 0) {
                        var answers = "Nein";
                    }
                    if ($(this).find('input:checked').after().val() == -1) {
                        var answers = "keine Angabe";
                    }

                    oldContent.push(answers);
                }

                if ($(this).attr('id') == 'text' || $(this).attr('id') == 'dropdown') {
                    var answers = $("#"+ question_counter).val();
                    oldContent.push(answers);
                }

            });

        }

        else {
            return true;
        }

    });


});

// handles expandable table-rows (for exampled in surveyView change-history)
function toggle(row, rowCount) {
    // change arrow icon
    arrowIcon = document.getElementById("arrow-icon"+row);
    if(arrowIcon.className == "fa fa-caret-right"){
        // if table is fold
        arrowIcon.className = "fa fa-sort-desc";
    } else {
        // if table is unfold
        arrowIcon.className = "fa fa-caret-right";
    }
    // display columns
    for (i=0;i<rowCount;i++) {
        currentTr = document.getElementById("tr-data-"+row+i);
        if(currentTr.style.display == "") {
            // fold
            currentTr.style.display = "none";
        } else {
            // unfold
            currentTr.style.display = "";
        }
    }
}



