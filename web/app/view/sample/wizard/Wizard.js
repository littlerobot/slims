Ext.define('Slims.view.sample.wizard.Wizard', {
    extend: 'Ext.window.Window',
    xtype: 'samplewizard',

    title: 'Sample Wizard Tool',
    layout: 'fit',
    border: false,
    width: 600,
    height: 500,
    modal: true,

    requires: [
        'Ext.form.field.Checkbox',
        'Slims.view.sample.wizard.SampleTypePanel',
        'Slims.view.sample.wizard.SampleInstancePanel',
        'Slims.view.sample.wizard.AttributesForm'
    ],

    // selected wizard values
    data: {},

    initComponent: function() {
        this.items = [{
            xtype: 'panel',
            name: 'cardPanel',
            layout: 'card',
            items: [
                this.buildSelectSampleTypePanel(),
                this.buildSelectSampleInstancePanel(),
                this.buildSetupAttributesForm()
            ]
        }];

        this.callParent();
    },

    buildSelectSampleTypePanel: function() {
        return Ext.create('Slims.view.sample.wizard.SampleTypePanel', {
            buttons: ['->', {
                text: 'Next',
                handler: function() {
                    var sampleTemplatePanel = this.down('panel[name=cardPanel]').layout.getActiveItem();
                    var valid = sampleTemplatePanel.form.isValid();
                    if (valid) {
                        var sampleTemplate = sampleTemplatePanel.down('combo').getValue();
                        this.data.sampleTemplate = sampleTemplate;
                        this.nextTab();
                    }
                },
                scope: this
            }]
        });
    },

    buildSelectSampleInstancePanel: function() {
        return Ext.create('Slims.view.sample.wizard.SampleInstancePanel', {
            buttons: [{
                text: 'Prev',
                handler: this.prevTab,
                scope: this
            }, '->', {
                text: 'Next',
                handler: function() {
                    var sampleInstancePanel = this.down('panel[name=cardPanel]').layout.getActiveItem();
                    var valid = sampleInstancePanel.form.isValid();
                    if (valid) {
                        this.data.sampleInstanceId = sampleInstancePanel.down('combo').getValue();
                        this.sampleInstanceStoreAttributes = sampleInstancePanel.down('grid').getStore().data.items;
                        this.data.storeAttributes = this.sampleInstanceStoreAttributes;
                        this.nextTab();
                    }
                },
                scope: this
            }]
        });
    },

    buildSetupAttributesForm: function() {
        return Ext.create('Slims.view.sample.wizard.AttributesForm', {
            listeners: {
                show: function() {
                    this.down('attributesform').loadAttributes(this.sampleInstanceStoreAttributes);
                },
                scope: this
            },
            buttons: [{
                text: 'Prev',
                handler: this.prevTab,
                scope: this
            }, '->', {
                text: 'Save',
                name: 'save',
                handler: this.saveChanges,
                scope: this
            }]
        });
    },

    nextTab: function() {
        var cardPanel = this.down('panel[name=cardPanel]');
        cardPanel.layout.setActiveItem(cardPanel.layout.getNext());
    },

    prevTab: function() {
        var cardPanel = this.down('panel[name=cardPanel]');
        cardPanel.layout.setActiveItem(cardPanel.layout.getPrev());
    },

    saveChanges: function() {
        var storeAtrributesValues = this.down('attributesform').getValues();
        storeAtrributesValues.samplesColor = this.down('attributesform').down('[name=samplesColor]').getValue();

        this.data.storeAtrributesValues = storeAtrributesValues;
        this.data.storeAtrributesValues.sampleType = this.data.sampleTemplate;
        this.data.storeAtrributesValues.sampleInstanceTemplate = this.data.sampleInstanceId;

        this.fireEvent('save', this.data);

        this.close();
    }
});