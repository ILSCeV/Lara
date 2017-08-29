import * as Sortable from "../../../node_modules/sortablejs/Sortable.js"

let shiftContainer = document.getElementById("shiftContainer");
if (shiftContainer) {
    Sortable.create(shiftContainer, {
        handle: ".fa-bars"
    });
}
