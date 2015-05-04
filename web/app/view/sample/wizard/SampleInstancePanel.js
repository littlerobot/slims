Ext.define('Slims.view.sample.wizard.SampleInstancePanel', {
    extend: 'Ext.form.Panel',

    layout: 'fit',
    columnsDefaults: {
        menuDisabled: true,
        resizable: false,
        sortable: false,
        draggable: false
    },

    initComponent: function() {
        this.buildItems();
        this.callParent(arguments);
    },

    buildItems: function() {
        this.items = [{
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
                    change: this.updateGridsData,
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
        }];
    },

    updateGridsData: function(combo, value) {
        var template = combo.store.findRecord(combo.valueField, value);
        this.down('grid[name=storeAttributesGrid]').getStore().loadData(template.get('store'));
        this.down('grid[name=removeAttributesGrid]').getStore().loadData(template.get('remove'));
    }
});