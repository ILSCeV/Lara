var updatePublishEvent = function () {
    var evtPublished = $('[name=evntIsPublished]').is(':checked');
    var publishBtn = $('#publishBtn');
    if (evtPublished) {
        publishBtn.removeClass("btn-danger").addClass("btn-success");
    } else {
        publishBtn.removeClass("btn-success").addClass("btn-danger");
    }
};
$(window).load(updatePublishEvent);
$('#publishBtn').click(function () {
    if ($('[name=evntIsPublished]').is(':checked')) {
        $('[name=evntIsPublished]').prop('checked', !$('[name=evntIsPublished]').is(':checked'));
        updatePublishEvent();
    } else {
        bootbox.confirm({
            title: '<div class="alert alert-warning text-center"><span class="glyphicon glyphicon-warning-sign"></span> WARNING <span class="glyphicon glyphicon-warning-sign"></span></div>',
            size: 'medium',
            message: '<p>' + translate('publishEventWarning') + '</p>',
            callback: function (result) {
                if (!result) {
                    return;
                }

                $('[name=evntIsPublished]').prop('checked', !$('[name=evntIsPublished]').is(':checked'));
                updatePublishEvent();
            }
        });
    }
});
