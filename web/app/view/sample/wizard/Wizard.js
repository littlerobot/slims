Ext.define('Slims.view.sample.wizard.Wizard', {
    extend: 'Ext.window.Window',
    xtype: 'samplewizard',

    layout: 'fit',
    border: false,
    width: 600,
    height: 500,

    title: 'Create Sample Wizard Tool',
    requires: [
        'Ext.form.field.Checkbox',
        'Slims.view.sample.wizard.SampleTypePanel',
        'Slims.view.sample.wizard.PositionsView',
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
                this.getSelectPositionView()
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
                handler: this.nextTab,
                scope: this
            }]
        });
    },

    getSelectPositionView: function() {
        return Ext.create('Slims.view.sample.wizard.PositionsView', {
            data: this.getTempData(),
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

    commitChanges: function() {},

    getTempData: function() {
        return [
            [
                {
                    id: 1,
                    sample_data: {},
                    color: 'red'
                },{
                    id: 2,
                    sample_data: {},
                    color: 'green'
                },{
                    id: 3,
                    sample_data: {},
                    color: 'blue'
                },{
                    id: 4,
                    sample_data: {},
                    color: 'black'
                },null,{
                    id: 6,
                    sample_data: {},
                    color: 'red'
                },{
                    id: 2,
                    sample_data: {},
                    color: 'green'
                },{
                    id: 3,
                    sample_data: {},
                    color: 'blue'
                },{
                    id: 4,
                    sample_data: {},
                    color: 'black'
                },null,{
                    id: 6,
                    sample_data: {},
                    color: 'red'
                }
            ], [
                {
                    id: 1,
                    sample_data: {},
                    color: 'red'
                },{
                    id: 2,
                    sample_data: {},
                    color: 'green'
                },{
                    id: 3,
                    sample_data: {},
                    color: 'blue'
                },{
                    id: 4,
                    sample_data: {},
                    color: 'black'
                },null,{
                    id: 6,
                    sample_data: {},
                    color: 'red'
                },{
                    id: 2,
                    sample_data: {},
                    color: 'green'
                },{
                    id: 3,
                    sample_data: {},
                    color: 'blue'
                },{
                    id: 4,
                    sample_data: {},
                    color: 'black'
                },null,{
                    id: 6,
                    sample_data: {},
                    color: 'red'
                }
            ], [
                null,{
                    id: 2,
                    sample_data: {},
                    color: 'green'
                },null,{
                    id: 4,
                    sample_data: {},
                    color: 'black'
                },null,{
                    id: 6,
                    sample_data: {},
                    color: 'red'
                },{
                    id: 2,
                    sample_data: {},
                    color: 'green'
                },null,{
                    id: 4,
                    sample_data: {},
                    color: 'black'
                },null,{
                    id: 6,
                    sample_data: {},
                    color: 'red'
                }
            ], [
                {
                    id: 1,
                    sample_data: {},
                    color: 'red'
                },{
                    id: 2,
                    sample_data: {},
                    color: 'green'
                },{
                    id: 3,
                    sample_data: {},
                    color: 'blue'
                },{
                    id: 4,
                    sample_data: {},
                    color: 'black'
                },null,{
                    id: 6,
                    sample_data: {},
                    color: 'red'
                },{
                    id: 2,
                    sample_data: {},
                    color: 'green'
                },{
                    id: 3,
                    sample_data: {},
                    color: 'blue'
                },{
                    id: 4,
                    sample_data: {},
                    color: 'black'
                },null,{
                    id: 6,
                    sample_data: {},
                    color: 'red'
                }
            ], [
                {
                    id: 1,
                    sample_data: {},
                    color: 'red'
                },{
                    id: 2,
                    sample_data: {},
                    color: 'green'
                },{
                    id: 3,
                    sample_data: {},
                    color: 'white'
                },{
                    id: 4,
                    sample_data: {},
                    color: 'black'
                },null,{
                    id: 6,
                    sample_data: {},
                    color: 'white'
                },{
                    id: 2,
                    sample_data: {},
                    color: 'green'
                },{
                    id: 3,
                    sample_data: {},
                    color: 'blue'
                },{
                    id: 4,
                    sample_data: {},
                    color: 'black'
                },null,{
                    id: 6,
                    sample_data: {},
                    color: 'red'
                }
            ]
        ];
    }
});