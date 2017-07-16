import * as $ from "jquery"
import { translate } from "./Translate"
import * as bootbox from "bootbox"


/**
 * Created by fabian on 28.04.17.
 */
$(() => {
    $('#pubishEventLnkBtn').click(() => {
        bootbox.confirm(translate('confirmPublishingEvent'), (result) => {
            if (result) {
                let targetLocation = $('#pubishEventLnkBtn').data('href');
                window.location.href = targetLocation;
            }
        });
    });
    $('#unPublishEventLnkBtn').click(() => {
        bootbox.confirm(translate('confirmUnpublishingEvent'), (result) => {
            if (result) {
                let targetLocation = $('#unPublishEventLnkBtn').data('href');
                window.location.href = targetLocation;
            }
        });
    });


});
