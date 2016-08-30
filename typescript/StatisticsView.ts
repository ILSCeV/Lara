///<reference path="_references.ts"/>

function getRowName(a: string) {
    return $(a).children("td").eq(0).text();
}

function getRowShifts(a: string) {
    let text = $(a).children("td").eq(1).text();
    return parseInt(text);
}

//inspired by http://stackoverflow.com/questions/3160277/jquery-table-sort
$("[data-sort='name']").click(function () {
    this.ascending = !this.ascending;
    let $table = $(this).parents("table");
    let rows = $table
        .find("tbody")
        .find("tr")
        .toArray()
        .sort((a, b) => getRowName(a).localeCompare(getRowName(b)));
    if (this.ascending) {
        rows.reverse();
    }
    rows.forEach(function (row) {
        $table.append($(row));
    });
});

$("[data-sort='shifts']").click(function () {
    this.ascending = !this.ascending;
    let $table = $(this).parents("table");
    let rows = $table
        .find("tbody")
        .find("tr")
        .toArray()
        .sort((a, b) => getRowShifts(a) - getRowShifts(b));
    if (this.ascending) {
        rows.reverse();
    }
    rows.forEach(function (row) {
        $table.append($(row));
    });
});
