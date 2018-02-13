import * as Sortable from "../../../node_modules/sortablejs/Sortable.js"

let shiftContainer = document.getElementById("shiftContainer");
if (shiftContainer) {
    (Sortable as any).create(shiftContainer, {
        handle: ".fa-bars"
    });
}
