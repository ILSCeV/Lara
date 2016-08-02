///<reference path="../typings/index.d.ts"/>

enum Club {
    NONE,
    BC_CLUB = 2,
    BC_CAFE,
    BD_CLUB,
    BH_CLUB,
    BI_CLUB
}

function getIdOfClub (club: string): Club {
    switch (club) {
        case "bc-Club":
            return Club.BC_CLUB;
        case "bc-Café":
            return Club.BC_CAFE;
    }
    return Club.NONE;
}

$(() => {
    // if set, internal events will trigger selection of all clubs
    // if user sets the club manually, we want to keep his selection
    let autoSelectAllClubs = true;
    let allClubCheckBoxes = $("#filter").find("input[type=checkbox]");
    allClubCheckBoxes.click(() => { autoSelectAllClubs = false; });

    // values of events that should trigger the selection of all clubs
    let internalEventValues = [
        "1", // Info
        "4", // Internal event
        "5", // private party
        "6", // cleaning
        "9"  // other
    ];
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

