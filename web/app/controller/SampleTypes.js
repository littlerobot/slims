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

        this.createStores();
    },

    createStores: function() {
        var sampleTypesStore = Ext.create('Slims.store.sample.Types', {
            storeId: 'sampleTypes'
        });

        sampleTypesStore.load();
    },

    openAddSampleTypeWindow: function() {
        Ext.create('Slims.view.sample.types.Window').show();
    },

    openEditSampleTypeWindow: function(sampleType) {
        var sampleTypesWindow = Ext.create('Slims.view.sample.types.Window', {
            templateId: sampleType.get('sample_type_template')
        });

        sampleTypesWindow.show();
        sampleTypesWindow.setData(sampleType.data);
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
        var jsonData = {
            name: sampleType.get('name'),
            sample_type_template: sampleType.get('sample_type_template'),
            attributes: sampleType.get('attributes')
        };
        Ext.Ajax.request({
            url: url,
            method: 'POST',
            jsonData: jsonData,
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
