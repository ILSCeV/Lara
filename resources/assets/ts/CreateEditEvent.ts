import * as $ from "jquery"
import { translate } from "./Translate"
import { getIdOfClub } from "./Utilities"
import * as bootbox from "bootbox"

// values of events that should trigger the selection of all clubs
let internalEventValues = [
    "1", // Info
    "4", // Internal event
    "5", // private party
    "6", // cleaning
    "9"  // other
];
$(() => {
    $("#button-create-submit").add("#button-edit-submit").click(function () {

        let beginDate = new Date($("[name='beginDate']").prop("value") + " " + $("[name='beginTime']").prop("value"));
        let endDate = new Date($("[name='endDate']").prop("value") + " " + $("[name='endTime']").prop("value"));

        // contains the keys to translations to be shown if the condition is fulfilled
        let errorConditions: {[key: string]: boolean} = {
            'endBeforeStart': beginDate.getTime() > endDate.getTime(),
            'forgotFilter': $("#filter-checkboxes").find("input[type=checkbox]:checked").length === 0,
            'forgotPreparation': $('[name="preparationTime"]').val() === "",
            'forgotStartTime': $('[name="beginTime"]').val() === "",
            'forgotEndTime': $('[name="endTime"]').val() === ""
        };

        let errors = Object.keys(errorConditions)
            .filter(key => errorConditions[key])
            .map(key => translate(key));

        if (errors.length > 0) {
            bootbox.alert(errors.map(err => "<p>" + err + "</p>").join("\n"));
            return false;
        }
    });
    // if set, internal events will trigger selection of all clubs
    // if user sets the club manually, we want to keep his selection
    let hasUserChangedClubs = false;
    let allClubCheckBoxes = $("#filter").find("input[type=checkbox]");
    allClubCheckBoxes.click(() => {
        hasUserChangedClubs = true;
    });

    // important to use function() (anonymous function) here an not an arrow function
    // using an arrow function will change the "this" inside
    $("[name='evnt_type']").click(function () {
        let prop = $(this).val();
        let isInternalEvent = internalEventValues.indexOf(prop) !== -1;
        if (!hasUserChangedClubs) {
            let $filterCheckboxes = $("#filter").find("input[type=checkbox]");
            if (isInternalEvent) {
                $filterCheckboxes.prop("checked", true);
            }
        }
        else {
            // reset all checkboxes
            $("#filter").find("input[type=checkbox]").prop("checked", false);
            let clubName = $(document).find("#section").val();
            let clubId = getIdOfClub(clubName);

                if (clubName) {
                    let showToClubCheckbox = $(document).find("[name='filter[" + clubName +"]']");
                    showToClubCheckbox.prop("checked", true);
                }
        }
        }
    });
});

