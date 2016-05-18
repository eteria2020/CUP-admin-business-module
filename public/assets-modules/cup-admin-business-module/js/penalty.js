/* global $ currentGlobalLocale*/

$(function() {
    'use strict';
    $("#business").select2({
        language: currentGlobalLocale,
        theme: "bootstrap",
        ajax: {
            url: "/business/typeahead-json",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    query: params.term,
                    page: params.page
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;

                //select2 want the result to have an id and a text field
                var mappedResult = $.map(data.businesses, function (obj) {
                    obj.id = obj.code;
                    obj.text = obj.name;
                    return obj;
                });

                return {
                    results: mappedResult
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) { return markup; },
        minimumInputLength: 1,
        templateResult: function (business) {
            if (business.loading) {
                return business.text;
            }
            return "<div class='select2-result clearfix'>" + business.text + "</div><small>" + business.id + "</small>";
        },
        templateSelection: function (business) {
            return business.text;
        }
    });
});
