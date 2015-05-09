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
        'Slims.view.sample.wizard.SampleInstancePanel'
    ],

    initComponent: function() {
        this.items = [{
            xtype: 'panel',
            name: 'cardPanel',
            layout: 'card',
            items: [
                this.getSelectSampleTypePanel(),
                this.getSelectSampleInstancePanel(),
                this.getSelectContainerPanel(),
                this.getSelectPositionPanel()
            ]
        }];

        this.callParent();
    },

    getSelectSampleTypePanel: function() {
        return Ext.create('Slims.view.sample.wizard.SampleTypePanel', {
            buttons: ['->', {
                text: 'Next',
                handler: function() {
                    var valid = this.down('panel[name=cardPanel]').layout.getActiveItem().form.isValid();
                    if (valid) {
                        this.nextTab();
                    }
                },
                scope: this
            }]
        });
    },

    getSelectSampleInstancePanel: function() {
        return Ext.create('Slims.view.sample.wizard.SampleInstancePanel', {
            buttons: [{
                text: 'Prev',
                handler: this.prevTab,
                scope: this
            }, '->', {
                text: 'Next',
                handler: function() {
                    var valid = this.down('panel[name=cardPanel]').layout.getActiveItem().form.isValid();
                    if (valid) {
                        this.nextTab();
                    }
                },
                scope: this
            }]
        });
    },

    getSelectContainerPanel: function() {
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
                    this.down('[name=step4]').container = selected;
                    this.nextTab();
                },
                scope: this
            }]
        });
    },

    getSelectPositionPanel: function() {
        return Ext.create('Slims.view.sample.wizard.PositionsPanel', {
            name: 'step4',
            buttons: [{
                text: 'Prev',
                handler: this.prevTab,
                scope: this
            }, '->', {
                text: 'Finish',
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

    commitChanges: function() {}
});