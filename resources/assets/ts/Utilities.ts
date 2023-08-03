
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


export const isMonthView: boolean = $('#month-view-marker').length > 0;
export const isWeekView: boolean = $('#week-view-marker').length > 0;
export const isDayView: boolean = $('#day-view-marker').length > 0;