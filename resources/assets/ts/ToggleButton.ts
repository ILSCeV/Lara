import * as $ from "jquery"
import {ToggleAction} from "./ToggleAction";

export class ToggleButton {

    private $button: JQuery;
    private isActive: () => boolean;

    private activeStyle: string;
    private inactiveStyle: string;

    private actions: Array<ToggleAction> = [];

    constructor(buttonId, isActive: () => boolean, activeStyle = "btn-primary", inactiveStyle = "") {
        this.$button = $(`#${buttonId}`);
        this.isActive = isActive;
        this.activeStyle = activeStyle;
        this.inactiveStyle = inactiveStyle;

        this.$button.toggleClass("btn-primary", this.isActive());
    }

    public setText(text: string) {
        this.$button.text(text);
        return this;
    }

    public addActions(actions: Array<ToggleAction>) {
        this.actions = [...this.actions, ...actions];
        this.setButtonListeners();
        return this;
    }

    private setButtonListeners() {
        // remove old event listeners
        this.$button.off("click.ToggleButton");
        this.$button.on("click.ToggleButton", () => this.setToggleStatus(!this.isActive()));
    }

    public setToggleStatus(active: boolean) {
        this.actions.forEach(action => action(!active));
        this.$button
            .toggleClass(this.activeStyle, active)
            .toggleClass(this.inactiveStyle, !active);
        return this;
    }

}

