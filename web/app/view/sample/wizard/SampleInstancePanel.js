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
        this.on('show', this.filterInstanceTemplatesWithoutAttributes, this);
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
                fieldLabel: 'Select the template',
                labelAlign: 'top',
                emptyText: 'Select to continue',
                allowBlank: false,
                editable: false,
                store: {
                    fields: [{
                        name: 'id'
                    }, {
                        name: 'name'
                    }, {
                        name: 'store',
                        type: 'auto',
                        useNull: true
                    }, {
                        name: 'remove',
                        type: 'auto',
                        useNull: true
                    }, {
                        name: 'editable',
                        type: 'bool',
                        defaultValue: true
                    }],
                    data: []
                },
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
    },

    filterInstanceTemplatesWithoutAttributes: function() {
        var combo = this.down('combo'),
            comboStore = combo.store,
            instanceTemplatesStore = Ext.StoreMgr.get('instanceTemplates'), instanseTemplatesWithAttributes = [];

        instanceTemplatesStore.data.each(function(template) {
            if (template.get('store').length > 0 && template.get('remove').length > 0) {
                instanseTemplatesWithAttributes.push(template);
            }
        });

        if (instanseTemplatesWithAttributes.length == 0) {
            Ext.Msg.alert('Cannot configure samples', 'You haven\'t instance templates with store and remove attributes, please add one at least to continue.');
            // this.up('samplewizard').close();
        }

        comboStore.loadData(instanseTemplatesWithAttributes);
    }
});