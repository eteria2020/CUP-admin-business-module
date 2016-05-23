/* global $ document Spinner translate window */

$(function() {
    'use strict';

    var orderSpecs = [[0, 'desc']];

    var languageSpecs = {
        "sEmptyTable": translate("sEmptyTable"),
        "sInfo": translate("sInfo"),
        "sInfoEmpty": translate("sInfoEmpty"),
        "sInfoFiltered": translate("sInfoFiltered"),
        "sInfoPostFix": "",
        "sInfoThousands": ",",
        "sLengthMenu": translate("sLengthMenu"),
        "sLoadingRecords": translate("sLoadingRecords"),
        "sProcessing": translate("sProcessing"),
        "sSearch": translate("sSearch"),
        "sZeroRecords": translate("sZeroRecords"),
        "oPaginate": {
            "sFirst": translate("oPaginateFirst"),
            "sPrevious": translate("oPaginatePrevious"),
            "sNext": translate("oPaginateNext"),
            "sLast": translate("oPaginateLast")
        },
        "oAria": {
            "sSortAscending": translate("sSortAscending"),
            "sSortDescending": translate("sSortDescending")
        }
    };

    var columnDefs = [
        { targets: [3], sortable: false}
    ];

    function initializeEmployeesDatatables() {
        $('#js-business-employees-table').dataTable({
            order: orderSpecs,
            language: languageSpecs,
            columnDefs: columnDefs
        });
        $('#js-pending-business-employees-table').dataTable({
            order: orderSpecs,
            language: languageSpecs,
            columnDefs: columnDefs
        });
    }

    function initializePackagesTab() {
        var table = $('#js-time-packages-table');
        table.dataTable({
            order: [[0, 'asc']],
            language: languageSpecs
        });
        $('#submit-btn').click(function () {
            var data = $("input:checked", table.fnGetNodes());
            $.ajax({
                url: window.location.href.split('?')[0] + "/buyable-packages",
                data: data,
                type: "POST",
                complete: function() {
                    window.location.href = window.location.href.split('?')[0] + '?tab=time-packages';
                }
            });
        });
    }

    var target = $('#spinner-loader')[0];
    var spinner = new Spinner().spin(target);

    var href = $(document).find('#js-tabs .active a').attr("href");

    function initOpenTab() {
        var openTab = $('#js-tabs .active');
        if (openTab.is('#menu-employee')) {
            initializeEmployeesDatatables();
        } else if (openTab.is('#menu-time-packages')) {
            initializePackagesTab();
        }
    }

    $('.tab-content .active').load(href, function(){
        spinner.stop();
        $('#js-tabs .active a').tab('show');
        initOpenTab();
    });

    $('#js-tabs li a').click(function() {
        spinner.spin(target);
        var context = $(this);
        var loadurl = context.attr('href');
        var targ = context.attr('data-target');

        $.get(loadurl, function(data) {
            $(targ).html(data);
            spinner.stop();
            context.tab('show');
            initOpenTab();
        });
        return false;
    });
});


