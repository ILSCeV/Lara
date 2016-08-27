///<reference path="_references.ts"/>

$("#button-create-survey").click(() => {
    let errors = new Array<string>();
    if ($("description").text().length  > 1500) {
        errors.push("Der Beschreibungstext ist zu lange! Der Text sollte weniger als 1500 Zeichen enthalten.,w");
    }
    if (errors.length > 0) {
        showErrorModal(errors.map(err => "<p>" + err + "</p>").join("\n"));
        return false;
    }
});
