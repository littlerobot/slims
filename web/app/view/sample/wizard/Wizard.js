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
        'Slims.view.sample.wizard.PositionsPanel',
        'Slims.view.sample.wizard.SampleInstancePanel',
        'Slims.view.sample.wizard.StoreAttributesPanel'
    ],

    initComponent: function() {
        this.items = [{
            xtype: 'panel',
            name: 'cardPanel',
            layout: 'card',
            items: [
                this.buildSelectSampleTypePanel(),
                this.buildSelectSampleInstancePanel(),
                this.buildSetupStoreAttributesPanel()
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
                        this.sampleTemplate = sampleTemplatePanel.down('combo').getValue();
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
                        this.sampleInstanceId = sampleInstancePanel.down('combo').getValue();
                        this.sampleInstanceStoreAttributes = sampleInstancePanel.down('grid').getStore().data.items;
                        this.nextTab();
                    }
                },
                scope: this
            }]
        });
    },

    buildSetupStoreAttributesPanel: function() {
        return Ext.create('Slims.view.sample.wizard.StoreAttributesPanel', {
            name: 'step3',
            listeners: {
                show: function() {
                    this.down('[name=step3]').loadAttributes(this.sampleInstanceStoreAttributes);
                },
                scope: this
            },
            buttons: [{
                text: 'Prev',
                handler: this.prevTab,
                scope: this
            }, '->', {
                text: 'Commit changes',
                name: 'commit'
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
    }
});