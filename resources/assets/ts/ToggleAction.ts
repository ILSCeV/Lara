import * as $ from "jquery"
import {safeSetLocalStorage} from "./Utilities";

export interface ToggleAction {
    (isActive: boolean): void;
}

export const makeLocalStorageAction = (key: string, active, inactive) => (
    (isActive: boolean) => safeSetLocalStorage(key, isActive ? inactive : active)
);
export const makeClassToggleAction = (selector: string | JQuery, style: string, isActiveOnToggle) => (
    (isActive: boolean) => $(selector).toggleClass(style, isActive ? !isActiveOnToggle : isActiveOnToggle)
);
