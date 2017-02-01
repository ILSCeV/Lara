////////////////////////////////
//      PERMANENT LINKS       //
////////////////////////////////

$('#permalink').click(function () {
    var url = location.protocol + '//' + location.host + location.pathname + '?';
    //Check section filtering
    $('.section-filter-selector').each(function () {
        url += $(this).text() + '=' + ($(this).hasClass('btn-primary') ? '1&' : '0&');
    });

    //Check time
    url += 't=' + ($('#toggle-shift-time').hasClass('btn-primary') ? '1&' : '0&');

    //Set week start
    if ($('#toggle-week-start').hasClass('btn-primary')) {
        url += 'w=MoSu&';
    }
    else if ($('#toggle-week-start').hasClass('btn-success')) {
        url += 'w=WeTu&';
    }

    //only free shifts
    url += 'f=' + ($('#toggle-taken-shifts').hasClass('btn-primary') ? '1' : '0');

    history.pushState(null, null, url); //Avoids reloading the page, otherwise use document.location.href=url;
});

$('#permalink').show();

$.urlParam = function (name) {
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(decodeURI(window.location.href));
    if (results == null) {
        return null;
    }
    else {
        return results[1] || 0;
    }
}

$(document).ready(function () {
    // Handler for .ready() called.
    $('.section-filter-selector').each(function () {
        if($.urlParam($(this) == null)){
            return;
        }
        if ($.urlParam($(this).text().trim()) == 1) {
            showSection('filter-'+($(this).text().trim()));
        }
        else {
            $(this).removeClass('btn-primary');
        }
    });
    if ($.urlParam('t') == 1) {
        showTimes();
    }
    else if ($.urlParam('t') == 0){
        hideTimes();
    }

    if ($.urlParam('f') == 1) {
        hideTakenShifts();
    }
    else if ($.urlParam('f') == 0) {
        showTakenShifts();
    }

    switch ($.urlParam('w')) {
        case 'MoSu':
            setWeekMoSu();
            break;
        case 'WeTu':
            setWeekWeTu();
            break;
    }
});