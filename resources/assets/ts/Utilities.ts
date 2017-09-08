import * as $ from "jquery"

function showErrorModal(message: string) {
    $("#errorModal").modal("show");
    $("#errorModal").find(".modal-body").html(message);
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

// conversion of html entities to text (e.g. "&" as "&amp;")
// ref: https://stackoverflow.com/questions/1147359/how-to-decode-html-entities-using-jquery
export const decodeEntities = (encodedString) => {
    var textArea = document.createElement('textarea');
    textArea.innerHTML = encodedString;
    return textArea.value;
}
