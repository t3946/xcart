function showGraph(id, title, yAxisTitle, yAxisTitle2, dateFormat, data, data2) {

    Highcharts.setOptions({
        global: {
            useUTC: false
        }
    });

    $('#' + id).highcharts({

        chart: {
            zoomType: 'x',
            type: 'areaspline'

        },


        title: {
            text: title
        },
        subtitle: {
            text: document.ontouchstart === undefined ?
                'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'
        },
        credits: {
            enabled: false
        },


        xAxis: {
            type: 'datetime',
            labels: {
                formatter: function () {
                    return Highcharts.dateFormat(dateFormat, this.value);
                }
            }
        },
        yAxis: [{
            title: {
                text: yAxisTitle
            }
        }, {
            title: {
                text: yAxisTitle2
            },
            opposite:true
        }
        ],

        legend: {

        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: false
            }
        },

        series: [{
            type: 'areaspline',
            name: yAxisTitle,
            data: data

        },
        {
            type: 'column',
            data: data2,
            name: yAxisTitle2,
            yAxis: 1
        }
        ]
    });
}