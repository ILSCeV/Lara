let shiftContainer = document.getElementById("shiftContainer");
if (shiftContainer) {
    Sortable.create(shiftContainer, {
        handle: ".fa-bars"
    });
}
