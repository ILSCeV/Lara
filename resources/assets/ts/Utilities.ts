import * as $ from "jquery"

function showErrorModal(message: string) {
    $("#errorModal").modal("show");
    $("#errorModal").find(".modal-body").html(message);
}

export function convertToSafeFormat(str: string) {
    // Replace spaces with a dash,
    // remove special characters
    // and then convert to lower case.
    // Example: "Section Name 123 & 456" => "section-name-123-456"
    return str.replace(/\W+/g, '-').toLowerCase();
}

export const safeSetLocalStorage = (key: string, prop: any) => {
    if (typeof(Storage) !== "undefined") {
        localStorage.setItem(key, prop);
    }
};

export const safeGetLocalStorage = (key: string) => {
    if (typeof(Storage) !== "undefined") {
        return localStorage.getItem(key);
    }
    return undefined;
};
