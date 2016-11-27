$(function () {
    var data = $.getJSON('/personal/statistics/chartData', function(data) {
        var personalData = data['personal'];
        var categories = Object.keys(personalData).sort();
        var personalSeries = categories.map(function(category) {
            return personalData[category];
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
                    var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    var month = new Date(date);
                    return months[month.getMonth()] + ' ' + month.getFullYear();
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
                data: personalSeries
            }],
            credits: false
        });
    });
});