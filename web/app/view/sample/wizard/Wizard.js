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
        'Slims.view.sample.wizard.PositionsView'
    ],

    columnsDefaults: {
        menuDisabled: true,
        resizable: false,
        sortable: false,
        draggable: false
    },

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
        return Ext.create('Ext.form.Panel', {
            layout: 'fit',
            items: [{
                xtype: 'panel',
                tbar: [{
                    xtype: 'combo',
                    padding: 10,
                    width: 400,
                    name: 'sampleInstanceTemplateId',
                    fieldLabel: 'Step #2. Select <i>Sample Instance Template</i> and press <i>Next</i>',
                    labelAlign: 'top',
                    emptyText: 'Select to continue',
                    allowBlank: false,
                    editable: false,
                    store: Ext.StoreMgr.get('instanceTemplates'),
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'id',
                    listeners: {
                        change: function(combo, value) {
                            var template = combo.store.findRecord(combo.valueField, value);
                            this.down('grid[name=storeAttributesGrid]').getStore().loadData(template.get('store'));
                            this.down('grid[name=removeAttributesGrid]').getStore().loadData(template.get('remove'));
                        },
                        scope: this
                    }
                }],
                layout: 'hbox',
                items: [{
                    xtype: 'grid',
                    style: 'border-right: 1px solid #99BBE8;',
                    title: 'Store Attributes',
                    name: 'storeAttributesGrid',
                    height: '100%',
                    flex: 1,
                    store: {
                        fields: ['order', 'label', 'type'],
                        data: []
                    },
                    columns: {
                        defaults: this.columnsDefaults,
                        items: [{
                            dataIndex: 'order',
                            width: 30,
                            header: '#'
                        }, {
                            dataIndex: 'label',
                            flex: 1,
                            header: 'Label'
                        }, {
                            dataIndex: 'type',
                            width: 120,
                            header: 'Type'
                        }]
                    }
                }, {
                    xtype: 'grid',
                    title: 'Remove Attributes',
                    name: 'removeAttributesGrid',
                    height: '100%',
                    flex: 1,
                    store: {
                        fields: ['order', 'label', 'type'],
                        data: []
                    },
                    columns: {
                        defaults: this.columnsDefaults,
                        items: [{
                            dataIndex: 'order',
                            width: 30,
                            header: '#'
                        }, {
                            dataIndex: 'label',
                            flex: 1,
                            header: 'Label'
                        }, {
                            dataIndex: 'type',
                            width: 120,
                            header: 'Type'
                        }]
                    }
                }]
            }],
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
        var positionsView = Ext.create('Slims.view.sample.wizard.PositionsView', {
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

        return positionsView;
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