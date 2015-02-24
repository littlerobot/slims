Ext.define('Slims.view.sample.types.Grid', {
    extend: 'Ext.grid.Panel',
    xtype: 'sampletypesgrid',

    requires: ['Ext.grid.plugin.RowExpander'],
    border: true,

    initComponent: function() {
        this.store = Ext.create('Slims.store.sample.Types');

        this.columns = [{
            text: 'Name',
            dataIndex: 'name',
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

        this.plugins = [{
            ptype: 'rowexpander',
            rowBodyTpl: [
                '<p><b>Company:</b> {name}</p><br>',
                '<p><b>Summary:</b> {name}</p>'
            ]
        }];

        this.callParent();

        this.on('afterrender', this.loadData, this);
    },

    loadData: function() {
        this.getStore().load();
    }
});