Ext.define('Slims.controller.SamplesSearch', {
    extend: 'Ext.app.Controller',

    views: [
        'sample.search.Grid',
        'sample.search.FiltersPanel'
    ],

    refs: [{
        ref: 'advancedFilterForm',
        selector: 'form[name=advancedFilter]'
    }],

    init: function() {
        this.control({
            'form[name=advancedFilter] button[name=search]': {
                click: this.advancedSearch
            }
        });
    },

    advancedSearch: function() {

    }
});
