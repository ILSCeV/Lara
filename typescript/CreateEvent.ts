///<reference path="../typings/index.d.ts"/>
///<reference path="Utilities.ts"/>

// values of events that should trigger the selection of all clubs
let internalEventValues = [
    "1", // Info
    "4", // Internal event
    "5", // private party
    "6", // cleaning
    "9"  // other
];

$(() => {
    // if set, internal events will trigger selection of all clubs
    // if user sets the club manually, we want to keep his selection
    let autoSelectAllClubs = true;
    let allClubCheckBoxes = $("#filter").find("input[type=checkbox]");
    allClubCheckBoxes.click(() => { autoSelectAllClubs = false; });

    // important to use function() (anonymous function) here an not an arrow function
    // using a lambda will change the "this" inside the
    $("[name='evnt_type']").click(function() {
        let prop = $(this).val();
        let isInternalEvent = internalEventValues.indexOf(prop) !== -1;
        if (isInternalEvent) {
            if (autoSelectAllClubs) {
                $("#filter").find("input[type=checkbox]").prop("checked", true);
            }
        }
        else {
            // reset all checkboxes
            $("#filter").find("input[type=checkbox]").prop("checked", false);
            let clubName = $(document).find("#place").val();
            let clubId = getIdOfClub(clubName);

            if (clubId !== -1) {
                let showToClubCheckbox = $(document).find("[name=filterShowToClub" + clubId + "]");
                showToClubCheckbox.prop("checked", true);
            }
        }
    });
});

