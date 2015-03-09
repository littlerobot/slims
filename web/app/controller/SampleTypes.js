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
    }, {
        ref: 'sampleTypesGrid',
        selector: 'sampletypesgrid'
    }],

    init: function() {
        this.control({
            'sampletypesgrid button[name=addType]': {
                click: this.openAddSampleTypeWindow
            },
            'sampletypesgrid': {
                editrecord: this.openEditSampleTypeWindow
            },
            'sampletypewindow': {
                save: this.saveSampleType
            },
            'sampletypesgrid button[name=reloadGrid]': {
                click: this.reloadGrid
            }
        });
    },

    openAddSampleTypeWindow: function() {
        Ext.create('Slims.view.sample.types.Window').show();
    },

    openEditSampleTypeWindow: function(sampleType) {
        var w = Ext.create('Slims.view.sample.types.Window', {
            templateId: sampleType.get('sample_type_template_id')
        });
        w.show();
        w.setData(sampleType.data);
    },

    saveSampleType: function(sampleType, wnd) {
        this.getTab().setLoading('Saving. Please, wait...');

        var url;
        if (sampleType.getId()) {
            url = Slims.Url.getRoute('setsampletype', [sampleType.getId()]);
        } else {
            url = Slims.Url.getRoute('createsampletype');
        }

        if (wnd) {
            wnd.setLoading(true);
        }

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
        this.getSampleTypesGrid().loadData();
    }
});
