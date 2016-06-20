/* global $ */

$(function() {
    'use strict';
    var businessSearch = $("#business");
    businessSearch.autocomplete({
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
});
