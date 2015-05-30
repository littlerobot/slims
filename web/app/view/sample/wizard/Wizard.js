Ext.define('Slims.view.sample.wizard.Wizard', {
    extend: 'Ext.window.Window',
    xtype: 'samplewizard',

    layout: 'fit',
    border: false,
    width: 600,
    height: 500,
    modal: true,

    title: 'Create Sample Wizard Tool',
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
                this.buildSelectContainerPanel(),
                this.buildSelectPositionPanel(),
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
                        var sampleInstanceId = sampleInstancePanel.down('combo').getValue();
                        this.sampleInstanceStoreAttributes = sampleInstancePanel.down('grid').getStore().data.items;
                        this.nextTab();
                    }
                },
                scope: this
            }]
        });
    },

    buildSelectContainerPanel: function() {
        return Ext.create('Ext.form.Panel', {
            layout: 'fit',
            name: 'step3',
            tbar: [{
                xtype: 'label',
                padding: 10,
                html: 'Select Container for storing new Sample and press <i>Next</i>'
            }],
            items: [{
                xtype: 'containersgrid',
                readOnly: true,
                listeners: {
                    selectionchange: function(tree, selected) {
                        tree.view.up('panel[name=step3]').down('button[name=next]').setDisabled(!selected.length);
                    }
                }
            }],
            buttons: [{
                text: 'Prev',
                handler: this.prevTab,
                scope: this
            }, '->', {
                text: 'Next',
                name: 'next',
                disabled: true,
                handler: function(btn) {
                    var selected = this.down('[name=step3]').down('containersgrid').selModel.selected.get(0);
                    this.down('[name=step4]').selectedContainer = selected;
                    this.selectedContainer = selected;
                    this.nextTab();
                },
                scope: this
            }]
        });
    },

    buildSelectPositionPanel: function() {
        return Ext.create('Slims.view.sample.wizard.PositionsPanel', {
            name: 'step4',
            width: '100%',
            height: '100%',
            buttons: [{
                text: 'Prev',
                handler: this.prevTab,
                scope: this
            }, '->', {
                text: 'Store samples',
                handler: function() {
                    var positionsPanel = this.down('panel[name=cardPanel]').layout.getActiveItem();
                    this.postionsMap = positionsPanel.getValue();

                    if (!this.postionsMap) {
                        Ext.Msg.alert('No container selected', 'Please select one ore more containers.');
                        return;
                    }

                    this.nextTab();
                },
                scope: this
            }]
        });
    },

    buildSetupStoreAttributesPanel: function() {
        return Ext.create('Slims.view.sample.wizard.StoreAttributesPanel', {
            name: 'step5',
            listeners: {
                show: function() {
                    this.down('[name=step5]').loadAttributes(this.sampleInstanceStoreAttributes);
                },
                scope: this
            },
            buttons: [{
                text: 'Prev',
                handler: this.prevTab,
                scope: this
            }, '->', {
                text: 'Commit changes',
                handler: this.commitChanges,
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

    commitChanges: function() {
        var storeAttributesPanel = this.down('panel[name=cardPanel]').layout.getActiveItem();

        if (!storeAttributesPanel.form.isValid()) {
            return;
        }

        // commit changes
    }
});