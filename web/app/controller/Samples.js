Ext.define('Slims.controller.Samples', {
    extend: 'Ext.app.Controller',

    views: [
        'sample.wizard.Wizard',
        'sample.Page',
        'sample.Grid'
    ],

    stores: ['sample.Samples'],
    models: ['sample.Sample'],

    refs: [{
        ref: 'wizard',
        selector: 'samplewizard'
    }],

    init: function() {
        this.control({
            'samplespage button[name=createSample]': {
                click: this.openWizard
            },
            'samplewizard button[name=commit]': {
                click: this.createNewSample
            }
        });

        this.createStores();
    },

    openWizard: function() {
        Ext.create('Slims.view.sample.wizard.Wizard').show();
    },

    createStores: function() {
        // var sampleTypesStore = Ext.create('Slims.store.sample.Types', {
        //     storeId: 'sampleTypes'
        // });

        // sampleTypesStore.load();
    },

    createNewSample: function() {
        var wizard = this.getWizard();

        var storeAttributesPanel = wizard.down('panel[name=cardPanel]').layout.getActiveItem();

        if (!storeAttributesPanel.form.isValid()) {
            return;
        }

        var  storeAttributes = storeAttributesPanel.form.getValues();

        Ext.Ajax.request({
            url: Slims.Url.getRoute('setsamples'),
            method: 'POST',
            params: {
                sample_template_id: wizard.sampleTemplate,
                sample_instance_id: wizard.sampleInstanceId,
                container_id: wizard.selectedContainer,
                store_attributes: storeAttributes,
                positions: wizard.positionsMap,
                colour: null
            },
            scope: this,
            success: function(xhr) {
                // refresh grid
                // close window
            },
            failure: function() {
                // show error message
            }
        });
    }
});
