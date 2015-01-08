Ext.define('Slims.view.templates.TemplatesGrid', {
    extend: 'Ext.grid.Panel',
    xtype: 'templatesgrid',

    requires: ['Ext.grid.column.Action'],

    initComponent: function() {
        this.store = Ext.create('Slims.store.Templates');

        this.columns = [{
            text: 'Name',
            dataIndex: 'name',
            flex: 1
        }, {
            xtype: 'actioncolumn',
            width: 30,
            items: [{
                icon: '/resources/images/delete.png',
                tooltip: 'Delete',
                scope: this,
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    this.fireEvent('editrecord', rec);
                }
            }]
        }];

        this.tbar = [{
            text: 'Add template',
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