import {translate} from "../Translate";
import 'bootstrap/js/dist/tooltip'
import 'bootstrap/js/dist/alert'
import {createMessage} from "../common/messages";

// conversion of html entities to text (e.g. "&" as "&amp;")
// ref: https://stackoverflow.com/questions/1147359/how-to-decode-html-entities-using-jquery
let decodeEntities = (encodedString) => {
  let textArea = document.createElement('textarea');
  textArea.innerHTML = encodedString;
  return textArea.value;
};

$(() => {
/////////////////////////////
// AUTOCOMPLETE USERNAMES //
/////////////////////////////
    // open username dropdown on input selection and show only "I'll do it!" button at the beginning
    $('.shift').find('input').on('focus', () => {
      // remove all other dropdowns
      $('.dropdown-username').hide();
      // open dropdown for current input
      $(document.activeElement).parent().children('.dropdown-username').show();
    });

    // hide all dropdowns on ESC keypress
    $(document).on("keyup" , function (e) {
      if (e.key === "Escape") {
        $('.dropdown-username').hide();
      }
    });

    $('.shift.autocomplete').closest('.shiftRow').find("input[id^='userName'], input[id^=comment]").on('input', function () {
      let self = $(this).closest('.shiftRow');
      // show only current button
      self.find('[name^=btn-submit-change]')
        .addClass('hide')
        .removeClass('btn-primary');
      self.find('[name^=btn-submit-change]')
        .removeClass('hide')
        .addClass('btn-primary');

      // hide only current icon
      self.find('[name^=status-icon]').removeClass('hide');
      self.find('[name^=status-icon]').addClass('hide');

      // do all the work here after AJAX response is received
      function ajaxCallBackUsernames(response) {

        // clear array from previous results, but leave first element with current user's data
        const $dropdown = $(document.activeElement).parent().children('.dropdown-username');

        // clear array from previous results, but leave first element with current user's data
        $dropdown.children().filter(function (index, item) {
          return index !== 0;
        }).remove();

        // format data received
        response.forEach(function (data) {
          // convert person_status to text
          if (data.prsn_status == 'candidate') {
            data.prsn_status = " (K)"
          } else if (data.prsn_status == 'veteran') {
            data.prsn_status = " (V)"
          } else if (data.prsn_status == 'resigned') {
            data.prsn_status = " (ex)"
          } else {
            data.prsn_status = ""
          }
          let onLeave: String = '';
          if (data.user && data.user.on_leave) {
            let today = new Date();
            if (today.getTime() < new Date(data.user.on_leave).getTime()) {
              onLeave = ' (U: ' + (new Date(data.user.on_leave)).toLocaleDateString() + ') ';
            }
          }
          let onLeaveClass = onLeave ? 'bg-danger' : '';
          if (data.user) {
            // add found persons to the array
            $dropdown.append(
              `<li class='dropdown-item ${onLeaveClass}'><a href='javascript:void(0);'>`
              + '<span name="currentLdapId" hidden>' + data.prsn_ldap_id + '</span>'
              + '<span name="currentName">' + data.prsn_name + '</span>'
              + data.prsn_status + onLeave
              + '(<span name="currentClub">' + data.club.clb_title + '</span>)'
              + '&nbsp;<span name="tooltip" class="text-muted"> ' + data.user.givenname + ' ' + data.user.lastname + ' </span> '
              + '</a></li>');
          }
        });

        // process clicks inside the dropdown
        $dropdown.children('li').on({
          click: (e) => {
            // ignore "i'll do it myself" button (handeled in view)
            e.preventDefault();
            let startPoint = $(e.target).closest('li');
            if ($(startPoint).hasClass("yourself")) return false;

            // gather the data for debugging
            let currentLdapId = $(startPoint).find('[name="currentLdapId"]').html();
            let currentName = $(startPoint).find('[name="currentName"]').html();
            let currentClub = $(startPoint).find('[name="currentClub"]').html();
            let currentEntryId = $(startPoint).closest(".shift").data("shiftid");
            let tooltipText = $(startPoint).find('[name="tooltip"]').html();

            // update fields
            $("input[id=userName" + currentEntryId + "]").val(currentName);
            $("input[id=userName" + currentEntryId + "]")
              .tooltip('hide')
              .attr('data-original-title', tooltipText)
              .tooltip('show');
            setTimeout(()=>{$("input[id=userName" + currentEntryId + "]").tooltip('hide')}, 2000);
            $("input[id=ldapId" + currentEntryId + "]").val(currentLdapId);
            $("input[id=club" + currentEntryId + "]").val(currentClub);
            // send to server
            // need to go via click instead of submit because otherwise ajax:beforesend, complete and so on won't be triggered
            $("#btn-submit-changes" + currentEntryId).trigger('click');
          }
        });

        // reveal newly created dropdown
        $dropdown.show();
      } //end of ajaxCallBackUsernames

      // short delay to prevents double sending
      $(this).delay(250);

      // Request autocompleted names
      $.ajax({
        type: $(this).prop('method'),

        url: "/person/" + $(this).val(),

        data: {
          // We use Laravel tokens to prevent CSRF attacks - need to pass the token with each requst
          "_token": $(this).find('input[name=_token]').val(),

          // Most browsers are restricted to only "get" and "post" methods, so we spoof the method in the data
          "_method": "get"
        },

        dataType: 'json',

        success: function (response) {
          // external function handles the response
          ajaxCallBackUsernames(response);
        },
      });
    });


/////////////////////////
// AUTOCOMPLETE CLUBS //
/////////////////////////

    // hide all dropdowns on ESC keypress
    $(document).on("keyup", function (e) {
      if (e.key === "Escape") {
        $(document).find('.dropdown-club').hide();
      }
    });
    // open club dropdown on input selection
    $('.shift').find('input').on('focus', function () {
      // remove all other dropdowns
      $('.dropdown-club').hide();
      // open dropdown for current input
      $(document.activeElement).parent().parent().children('.dropdown-club').show();
    });


    $('.shift').find("input[id^='club']").on('input', function () {
      let self = $(this).closest('.shiftRow');
      // Show save icon on form change
      self.find('[name^=btn-submit-change]').removeClass('hide');
      self.find("[name^=status-icon]").addClass('hide');

      // do all the work here after AJAX response is received
      function ajaxCallBackClubs(response) {

        // clear array from previous results, but leave first element with current user's data
        $(document.activeElement).parent().parent().children('.dropdown-club').contents().remove();

        // format data received
        response.forEach(function (data) {

          // add found clubs to the array$(document.activeElement).parent().children('.dropdown-club')
          $(document.activeElement).parent().parent().children('.dropdown-club').append(
            '<li class="dropdown-item"><a href="javascript:void(0);">'
            + '<span class="clubTitle">' + data.clb_title + '</span>'
            + '</a></li>');
        });

        // process clicks inside the dropdown
        $(document.activeElement).parent().parent().children('.dropdown-club').children('li').on({
          click: function (e) {

            let clubTitle = $(e.target).text();
            let currentEntryId = $(e.target).closest(".shift").data("shiftid");

            // update fields
            $("input[id=club" + currentEntryId + "]").val(clubTitle);

            // send to server
            // need to go via click instead of submit because otherwise ajax:beforesend, complete and so on won't be triggered
            $("#btn-submit-changes" + currentEntryId).trigger('click');

          }
        });

        // reveal newly created dropdown
        $(document.activeElement).parent().parent().children('.dropdown-club').show();

      }

      // short delay to prevents double sending
      $(this).delay(250);

      // Request autocompleted names
      $.ajax({
        type: $(this).prop('method'),

        url: "/club/" + $(this).val(),

        data: {
          // We use Laravel tokens to prevent CSRF attacks - need to pass the token with each requst
          "_token": $(this).find('input[name=_token]').val(),

          // Most browsers are restricted to only "get" and "post" methods, so we spoof the method in the data
          "_method": "get"
        },

        dataType: 'json',

        success: function (response) {
          // external function handles the response
          ajaxCallBackClubs(response);
        },
      });
    });
    /////////////////////////////
    // AUTOCOMPLETE SHIFTTYPES //
    /////////////////////////////
    function updateShiftEntry(data: any, isConflict: boolean) {
      const $spinner = $("#spinner");
      const entryId = data.entryId;
      const $userNameInput = $("input[id=userName" + entryId + "]");
      const $ldapInput = $("input[id=ldapId" + entryId + "]");
      const $timestampInput = $("input[id=timestamp" + entryId + "]");
      const $clubInput = $("input[id=club" + entryId + "]");
      const $commentInput = $("input[id=comment" + entryId + "]");
      const $row = $userNameInput.closest('.row');

      if (isConflict) {
        let $alert = $('<div id="alert' + entryId + '" class="alert alert-dismissible alert-warning clear-both col-md-12">\n' +
          '<button type="button" class="close" data-dismiss="alert">&times;</button>\n' +
          '<strong>' + translate("conflictDetected") + '</strong>\n<i class="fa fa-3x fa-history float-right"></i>' +
          '<p>' + translate("conflictAlertLine1") + '</p>' +
          '<p>' + translate("conflictAlertLine2") + '</p>\n' +
          '</div>');
        $alert.alert();
        $row.append($alert);
        // (<any>window).isotope?(<any>window).isotope.layout() : null;
      }

      if (isConflict && $userNameInput.val() !== data.userName) {
        $userNameInput.addClass("input-warning");
      } else {
        $userNameInput.removeClass("input-warning");
      }
      if (isConflict && $commentInput.val() !== data.userComment) {
        $commentInput.addClass("input-warning");
      } else {
        $commentInput.removeClass("input-warning");
      }
      if (isConflict && $clubInput.val() !== data.userClub) {
        $clubInput.addClass("input-warning");
      } else {
        $clubInput.removeClass("input-warning");
      }

      $userNameInput.val(data.userName).attr("placeholder", "=FREI=");
      $ldapInput.val(data.ldapId);
      $timestampInput.val(data.timestamp);
      $clubInput.val(data.userClub).attr("placeholder", "-");
      $commentInput.val(data.userComment).attr("placeholder", translate("addCommentHere"));

      // Switch comment icon in week view
      if ($commentInput.val() == "") {
        $commentInput.parent().children().children("button").children("i").removeClass().addClass("fas fa-comment-alt");
      } else {
        $commentInput.parent().children().children("button").children("i").removeClass().addClass("fa fa-comment");
      }

      // Switch comment in event view
      if ($commentInput.val() == "") {
        $commentInput.parent().children("span").children("i").removeClass().addClass("fas fa-comment-alt");
      } else {
        $commentInput.parent().children("span").children("i").removeClass().addClass("fas fa-comment");
      }

      let $colorDiv = $userNameInput.closest('.shiftRow').find("div.shift_title");

      let isShiftEmpty = data["userName"] !== "";
      if (isShiftEmpty) {
        $colorDiv.removeClass("shift_free").addClass("shift_taken");
      } else {
        $colorDiv.removeClass("shift_taken").addClass("shift_free");
      }

      // UPDATE STATUS ICON
      // switch to normal user status icon and clear "spinner"-markup
      // we receive this parameters: e.g. ["status"=>"fa fa-adjust", "style"=>"color:yellowgreen;", "title"=>"Kandidat"]
      $spinner.attr("style", data["userStatus"]["style"]);
      $spinner.attr("data-original-title", data["userStatus"]["title"]);
      $spinner.removeClass().addClass(data["userStatus"]["status"]).removeAttr("id");


      $userNameInput.closest('.shiftRow').toggleClass('my-shift', data.is_current_user);
    }


    // open shiftType dropdown on input selection
    $('.box').find('input[type=text]').on('focus', function () {
      // remove all other dropdowns
      $('.dropdown-shiftTypes').hide();
      // open dropdown for current input
      $(document.activeElement).next('.dropdown-shiftTypes').show();
    });

    // hide all dropdowns on ESC keypress
    $(document).on("keyup",function (e) {
      if (e.key === "Escape") {
        $(document).find('.dropdown-shiftTypes').hide();
      }
    });

    $('.yourself').on(
      {
        click: (e) => {
          $(e.target).closest('.shiftRow').addClass('my-shift');
          if($(e.target).is('li')){
            $(e.target).find('a').trigger('click');
          }
        }
      });

    $('.box').find("input[name^='shifts\[title\]']").on('input', function () {
      // do all the work here after AJAX response is received
      function ajaxCallBackClubs(response) {

        // clear array from previous results
        $(document.activeElement).next('.dropdown-shiftTypes').contents().remove();

        // format data received
        response.forEach(function (data) {

          // add found shiftTypes and metadata to the dropdown
          $(document.activeElement).next('.dropdown-shiftTypes').append(
            '<li class="dropdown-item"><a href="javascript:void(0);">'
            + '<span id="shiftTypeTitle">'
            + data.title
            + '</span>'
            + ' (<i class="fa fa-clock-o"></i> '
            + '<span id="shiftTypeTimeStart">'
            + data.start
            + '</span>'
            + '-'
            + '<span id="shiftTypeTimeEnd">'
            + data.end
            + '</span>'
            + '<span id="shiftTypeWeight" class="hidden">'
            + data.statistical_weight
            + '</span>'
            + ')'
            + '</a></li>');
        });

        // process clicks inside the dropdown
        $(document.activeElement).next('.dropdown-shiftTypes').children('li').click(function (e) {
          let selectedShiftTypeTitle = decodeEntities($(this).find('#shiftTypeTitle').html());     // decoding html entities in the process
          let selectedShiftTypeTimeStart = $(this).find('#shiftTypeTimeStart').html();
          let selectedShiftTypeTimeEnd = $(this).find('#shiftTypeTimeEnd').html();
          let selectedShiftTypeWeight = $(this).find('#shiftTypeWeight').html();

          // update fields
          $(this).parents(".box").find("[name^='shifts[title]']").val(selectedShiftTypeTitle);
          $(this).parents(".box").find("[name^='shifts[start]']").val(selectedShiftTypeTimeStart);
          $(this).parents(".box").find("[name^='shifts[end]']").val(selectedShiftTypeTimeEnd);
          $(this).parents(".box").find("[name^='shifts[weight]']").val(selectedShiftTypeWeight);

          // close dropdown afterwards
          $(document).find('.dropdown-shiftTypes').hide();
        });

        // reveal newly created dropdown
        $(document.activeElement).next('.dropdown-shiftTypes').show();

      }

      // short delay to prevents double sending
      $(this).delay(250);

      // Request autocompleted names
      $.ajax({
        type: $(this).prop('method'),

        url: "/shiftTypes/" + $(this).val(),

        data: {
          // We use Laravel tokens to prevent CSRF attacks - need to pass the token with each requst
          "_token": $(this).find('input[name=_token]').val(),

          // Most browsers are restricted to only "get" and "post" methods, so we spoof the method in the data
          "_method": "get"
        },

        dataType: 'json',

        success: function (response) {
          // external function handles the response
          ajaxCallBackClubs(response);
        },
      });
    });


    // Submit changes
    $('.shift').on('submit', function () {

      let self = $(this).closest('.shiftRow');

      // For passworded schedules: check if a password field exists and is not empty
      // We will check correctness on the server side
      var password = "";
      if ($(this).parentsUntil($(this), '.card').find("[name^=password]").length
        && !$(this).parentsUntil($(this), '.card').find("[name^=password]").val()) {
        password = window.prompt('Bitte noch das Passwort für diesen Dienstplan eingeben:');
      } else {
        password = <string>$(this).parentsUntil($(this), '.card').find("[name^=password]").val();
      }
      // necessary for the ajax callbacks
      let currentId = $(this).data('shiftid');


      $.ajax({
        type: $(this).prop('method'),

        url: $(this).prop('action'),

        data: JSON.stringify({
          // We use Laravel tokens to prevent CSRF attacks - need to pass the token with each requst
          "_token": $(self).find('input[name=_token]').val(),

          // Actual data being sent below
          "entryId": currentId,
          "userName": $(self).find("[name^=userName]").val(),
          "ldapId": $(self).find("[name^=ldapId]").val(),
          "timestamp": $(self).find("[name^=timestamp]").val(),
          "userClub": $(self).find("[name^=club]").val(),
          "userComment": $(self).find("[name^=comment]").val(),
          "password": password,

          // Most browsers are restricted to only "get" and "post" methods, so we spoof the method in the data
          "_method": "put"
        }),

        dataType: 'json',

        contentType: 'application/json',

        beforeSend: function () {
          // console.log("beforesend");

          // hide dropdowns because they aren't no longer needed
          $('.dropdown-username').hide();
          $('.dropdown-club').hide();
          $('div#alert' + currentId).remove();

          // Remove save icon and show a spinner in the username status while we are waiting for a server response
          $('#btn-submit-changes' + currentId).addClass('hide')
            .parent()
            .children('i')
            .removeClass()
            .addClass("fa fa-spinner fa-spin")
            .attr("id", "spinner")
            .attr("data-original-title", "In Arbeit...")
            .css("color", "darkgrey");
        },

        complete: async function (jqXHR) {
          // console.log('complete');
          let responsiveClass = jqXHR.status == 200 ? 'bg-success' : 'bg-danger';
          self.addClass('animation').addClass(responsiveClass);
          await new Promise(resolve => setTimeout(resolve, 500));
          self.removeClass(responsiveClass);
        },
        success: function (data) {
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
          updateShiftEntry(data, false);
        },

        error: function (xhr, ajaxOptions, thrownError) {
          if (xhr.responseJSON && xhr.responseJSON.errorCode) {
            let json = xhr.responseJSON;
            if (json.errorCode === "error_outOfSync") {
              if (json.userStatus) {
                updateShiftEntry(json, true);
                return;
              } else {
                //alert(translate(xhr.responseJSON.errorCode));
                createMessage(translate('error'),xhr.responseJSON.errorCode,'bg-danger')
              }
            }
          } else {
            //alert(JSON.stringify(xhr.responseJSON));
            createMessage(translate('error') + ' ' + xhr.status, translate('sessionExpired'), 'bg-danger')
          }
          $("#spinner").removeClass().addClass("fa fa-exclamation-triangle").css("color", "red").attr("data-original-title", "Fehler: Änderungen nicht gespeichert!"); //TODO: translate
        },

      });

      // Prevent the form from actually submitting in browser
      return false;
    });
  }
);
