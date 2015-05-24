Ext.define('Slims.view.sample.wizard.SampleTypePanel', {
    extend: 'Ext.form.Panel',

    layout: 'fit',

    initComponent: function() {
        this.buildItems();
        this.callParent(arguments);
    },

    buildItems: function() {
        this.items = [{
            xtype: 'label',
            padding: 10,
            width: '100%',
            html: '<center><h2>Welcome to Create New Sample Wizard.</h2></center>'
        }, {
            xtype: 'grid',
            name: 'sampleTypesGrid',
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
                store: Ext.StoreMgr.get('sampleTypes'),
                queryMode: 'local',
                displayField: 'name',
                valueField: 'id',
                listeners: {
                    change: function(combo, value) {
                        var template = combo.store.findRecord(combo.valueField, value),
                            attributes = template.get('attributes');
                        this.down('grid[name=sampleTypesGrid]').getStore().loadData(attributes);
                    },
                    scope: this
                }
            }],
            store: {
                fields: ['label', 'value'],
                data: []
            },
            columns: {
                defaults: this.columnsDefaults,
                items: [{
                    dataIndex: 'label',
                    flex: 1,
                    header: 'Label'
                }, {
                    dataIndex: 'value',
                    flex: 1,
                    header: 'Value'
                }]
            }
        }];
    }
});