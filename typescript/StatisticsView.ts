///<reference path="_references.ts"/>

function getRowName(a: string) {
    return $(a).children("td").eq(0).text();
}

function getRowShifts(a: string) {
    return $(a).children("td").eq(1).text();
}

//inspired by http://stackoverflow.com/questions/3160277/jquery-table-sort
$(".fa-sort, .fa-sort-desc, .fa-sort-asc").click(function () {
    let isAscending = $(this).hasClass('fa-sort-asc');
    let rowCatcher = $(this).parent().data('sort') === 'name' ? getRowName : getRowShifts;
    let $table = $(this).parents("table");
    let rows = $table
        .find("tbody")
        .find("tr")
        .toArray()
        .sort((a, b) => rowCatcher(a).localeCompare(rowCatcher(b), undefined, {'numeric': true}));
    if (!isAscending) {
        rows.reverse();
    }
    rows.forEach(row => $table.append($(row)));
    $(this).parents('table')
        .find('.fa-sort, .fa-sort-desc, .fa-sort-asc')
        .removeClass('fa-sort-asc')
        .removeClass('fa-sort-desc')
        .addClass('fa-sort');
    $(this).removeClass('fa-sort')
        .addClass(isAscending ? 'fa-sort-desc' : 'fa-sort-asc');
});
