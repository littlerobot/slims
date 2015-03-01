Ext.define('Slims.view.sample.types.Grid', {
    extend: 'Ext.grid.Panel',
    xtype: 'sampletypesgrid',

    requires: ['Ext.grid.plugin.RowExpander'],
    border: true,

    plugins: [{
        ptype: 'rowexpander',
        rowBodyTpl: [
            '<p><b>Name:</b> {name}</p>'
        ]
    }],

    initComponent: function() {
        this.store = Ext.create('Slims.store.sample.Types');

        this.columns = [{
            text: 'ID',
            dataIndex: 'id',
            width: 100
        }, {
            text: 'Name',
            dataIndex: 'name',
            flex: 1
        }, {
            text: 'Attributes',
            dataIndex: 'attributes',
            flex: 1
        }];

        this.tbar = [{
            text: 'Add type',
            icon: '/resources/images/add.png',
            name: 'addTemplate'
        }, '->', {
            width: 25,
            icon: '/resources/images/reload.png',
            name: 'reloadGrid'
        }];

        this.callParent();

        this.on('afterrender', this.loadData, this);
    },

    loadData: function() {
        this.getStore().load();
    }
});