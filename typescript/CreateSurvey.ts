///<reference path="../typings/index.d.ts"/>
///<reference path="Utilities.ts"/>

$("#button-create-survey").click(() => {
    let errors = new Array<string>();
    if ($("#description").val().length  > 1500) {
        errors.push("Der Beschreibungstext ist zu lange! Der Text sollte weniger als 1500 Zeichen enthalten.");
    }
    if (errors.length > 0) {
        bootbox.alert(errors.map(err => "<p>" + err + "</p>").join("\n"));
        return false;
    }
});
