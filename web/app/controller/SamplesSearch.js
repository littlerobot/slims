Ext.define('Slims.controller.SamplesSearch', {
    extend: 'Ext.app.Controller',

    views: [
        'sample.search.Grid',
        'sample.search.FiltersForm'
    ],

    refs: [{
        ref: 'form',
        selector: 'samplessearchfilter'
    }, {
        ref: 'grid',
        selector: 'samplesearch'
    }],

    init: function() {
        this.control({
            'samplessearchfilter': {
                search: this.doSearch
            }
        });
    },

    doSearch: function(values) {
        this.getGrid().setLoading(true);

        Ext.Ajax.request({
            url: Slims.Url.getRoute('searchsamples'),
            method: 'GET',
            params: values,
            scope: this,
            success: function(xhr) {
                this.getGrid().setLoading(false);
                var response = Ext.decode(xhr.responseText);
                this.getGrid().showSearchResults(response.results || []);
            },
            failure: function() {
                this.getGrid().setLoading(false);
            }
        });
    }
});
