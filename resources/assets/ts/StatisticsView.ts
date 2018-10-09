import * as $ from "jquery"

function getRowName(a: string | Element) {
    return $(a).children("td").eq(0).text();
}

function getRowShifts(a: string | Element) {
    return $(a).children("td").eq(1).text();
}


//inspired by http://stackoverflow.com/questions/3160277/jquery-table-sort
function sortTable($table: JQuery, byName: boolean, descending: boolean) {
    let rowCatcher = byName ? getRowName : getRowShifts;
    let rows = $table
        .find("tbody")
        .find("tr")
        .toArray()
        .sort((a, b) => rowCatcher(a).localeCompare(rowCatcher(b), undefined, {'numeric': true}));
    if (descending) {
        rows.reverse();
    }
    rows.forEach(row => $table.append($(row)));
}

function updateSortIconStyle($table: JQuery, byName: boolean, descending: boolean) {
    // remove specific sorting from all icons
    $table.find('.fa-sort, .fa-sort-up, .fa-sort-down')
        .removeClass('fa-sort-up')
        .removeClass('fa-sort-down')
        .addClass('fa-sort');
    // and add the current sorting order to the one that changed
    let icon = $table.find('.fa-sort, .fa-sort-up, .fa-sort-down')
        .filter(function () {
            let sortIdentifier = byName ? 'name' : 'shifts';
            return $(this).parent().data('sort') === sortIdentifier;
        });
    icon.removeClass('fa-sort')
        .addClass(descending ? 'fa-sort-down' : 'fa-sort-up');
}

function sortLeaderboards(sortIcon: JQuery) {
    let $tables = $('#memberStatisticsTabs').find('table');
    let wasAscending = sortIcon.hasClass('fa-sort-up');
    let isNameSort = sortIcon.parent().data('sort') === 'name';

    localStorage.setItem('preferredSortType', isNameSort ? 'name' : 'shifts');
    localStorage.setItem('preferredSortOrder', wasAscending ? 'descending' : 'ascending');

    $tables.each(function(){
        sortTable($(this), isNameSort, wasAscending);
        updateSortIconStyle($(this), isNameSort, wasAscending);
    });
}

$(".fa-sort, .fa-sort-up, .fa-sort-down").click(function () {
    sortLeaderboards(this);
});

$('#memberStatisticsTabs').find('thead').find('td').click(function() {
    sortLeaderboards($($(this).find('i').first()));
});

$(".statisticClubPicker").find("a").click(function() {
    let clubName = $(this).text().trim();
    localStorage.setItem('preferredStatistics', clubName);
});

$(".leaderboardsClubPicker").find("a").click(function() {
    let leaderBoardName = $(this).text().trim();
    localStorage.setItem('preferredLeaderboards', leaderBoardName);
});


$(() => {
    let preferredStatistics = localStorage.getItem('preferredStatistics');
    if (preferredStatistics) {
        $('.statisticClubPicker').find('a').filter(function() {
            return $(this).text().trim() === preferredStatistics;
        })
            .first()
            .click();
    }

    let preferredLeaderboards = localStorage.getItem('preferredLeaderboards');
    if (preferredLeaderboards) {
        $('.leaderboardsClubPicker').find('a').filter(function() {
            return $(this).text().trim() === preferredLeaderboards;
        })
            .first()
            .click();
    }

    let preferredSortType = localStorage.getItem('preferredSortType');
    let preferredSortOrder = localStorage.getItem('preferredSortOrder');

    let $tables = $('#memberStatisticsTabs').find('table');
    let isNameSort = preferredSortType === 'name';
    let isDescending = preferredSortOrder === 'descending';

    $tables.each(function() {
        sortTable($(this), isNameSort, isDescending);
        updateSortIconStyle($(this), isNameSort, isDescending);
    })

});
