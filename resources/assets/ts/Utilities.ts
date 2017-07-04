enum Club {
    None,
    BcClub = 2,
    BcCafe,
    BdClub,
    BhClub,
    BiClub
}

function showErrorModal(message: string) {
    $("#errorModal").modal("show");
    $("#errorModal").find(".modal-body").html(message);
}
