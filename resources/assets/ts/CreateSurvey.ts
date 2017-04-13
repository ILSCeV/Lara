$("#button-create-survey").click(() => {
    let errors = new Array<string>();

    if ($("#description").val().length  > 1500) {
        errors.push(translate("descriptionTooLong"));
    }
    if (errors.length > 0) {
        bootbox.alert(errors.map(err => "<p>" + err + "</p>").join("\n"));
        return false;
    }
});


