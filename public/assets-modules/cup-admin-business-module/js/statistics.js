/* global $ */

$(function() {
    'use strict';

    var ctx = document.getElementById("chart");

    var chartData = {
        data: {
            labels: [],
            datasets: [
                {
                    label: "",
                    backgroundColor: "rgba(255,99,132,0.2)",
                    borderColor: "rgba(255,99,132,1)",
                    borderWidth: 1,
                    hoverBackgroundColor: "rgba(255,99,132,0.4)",
                    hoverBorderColor: "rgba(255,99,132,1)",
                    data: []
                }

            ]
        },
        options: {
            responsive: true,
            mantainAspectRatio: false,
            legend: {
                display: false
            }
        }
    };

    function initChart() {
        new Chart.Bar(ctx, chartData);
    }

    function getChartData () {
        $.post('stats/data', {},
            function(data) {
                chartData.data.labels = data.labels;
                chartData.data.datasets[0].data = data.data;

                initChart();
            }
        );
    }

    initChart();
    getChartData();
});
