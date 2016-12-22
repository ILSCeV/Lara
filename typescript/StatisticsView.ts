///<reference path="_references.ts"/>

function getRowName(a: string) {
    return $(a).children("td").eq(0).text();
}

function getRowShifts(a: string) {
    return $(a).children("td").eq(1).text();
}


//inspired by http://stackoverflow.com/questions/3160277/jquery-table-sort
function sortLeaderboards(sortIcon: JQuery) {
    let isAscending = sortIcon.hasClass('fa-sort-asc');
    let rowCatcher = sortIcon.parent().data('sort') === 'name' ? getRowName : getRowShifts;
    let $table = sortIcon.parents("table");
    let rows = $table
        .find("tbody")
        .find("tr")
        .toArray()
        .sort((a, b) => rowCatcher(a).localeCompare(rowCatcher(b), undefined, {'numeric': true}));
    if (!isAscending) {
        rows.reverse();
    }
    rows.forEach(row => $table.append($(row)));
    sortIcon.parents('table')
        .find('.fa-sort, .fa-sort-desc, .fa-sort-asc')
        .removeClass('fa-sort-asc')
        .removeClass('fa-sort-desc')
        .addClass('fa-sort');
    sortIcon.removeClass('fa-sort')
        .addClass(isAscending ? 'fa-sort-desc' : 'fa-sort-asc');
}
$(".fa-sort, .fa-sort-desc, .fa-sort-asc").click(function () {
    sortLeaderboards(this);
});

$('#leaderboardsTabs').find('thead').find('td').click(function() {
    sortLeaderboards($($(this).find('i').first()));
});
