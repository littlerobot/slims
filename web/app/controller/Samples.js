Ext.define('Slims.controller.Samples', {
    extend: 'Ext.app.Controller',

    views: [
        'sample.wizard.Wizard',
        'sample.Page',
        'sample.Grid',
        'sample.PositionsGrid',
        'sample.search.Grid',
        'sample.search.FiltersPanel'
    ],

    stores: ['sample.Samples'],
    models: ['sample.Sample'],

    refs: [{
        ref: 'wizard',
        selector: 'samplewizard'
    }, {
        ref: 'grid',
        selector: 'samplesgrid'
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
    },

    openWizard: function() {
        Ext.create('Slims.view.sample.wizard.Wizard').show();
    },

    createNewSample: function() {
        var wizard = this.getWizard();

        var attributesForm = wizard.down('panel[name=cardPanel]').layout.getActiveItem();

        if (!attributesForm.form.isValid()) {
            return;
        }

        var  storeAttributes = attributesForm.form.getValues(),
            colour = storeAttributes.samplesColor;

        delete storeAttributes.samplesColor;

        wizard.setLoading('Saving...');
        Ext.Ajax.request({
            url: Slims.Url.getRoute('setsamples'),
            method: 'POST',
            params: {
                sample_template_id: wizard.sampleTemplate,
                sample_instance_id: wizard.sampleInstanceId,
                container_id: wizard.selectedContainer,
                store_attributes: storeAttributes,
                positions: wizard.positionsMap,
                colour: colour
            },
            scope: this,
            success: function(xhr) {
                wizard.setLoading(false);
                this.getGrid().getStore().load();
                wizard.close();
            },
            failure: function() {
                wizard.setLoading(false);
            }
        });
    }
});
