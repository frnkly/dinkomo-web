/**
 * Copyright Di Nkomo(TM) 2016, all rights reserved
 *
 */
(function ( $ ) {

    /**
     *
     */
    $.fn.languageLookup = function( setup ) {

        var $resLookup = this.resourceLookup($.extend(setup, {
            valueField: 'code',
            labelField: 'name',
            searchField: ['code', 'name', 'transliteration', 'altNames'],
            maxItems: 1,
            apiEndpoint: App.root + 'api/0.1/language/search/:query',
            render:
            {
                item: function(item, escape) {
                    return  '<div>' +
                                '<span class="name">' + escape(item.name) + '</span>' +
                            '</div>';
                },

                option: function(item, escape)
                {
                    // Language title
                    var title = item.name;
                    if (item.parentName && item.parentName.length)
                        title += ' (a sub-language of '+ item.parentName +')';

                    // Add a short desciption
                    var hint = '';
                    if (item.altNames && item.altNames.length)
                        hint = '<span class="hint"> &mdash; Also known as '+ item.altNames + '</span>';

                    // Return formatted HTML
                    return  '<div>' +
                                '<span class="label">' + escape(title) + '</span>' + hint +
                            '</div>';
                }
            }
        }));

        return this;
    };

}( jQuery ));
