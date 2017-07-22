import * as $ from "jquery"

function showErrorModal(message: string) {
    $("#errorModal").modal("show");
    $("#errorModal").find(".modal-body").html(message);
}
