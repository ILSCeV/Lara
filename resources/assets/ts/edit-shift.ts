var updatePublishEvent = function () {
    let $eventPublished = $('[name=evntIsPublished]');
    var evtPublished = $eventPublished.is(':checked');
    var publishIndicator = $('#eventPublishedIndicator');
    publishIndicator.removeClass('fa-check-square-o').removeClass('fa-square-o').removeClass('text-danger').removeClass('text-success');
    var publishBtn = $('#publishBtn');
    if (evtPublished) {
        publishIndicator.addClass('fa-check-square-o').addClass('text-success');
    } else {
        publishIndicator.addClass('fa-square-o').addClass('text-danger');
    }
    var isPrivateInput = $('[name=isPrivate]');
    if (isPrivateInput.is(':checked')) {
        publishBtn.removeClass('hidden');
    } else {
        publishBtn.addClass("hidden");
        $('[name=evntIsPublished]').prop("checked", false);
    }
};
$(window).on('load', updatePublishEvent);
$('#publishBtn').click(function () {
    if ($('[name=evntIsPublished]').is(':checked')) {
        $('[name=evntIsPublished]').prop('checked', !$('[name=evntIsPublished]').is(':checked'));
        updatePublishEvent();
    } else {
        bootbox.confirm({
            title: '<div class="alert alert-warning text-center"><span class="glyphicon glyphicon-warning-sign"></span> WARNING <span class="glyphicon glyphicon-warning-sign"></span></div>',
            size: 'small',
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

$('input[name=isPrivate]').change(updatePublishEvent);