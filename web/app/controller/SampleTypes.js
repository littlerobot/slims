Ext.define('Slims.controller.SampleTypes', {
    extend: 'Ext.app.Controller',

    views: [
        'sample.types.Panel',
        'sample.types.Grid'
    ],

    stores: ['sample.Types'],
    models: ['sample.Type'],

    refs: [{
        ref: 'tab',
        selector: 'sampletypespage'
    }],

    init: function() {
        this.control({
            'sampletypesgrid button[name=addType]': {
                click: this.openAddSampleTypeWindow
            },
            'sampletypewindow': {
                save: this.saveSampleType
            }
        });
    },

    openAddSampleTypeWindow: function() {
        Ext.create('Slims.view.sample.types.Window').show();
    },

    saveSampleType: function(sampleType, wnd) {
        this.getTab().setLoading('Saving. Please, wait...');

        var url;
        if (sampleType.getId()) {
            url = Ext.String.format(Slims.Url.getRoute('setsampletype'), sampleType.getId());
        } else {
            url = Slims.Url.getRoute('createsampletype');
        }

        if (wnd) {
            wnd.setLoading(true);
        }
        console.log(sampleType.data);
        Ext.Ajax.request({
            url: url,
            method: 'POST',
            jsonData: sampleType.data,
            scope: this,
            success: function() {
                this.getTab().setLoading(false);
                if (wnd) {
                    wnd.setLoading(false);
                    wnd.close();
                }

                this.reloadGrid();
            },
            failure: function() {
                this.getTab().setLoading(false);
                if (wnd) {
                    wnd.setLoading(false);
                }

                this.reloadGrid();
            }
        });
    },

    reloadGrid: function() {

    }
});
