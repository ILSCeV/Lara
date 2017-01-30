var chart;
var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
var activeMonths;

$(function() {
    $.getJSON('/personal/statistics/chartData', function(data) {
        var personalData = data['personal'];
        var categories = Object.keys(personalData).sort();
        activeMonths = categories;
        var personalSeries = categories.map(function(category) {
            return personalData[category];
        });

        chart = Highcharts.chart('activityGraph', {
            title: {
                text: 'Your monthly activity'
            },
            xAxis: {
                categories: categories.map(function(date) {
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
            credits: false
        });

        chart.addSeries({
            name: 'Your activity',
            data: personalSeries,
            type: 'column',
            zones: [
                {
                    value: 1.1,
                    color: '#f72024'
                },
                {
                    value: 4.5,
                    color: '#f2f714'
                },
                {
                    color: '#26ec33'
                },
            ],
        });

        $.getJSON('/personal/statistics/chartData/average', function(data) {
            var personalData = data['average'];
            var averageSeries = activeMonths.map(function(category) {
                return personalData[category];
            });
            chart.addSeries({
                name: 'Average',
                data: averageSeries
            })
        });
    });
});

