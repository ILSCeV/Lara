$(function () {
    var data = $.getJSON('/personal/statistics/chartData', function(data) {
        var categories = Object.keys(data).sort();
        var series = categories.map(function(category) {
            return data[category];
        });
        $('#activityGraph').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'Your monthly activity'
            },
            xAxis: {
                categories: categories.map(function(date) {
                    var month = new Date(date);
                    return month.getMonth() + month.getFullYear();
                })
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Amount of shifts'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.f}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: 'Your shifts',
                data: series
            }]
        });
    });
});