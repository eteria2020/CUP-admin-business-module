/* global $ */

$(function() {
    'use strict';

    var business = $('#business-name');
    var filters = {
        business: business,
        dateFrom: $('#js-date-from'),
        dateTo: $('#js-date-to')
    };
    var ctx = $('#chart');

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
        $.post('stats/data', {
            'filters': {
                business: filters.business.val(),
                from: filters.dateFrom.val(),
                to: filters.dateTo.val()
            }},
            function(data) {
                chartData.data.labels = data.labels;
                chartData.data.datasets[0].data = data.data;

                initChart();
            }
        );
    }

    business.autocomplete({
        lookup: function (query, done) {
            $.ajax({
                url: '/business/typeahead-json',
                dataType: 'json',
                data: {
                    query: query
                },
                success: function(data){
                    var suggestions = [];
                    $.each(data.businesses, function(i, item){
                        suggestions.push({"value": item.name, "data": item.code});
                    });

                    done({suggestions: suggestions});
                }
            });
        }
    });

    initChart();
    getChartData();

    $("#js-search").click(function() {
        getChartData();
    });

    $("#js-clear").click(function() {
        filters.business.val("");
        filters.dateFrom.val("");
        filters.dateTo.val("");
    });

    $(".date-picker").datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        weekStart: 1
    });
});
