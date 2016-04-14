/* global $ document Spinner */
$(function() {
    'use strict';
    var target = $('#spinner-loader')[0];
    var spinner = new Spinner().spin(target);

    var href = $(document).find('#js-tabs .active a').attr("href");

    $('.tab-content .active').load(href, function(){
        spinner.stop();
        $('#js-tabs .active a').tab('show');
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
        });
        return false;
    });
});
