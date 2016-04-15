/* global $ document Spinner translate */
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

    function initializeDatatables() {
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

    var target = $('#spinner-loader')[0];
    var spinner = new Spinner().spin(target);

    var href = $(document).find('#js-tabs .active a').attr("href");

    $('.tab-content .active').load(href, function(){
        spinner.stop();
        $('#js-tabs .active a').tab('show');
        if ($('#js-tabs .active').is('#menu-employee')) {
            initializeDatatables();
        }
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
            if (context.parent().is('#menu-employee')) {
                initializeDatatables();
            }
        });
        return false;
    });
});
