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

$("#button-create-submit").add("#button-edit-submit").click(function () {
    let errors = new Array<string>();

    let beginDate = new Date($("[name='beginDate']").prop("value") + " " + $("[name='beginTime']").prop("value"));
    let endDate = new Date($("[name='endDate']").prop("value") + " " + $("[name='endTime']").prop("value"));
    if (beginDate.getTime() > endDate.getTime()) {
        errors.push("Die Startzeit liegt vor der Endzeit!");
    }
    if ($("#filter-checkboxes").find("input[type=checkbox]:checked").length === 0) {
        errors.push("Den Filter vergessen! Bitte setze mindestens eine Sektion, der diese Veranstaltung/Aufgabe gezeigt werden soll.");
    }

    if (errors.length > 0) {
        bootbox.alert(errors.map(err => "<p>" + err + "</p>").join("\n"))
        return false;
    }
});
$(() => {
    // if set, internal events will trigger selection of all clubs
    // if user sets the club manually, we want to keep his selection
    let autoSelectAllClubs = true;
    let allClubCheckBoxes = $("#filter").find("input[type=checkbox]");
    allClubCheckBoxes.click(() => {
        autoSelectAllClubs = false;
    });

    // important to use function() (anonymous function) here an not an arrow function
    // using an arrow function will change the "this" inside
    $("[name='evnt_type']").click(function () {
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

