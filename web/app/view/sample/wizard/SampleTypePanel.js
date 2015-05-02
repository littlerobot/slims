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
        }];
    }
});