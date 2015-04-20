Ext.define('Slims.view.sample.Wizard', {
    extend: 'Ext.window.Window',
    xtype: 'samplewizard',

    layout: 'fit',
    border: false,
    width: 600,
    height: 500,

    title: 'Create Sample Wizard Tool',
    requires: [
        'Ext.form.field.Checkbox'
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
                this.getSelectPositionPanel()
            ]
        }];

        this.callParent();
    },

    getSelectSampleTypePanel: function() {
        return Ext.create('Ext.form.Panel', {
            layout: 'fit',
            items: [{
                xtype: 'label',
                padding: 10,
                width: '100%',
                html: '<center><h2>Welcome to Create New Sample Wizard.</h2></center>'
            }, {
                xtype: 'grid',
                name: 'templatesGrid',
                tbar: [{
                    xtype: 'combo',
                    padding: 10,
                    width: 400,
                    name: 'templateId',
                    fieldLabel: 'Please, select <i>Sample Type</i> for a new sample and press <i>Next</i>',
                    labelAlign: 'top',
                    emptyText: 'Select to continue',
                    allowBlank: false,
                    editable: false,
                    store: Ext.StoreMgr.get('templates'),
                    queryMode: 'local',
                    displayField: 'name',
                    valueField: 'id',
                    listeners: {
                        change: function(combo, value) {
                            var template = combo.store.findRecord(combo.valueField, value),
                                attributes = template.get('attributes');
                            this.down('grid[name=templatesGrid]').getStore().loadData(attributes);
                        },
                        scope: this
                    }
                }],
                store: {
                    fields: ['order', 'label', 'type'],
                    data: []
                },
                columns: {
                    defaults: this.columnsDefaults,
                    items: [{
                        dataIndex: 'order',
                        width: 40,
                        header: '#'
                    }, {
                        dataIndex: 'label',
                        flex: 1,
                        header: 'Label'
                    }, {
                        dataIndex: 'type',
                        width: 200,
                        header: 'Type'
                    }]
                }
            }],
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

    getSelectPositionPanel: function() {
        var createRowPanel = function(data) {
            var items = [];
            for (var i in data) {
                var conf = data[i] || {};
                var cb = Ext.create('Ext.form.field.Checkbox',  {
                    hideLabel: true,
                    margin: 3,
                    checked: !!conf.color,
                    cls: 'slims-wizard-position-cb',
                    name: conf.id || 'empty',
                    style: {
                        'background': conf.color || 'white',
                        margin: '3px'
                    },
                    sample_data: conf.sample_data || {}
                });
                items.push(cb);
            }
            var rowPanel = Ext.create('Ext.panel.Panel', {
                layout: 'hbox',
                items: items
            });
            return rowPanel;
        }

        var colsItems = [],
            data = this.getTempData();
        for (var i in data) {
            var colData = data[i];
            colsItems.push(createRowPanel(colData));
        }
        var positionsPanel = Ext.create('Ext.panel.Panel', {
            layout: 'vbox',
            items: colsItems,
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

        return positionsPanel;
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
            ]
        ];
    }
});