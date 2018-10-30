/* global $, filters:true, translate:true, getSessionVars:true */
$(function () {
    'use strict';
    // DataTable
    var table = $("#js-trips-table");
    var searchValue = $("#js-value");

    // Define DataTables Filters
    var dataTableVars = {
        searchValue: searchValue,
        column: $("#js-column"),
        iSortCol_0: 7,
        sSortDir_0: "desc",
        iDisplayLength: 100,
        from: $("#js-date-from"),
        columnFromDate: "e.timestampBeginning",
        to: $("#js-date-to"),
        columnToDate: "e.timestampEnd"
    };

    var filterWithNull = false;
    var filterWithTime = false;

    dataTableVars.searchValue.val("");
    dataTableVars.column.val("select");

    if (typeof getSessionVars !== "undefined") {
        getSessionVars(filters, dataTableVars);
    }
    
    if ($("#js-date-from").val().length > 10) {
        $("#js-date-from").val($("#js-date-from").val().substring(0, 10));
    }

    table.dataTable({
        "processing": true,
        "serverSide": true,
        "bStateSave": false,
        "bFilter": false,
        "sAjaxSource": "/trips/datatable",
        "createdRow": function (row, data, dataIndex, cells) {
            switch (data.cu.type) {
                case 1:
                    $('td', row).css('background', '#ffe6e6');
                    break;
                case 2:
                    $('td', row).css('background', '#f2ffcc');
                    break;
            }
        },
        "fnServerData": function (sSource, aoData, fnCallback, oSettings) {
            oSettings.jqXHR = $.ajax({
                "dataType": "json",
                "type": "POST",
                "url": sSource,
                "data": aoData,
                "success": fnCallback,
                "statusCode": {
                    200: function (data, textStatus, jqXHR) {
                        loginRedirect(data, textStatus, jqXHR);
                    }
                }
            });
        },
        "fnServerParams": function (aoData) {
            aoData.push({"name": "fromDate", "value": $(dataTableVars.from).val().trim()});
            aoData.push({"name": "toDate", "value": $(dataTableVars.to).val().trim()});
            if (filterWithNull) {
                aoData.push({"name": "column", "value": ""});
                aoData.push({"name": "searchValue", "value": ""});
                aoData.push({"name": "columnNull", "value": "e.timestampEnd"});
            } else {
                if (filterWithTime) {
                    aoData.push({"name": "columnNull", "value": "e.timestampEnd"});
                    var d = new Date();
                    var month = d.getMonth() + 1;
                    var day = d.getDate();
                    var output = d.getFullYear() + '-' + (month < 10 ? '0' : '') + month + '-' + (day < 10 ? '0' : '') + day + " ";
                    if ($(dataTableVars.from).val().trim() == "") {
                        var newHours = d.getHours() - ($('#js-value').val());
                        var from = output + newHours + ":" + d.getMinutes() + ":" + d.getSeconds();
                        aoData.push({"name": "fromDate", "value": from.trim()});
                    } else {
                        aoData.push({"name": "fromDate", "value": ""});
                    }
                    aoData.push({"name": "toDate", "value": output.trim()});
                } else {
                    aoData.push({"name": "column", "value": $(dataTableVars.column).val()});
                    aoData.push({"name": "searchValue", "value": dataTableVars.searchValue.val().trim()});
                }
            }
            aoData.push({"name": "columnFromDate", "value": dataTableVars.columnFromDate});
            aoData.push({"name": "columnToDate", "value": dataTableVars.columnToDate});
        },
        "order": [[dataTableVars.iSortCol_0, dataTableVars.sSortDir_0]],
        "columns": [
            {data: "e.id"},
            {data: "cu.email"},
            {data: "cu.fullname"},
            {data: "c.plate"},
            {data: "cu.mobile"},
            {data: "cc.code"},
            {data: "c.keyStatus"},
            {data: "e.timestampBeginning"},
            {data: "e.timestampEnd"},
            {data: "duration"},
            {data: "e.parkSeconds"},
            {data: "c.parking"},
            {data: "e.payable"},
            {data: "payed"},
            {data: "e.totalCost"},
            {data: "f.name"},
            {data: "e.isBusiness"}
        ],
        "columnDefs": [
            {
                targets: 0, //id
                "render": function (data) {
                    return renderTripLink(data);
                }
            },
            {
                targets: 1,
                visible: false
            },
            {
                targets: 2, //Cognome/nome
                "render": function (data, type, row) {
                    return '<a href="/customers/edit/' + row.cu.id + '" title="' +
                            translate("showProfile") + " " + row.cu.fullname + ' ">' + data + '</a>';
                }
            },
            {
                targets: 4, //Tel.mobile
                sortable: false
            },
            {
                targets: 5, //RFID
                sortable: false
            },
            {
                targets: 9, //Durata
                sortable: false
            },
            {
                targets: 10, //Sosta
                sortable: false,
                "render": function (data) {
                    return renderParkingMinutes(data);
                }
            },
            {
                targets: 11, //In Sosta
                sortable: false
            },
            {
                targets: 13, //Pagata
                sortable: false
            },
            {
                targets: 14, //Costo
                sortable: false,
                "render": function (data) {
                    return renderCostButton(data);
                }
            }
        ],
        "lengthMenu": [
            [100, 200, 300],
            [100, 200, 300]
        ],
        "pageLength": dataTableVars.iDisplayLength,
        "pagingType": "bootstrap_full_number",
        "language": {
            "sEmptyTable": translate("sTripEmptyTable"),
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
        }
    });

    $("#js-search").click(function () {
        table.fnFilter();
    });

    $("#js-clear").click(function () {
        dataTableVars.searchValue.val("");
        dataTableVars.from.val("");
        dataTableVars.to.val("");
        dataTableVars.column.val("select");
        dataTableVars.searchValue.prop("disabled", false);
        filterWithNull = false;
        filterWithTime = false;
        dataTableVars.searchValue.show();
    });

    $(".date-picker").datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        weekStart: 1
    });
    /*
     $('.datetime-picker').datetimepicker({
     //format: "YYYY-MM-DD HH:mm:ss",
     format: "YYYY-MM-DD HH:00:00",
     //format: "YYYY-MM-DD 00:00:00",
     });*/

    $(dataTableVars.column).change(function () {
        var value = $(this).val();

        dataTableVars.searchValue.show();
        dataTableVars.searchValue.val("");

        disableBusinessSearch();
        if (value === "e.timestampEnd") {
            filterWithNull = true;
            dataTableVars.searchValue.prop("disabled", true);
        } else if (value === 'b.name') {
            enableBusinessSearch();
        } else {
            if (value === "e.timestampBeginning") {
                filterWithTime = true;
                $('#js-date-from').val("");
                $('#js-date-to').val("");
            } else {
                filterWithNull = false;
                filterWithTime = false;
                dataTableVars.searchValue.prop("disabled", false);
            }
        }
    });

    function renderCostButton(data)
    {
        var amount = data.amount;
        if (amount !== "FREE") {
            return amount !== "" ?
                    '<a href="/trips/details/' + data.id + '?tab=cost">' +
                    renderAmount(parseInt(amount)) + '</a>' : "";
        }
        return amount;
    }

    function renderAmount(amount)
    {
        return (Math.floor(amount / 100)) + "," + toStringKeepZero(amount % 100) + " \u20ac";
    }

    function renderTripLink(data) {
        var tripId = data;
        if (data.indexOf("<br>") !== -1) {
            tripId = data.substring(0, data.indexOf("<br>"));
        }
        return '<a href="/trips/details/' + tripId + '">' + data + '</a>';
    }

    function toStringKeepZero(value)
    {
        return ((value < 10) ? "0" : "") + value;
    }

    function enableBusinessSearch() {
        searchValue.autocomplete('enable');
    }

    function disableBusinessSearch() {
        searchValue.autocomplete('disable');
    }

    function renderParkingMinutes(data) {
        data = data.replace(" sec", "").trim();
        if (data === "") {
            data = "0";
        }

        data = Math.round(parseInt(data) / 60);
        data = data.toString() + " min";
        return data;
    }

    searchValue.autocomplete({
        lookup: function (query, done) {
            $.ajax({
                url: '/business/typeahead-json',
                dataType: 'json',
                data: {
                    query: query
                },
                success: function (data) {
                    var suggestions = [];
                    $.each(data.businesses, function (i, item) {
                        suggestions.push({"value": item.name, "data": item.code});
                    });

                    done({suggestions: suggestions});
                }
            });
        }
    });
    disableBusinessSearch();
}); 
