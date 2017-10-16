import * as $ from "jquery"

$( '.updateShiftType' ).on( 'submit',
    function() {
        $.ajax({
            type: $( this ).prop( 'method' ),

            url: $( this ).prop( 'action' ),

            data: JSON.stringify({
                // We use Laravel tokens to prevent CSRF attacks - need to pass the token with each requst
                "_token":       $(this).find( 'input[name=_token]' ).val(),

                // Actual data being sent below
                "entryId":      $(this).closest("form").attr("id"),
                "shiftTypeId":    $(this).find("[name^=shiftType]").val(),

                // Most browsers are restricted to only "get" and "post" methods, so we spoof the method in the data
                "_method": "put"
            }),

            dataType: 'json',

            contentType: 'application/json',

            beforeSend: function() {
                // console.log("beforesend");
            },

            complete: function() {
                // console.log('complete');
            },

            success: function(data) {
                //console.log("success");
                // remove row to indicate successful renaming of the shiftType
                $(".shiftType-event-row" + data["entryId"]).hide();

                // if all rows except table header were hidden (all shiftTypes substituted withn other ones),
                // refresh the page to get the delete button or show remaining shiftTypes
                if ($("#events-rows").children("tr:visible").length <= 1) {
                    // we remove arguments after "?" because otherwise user could land on a pagination page that is already empty
                    (<any>window).location = window.location.href.split("?")[0];
                }

            },

            error: function (xhr, ajaxOptions, thrownError) {
                alert(JSON.stringify(xhr.responseJSON));
            }

        });

        // Prevent the form from actually submitting in browser
        return false;

    }
)
